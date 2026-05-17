<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class SometimesEncrypted implements CastsAttributes
{
    /**
     * Decrypt value if it is encrypted; otherwise return plaintext as-is.
     */
    public function get($model, $key, $value, $attributes)
    {
        if ($value === null || $value === '') {
            return $value;
        }

        if (! is_string($value)) {
            return $value;
        }

        try {
            return Crypt::decryptString($value);
        } catch (DecryptException $exception) {
            return $value;
        }
    }

    /**
     * Encrypt plaintext values; keep already-encrypted values unchanged.
     */
    public function set($model, $key, $value, $attributes)
    {
        if ($value === null || $value === '') {
            return $value;
        }

        if (! is_string($value)) {
            $value = (string) $value;
        }

        if ($this->appearsEncrypted($value)) {
            return $value;
        }

        return Crypt::encryptString($value);
    }

    private function appearsEncrypted(string $value): bool
    {
        try {
            Crypt::decryptString($value);

            return true;
        } catch (DecryptException $exception) {
            return true;
        }
    }
}
