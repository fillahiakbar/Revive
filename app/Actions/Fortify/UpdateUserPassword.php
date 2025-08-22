<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;
use App\Services\BrevoMailer;

class UpdateUserPassword implements UpdatesUserPasswords
{
    use PasswordValidationRules;

    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'current_password' => ['required', 'string', 'current_password:web'],
            'password' => $this->passwordRules(),
        ], [
            'current_password.current_password' => __('كلمة المرور المُدخلة لا تطابق كلمة المرور الحالية.'),
        ])->validateWithBag('updatePassword');

        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();

        // ✅ Kirim notifikasi lewat Brevo
        (new BrevoMailer)->sendEmail(
            $user->email,
            $user->name,
            'تم تحديث معلومات حسابك',
            '
            <html lang="ar" dir="rtl">
            <body style="background-color:#f9fafb;padding:30px;font-family:Cairo, sans-serif;text-align:right;">
                <div style="background:#fff;border-radius:8px;padding:20px;border:1px solid #ddd;">
                    <h2 style="color:#1d4ed8;">تحديث معلومات الحساب</h2>
                    <p>مرحباً <strong>' . e($user->name) . '</strong>،</p>
                    <p>تم تعديل معلومات حسابك بنجاح.</p>
                    <p>إذا لم تقم بذلك، يرجى التواصل مع فريق الدعم عبر <a href="mailto:support@revivesubs.com">support@revivesubs.com</a></p>
                    <p style="margin-top:30px;">مع تحياتنا،<br><strong>REVIVE</strong></p>
                </div>
            </body>
            </html>
            '
        );
    }
}
