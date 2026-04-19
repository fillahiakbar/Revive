<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidUsername implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check minimum length (3 characters)
        if (strlen($value) < 3) {
            $fail('الاسم المستخدم يجب أن يكون 3 أحرف على الأقل.');
            return;
        }

        // Check maximum length (30 characters)
        if (strlen($value) > 30) {
            $fail('الاسم المستخدم يجب أن لا يتجاوز 30 حرف.');
            return;
        }

        // Check if it's only 1 character repeated (like "aaa" or "111")
        if (strlen(preg_replace('/(.)\\1+/', '', $value)) === 0) {
            $fail('الاسم المستخدم لا يمكن أن يتكون من حرف واحد متكرر.');
            return;
        }

        // Check if it contains only symbols (like ".", "-", "_", etc.)
        if (preg_match('/^[._-]+$/', $value)) {
            $fail('الاسم المستخدم لا يمكن أن يتكون من رموز فقط.');
            return;
        }

        // Check for excessive spaces
        if (preg_match('/\s{2,}/', $value)) {
            $fail('الاسم المستخدم لا يمكن أن يحتوي على مسافات متعددة.');
            return;
        }

        // Check for leading/trailing spaces
        if (trim($value) !== $value) {
            $fail('الاسم المستخدم لا يمكن أن يبدأ أو ينتهي بمسافات.');
            return;
        }

        // Check for valid characters (only letters, numbers, underscore, and dot)
        if (!preg_match('/^[a-zA-Z0-9_.]+$/', $value)) {
            $fail('الاسم المستخدم يمكن أن يحتوي فقط على أحرف وأرقام وشرطات سفلية (_) ونقاط (.).');
            return;
        }

        // Check if starts or ends with special characters
        if (preg_match('/^[._]|[._]$/', $value)) {
            $fail('الاسم المستخدم لا يمكن أن يبدأ أو ينتهي برمز (. أو _).');
            return;
        }

        // Check for consecutive special characters
        if (preg_match('/[._]{2,}/', $value)) {
            $fail('الاسم المستخدم لا يمكن أن يحتوي على رموز متتالية.');
            return;
        }
    }
}
