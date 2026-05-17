<?php

namespace App\Console\Commands;

use App\Models\ElectricTransactionDetail;
use App\Models\Location;
use App\Models\Merchant;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Throwable;

class BackfillSensitiveDataEncryption extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'security:encrypt-sensitive-data
                            {--chunk=500 : Number of records processed per batch}
                            {--dry-run : Show what would be encrypted without writing changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backfill plaintext sensitive fields to encrypted values in batches.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $chunkSize = max((int) $this->option('chunk'), 1);
        $dryRun = (bool) $this->option('dry-run');

        $targets = [
            [
                'label' => 'users.phone',
                'column' => 'phone',
                'query' => fn () => User::query(),
                'where_columns' => ['id'],
            ],
            [
                'label' => 'merchants.phone',
                'column' => 'phone',
                'query' => fn () => Merchant::query(),
                'where_columns' => ['id'],
            ],
            [
                'label' => 'locations.address',
                'column' => 'address',
                'query' => fn () => Location::withTrashed(),
                'where_columns' => ['id'],
            ],
            [
                'label' => 'electric_transaction_details.subscription_number',
                'column' => 'subscription_number',
                'query' => fn () => ElectricTransactionDetail::query()
                    ->orderBy('transaction_id')
                    ->orderBy('electric_token')
                    ->orderBy('created_at'),
                'where_columns' => ['transaction_id', 'electric_token', 'created_at'],
            ],
        ];

        $summary = [];

        foreach ($targets as $target) {
            $label = $target['label'];
            $column = $target['column'];
            $queryFactory = $target['query'];
            $whereColumns = $target['where_columns'];

            $this->line("Processing {$label}...");

            $result = $this->processTarget(
                queryFactory: $queryFactory,
                column: $column,
                whereColumns: $whereColumns,
                chunkSize: $chunkSize,
                dryRun: $dryRun
            );

            $summary[] = [
                'Field' => $label,
                'Total' => $result['total'],
                'Encrypted' => $result['encrypted'],
                'Already Encrypted / Empty' => $result['skipped'],
                'Failed' => $result['failed'],
            ];

            $this->line("Done {$label}: encrypted={$result['encrypted']}, skipped={$result['skipped']}, failed={$result['failed']}");
            $this->newLine();
        }

        $this->table(
            ['Field', 'Total', 'Encrypted', 'Already Encrypted / Empty', 'Failed'],
            $summary
        );

        if ($dryRun) {
            $this->warn('Dry run mode: no data was changed.');
        } else {
            $this->info('Backfill completed.');
        }

        return self::SUCCESS;
    }

    private function processTarget(callable $queryFactory, string $column, array $whereColumns, int $chunkSize, bool $dryRun): array
    {
        /** @var Builder $baseQuery */
        $baseQuery = $queryFactory();
        $total = (clone $baseQuery)->whereNotNull($column)->count();

        $encrypted = 0;
        $skipped = 0;
        $failed = 0;

        /** @var Builder $query */
        $query = $queryFactory();
        $query->whereNotNull($column)->chunk($chunkSize, function ($models) use ($column, $dryRun, &$encrypted, &$skipped, &$failed) {
            foreach ($models as $model) {
                if (! $model instanceof Model) {
                    $skipped++;
                    continue;
                }

                $rawValue = $model->getRawOriginal($column);

                if ($rawValue === null || $rawValue === '') {
                    $skipped++;
                    continue;
                }

                if (! is_string($rawValue)) {
                    $rawValue = (string) $rawValue;
                }

                if ($this->appearsEncrypted($rawValue)) {
                    $skipped++;
                    continue;
                }

                if ($dryRun) {
                    $encrypted++;
                    continue;
                }

                try {
                    $ciphertext = Crypt::encryptString($rawValue);
                    $affected = $this->updateEncryptedValue(
                        model: $model,
                        column: $column,
                        encryptedValue: $ciphertext,
                        whereColumns: $whereColumns
                    );

                    if ($affected === 0) {
                        $failed++;
                        $this->warn("No rows updated for {$model->getTable()}.{$column} (model key {$model->getKey()}).");
                        continue;
                    }

                    $encrypted += $affected;
                } catch (Throwable $exception) {
                    $failed++;
                    $this->warn("Failed to encrypt {$model->getTable()}.{$column} for model key {$model->getKey()}: {$exception->getMessage()}");
                }
            }
        });

        return [
            'total' => $total,
            'encrypted' => $encrypted,
            'skipped' => $skipped,
            'failed' => $failed,
        ];
    }

    private function updateEncryptedValue(Model $model, string $column, string $encryptedValue, array $whereColumns): int
    {
        $query = DB::table($model->getTable());

        foreach ($whereColumns as $whereColumn) {
            $rawWhereValue = $model->getRawOriginal($whereColumn);

            if ($rawWhereValue === null) {
                $query->whereNull($whereColumn);
                continue;
            }

            $query->where($whereColumn, $rawWhereValue);
        }

        return $query->update([
            $column => $encryptedValue,
        ]);
    }

    private function appearsEncrypted(string $value): bool
    {
        try {
            Crypt::decryptString($value);

            return true;
        } catch (DecryptException $exception) {
            return false;
        }
    }
}
