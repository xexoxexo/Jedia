<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE users MODIFY phone TEXT NULL');
            DB::statement('ALTER TABLE merchants MODIFY phone TEXT NOT NULL');
            DB::statement('ALTER TABLE locations MODIFY address TEXT NOT NULL');
            DB::statement('ALTER TABLE electric_transaction_details MODIFY subscription_number TEXT NOT NULL');

            return;
        }

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE users ALTER COLUMN phone TYPE TEXT');
            DB::statement('ALTER TABLE merchants ALTER COLUMN phone TYPE TEXT');
            DB::statement('ALTER TABLE locations ALTER COLUMN address TYPE TEXT');
            DB::statement('ALTER TABLE electric_transaction_details ALTER COLUMN subscription_number TYPE TEXT');

            return;
        }

        if ($driver === 'sqlite') {
            // SQLite stores flexible text affinity; no-op for transition migration.
            return;
        }

        throw new \RuntimeException("Unsupported database driver for sensitive-column migration: {$driver}");
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE users MODIFY phone VARCHAR(255) NULL');
            DB::statement('ALTER TABLE merchants MODIFY phone VARCHAR(255) NOT NULL');
            DB::statement('ALTER TABLE locations MODIFY address VARCHAR(255) NOT NULL');
            DB::statement('ALTER TABLE electric_transaction_details MODIFY subscription_number VARCHAR(255) NOT NULL');

            return;
        }

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE users ALTER COLUMN phone TYPE VARCHAR(255)');
            DB::statement('ALTER TABLE merchants ALTER COLUMN phone TYPE VARCHAR(255)');
            DB::statement('ALTER TABLE locations ALTER COLUMN address TYPE VARCHAR(255)');
            DB::statement('ALTER TABLE electric_transaction_details ALTER COLUMN subscription_number TYPE VARCHAR(255)');

            return;
        }

        if ($driver === 'sqlite') {
            return;
        }

        throw new \RuntimeException("Unsupported database driver for sensitive-column rollback: {$driver}");
    }
};
