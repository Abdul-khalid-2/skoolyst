<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Blocks throwaway email providers listed in config/spam.php.
 */
class NotDisposableEmail implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || ! str_contains($value, '@')) {
            return;
        }

        $domain = strtolower(trim(substr(strrchr($value, '@'), 1)));
        $blocked = array_map('strtolower', config('spam.disposable_domains', []));

        foreach ($blocked as $bad) {
            if ($domain === $bad || str_ends_with($domain, '.' . $bad)) {
                $fail('Please use a permanent email address (temporary email providers are not allowed).');
                return;
            }
        }
    }
}
