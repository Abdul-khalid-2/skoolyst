<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Rejects links and foreign-script lookalike characters in plain text fields
 * (names etc). Urdu/Arabic and English text is allowed.
 */
class NoSpamContent implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || $value === '') {
            return;
        }

        // 1) Explicit links
        if (preg_match('#(https?:|ftp:|www\.|//)#i', $value)) {
            $fail('The :attribute must not contain links.');
            return;
        }

        // 2) Bare domains like "myxa.cc" (only well-known spam TLDs, so
        //    names like "M.Ali" or "St. Mary" are not affected)
        $tlds = implode('|', array_map('preg_quote', config('spam.link_tlds', [])));
        if ($tlds !== '' && preg_match('/[a-z0-9-]+\.(?:' . $tlds . ')\b/i', $value)) {
            $fail('The :attribute must not contain links.');
            return;
        }

        // 3) Cyrillic, Chinese/Japanese/Korean scripts (common in spam tools)
        if (preg_match('/[\x{0400}-\x{052F}\x{3040}-\x{30FF}\x{3400}-\x{9FFF}\x{AC00}-\x{D7AF}]/u', $value)) {
            $fail('The :attribute may only contain English or Urdu characters.');
            return;
        }

        // 4) Email addresses or long hex tokens pasted into a name
        if (preg_match('/[^\s@]+@[^\s@]+\.[^\s@]+/', $value) || preg_match('/\b[a-f0-9]{24,}\b/i', $value)) {
            $fail('The :attribute is not valid.');
        }
    }
}
