<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class MidtransService
{
    public function snapJsUrl(): string
    {
        return $this->isProduction()
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    }

    public function isProduction(): bool
    {
        $configured = (bool) config('services.midtrans.is_production', false);
        $serverKey = $this->serverKey();
        $inferred = $this->inferModeFromServerKey($serverKey);

        if ($inferred === null) {
            return $configured;
        }

        if ($configured !== $inferred) {
            Log::warning('MIDTRANS_IS_PRODUCTION does not match MIDTRANS_SERVER_KEY format. Using key-derived mode.', [
                'configured_is_production' => $configured,
                'inferred_is_production' => $inferred,
            ]);
        }

        return $inferred;
    }

    public function createSnapTransaction(array $payload): array
    {
        try {
            $response = $this->client()->post($this->snapBaseUrl().'/transactions', $payload);
        } catch (\Throwable $exception) {
            throw new RuntimeException('Failed to connect Midtrans Snap API: '.$exception->getMessage(), 0, $exception);
        }

        if (! $response->successful()) {
            throw new RuntimeException('Failed to create Midtrans transaction: '.$response->body());
        }

        return $response->json();
    }

    public function getTransactionStatus(string $orderId): array
    {
        try {
            $response = $this->client()->get($this->coreBaseUrl().'/'.$orderId.'/status');
        } catch (\Throwable $exception) {
            throw new RuntimeException('Failed to connect Midtrans Status API: '.$exception->getMessage(), 0, $exception);
        }

        if (! $response->successful()) {
            throw new RuntimeException('Failed to get Midtrans transaction status: '.$response->body());
        }

        return $response->json();
    }

    public function verifySignature(array $payload): bool
    {
        if (
            ! isset($payload['order_id']) ||
            ! isset($payload['status_code']) ||
            ! isset($payload['gross_amount']) ||
            ! isset($payload['signature_key'])
        ) {
            return false;
        }

        $expected = hash('sha512', $payload['order_id'].$payload['status_code'].$payload['gross_amount'].$this->serverKey());

        return hash_equals($expected, (string) $payload['signature_key']);
    }

    private function client(): PendingRequest
    {
        $verifySsl = (bool) config('services.midtrans.verify_ssl', true);

        return Http::withBasicAuth($this->serverKey(), '')
            ->acceptJson()
            ->asJson()
            ->withOptions(['verify' => $verifySsl])
            ->timeout(15);
    }

    private function snapBaseUrl(): string
    {
        return $this->isProduction()
            ? 'https://app.midtrans.com/snap/v1'
            : 'https://app.sandbox.midtrans.com/snap/v1';
    }

    private function coreBaseUrl(): string
    {
        return $this->isProduction()
            ? 'https://api.midtrans.com/v2'
            : 'https://api.sandbox.midtrans.com/v2';
    }

    private function serverKey(): string
    {
        $serverKey = (string) config('services.midtrans.server_key');

        if ($serverKey === '') {
            throw new RuntimeException('MIDTRANS_SERVER_KEY is not configured.');
        }

        return $serverKey;
    }

    private function inferModeFromServerKey(string $serverKey): ?bool
    {
        if (str_starts_with($serverKey, 'SB-Mid-server-')) {
            return false;
        }

        if (str_starts_with($serverKey, 'Mid-server-')) {
            return true;
        }

        return null;
    }
}
