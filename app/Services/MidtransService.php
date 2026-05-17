<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class MidtransService
{
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
        return Http::withBasicAuth($this->serverKey(), '')
            ->acceptJson()
            ->asJson()
            ->timeout(15);
    }

    private function snapBaseUrl(): string
    {
        return config('services.midtrans.is_production')
            ? 'https://app.midtrans.com/snap/v1'
            : 'https://app.sandbox.midtrans.com/snap/v1';
    }

    private function coreBaseUrl(): string
    {
        return config('services.midtrans.is_production')
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
}
