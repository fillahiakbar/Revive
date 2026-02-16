<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Laravel\Jetstream\HasProfilePhoto;
use Illuminate\Support\Facades\URL;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasProfilePhoto;
    use Notifiable;
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
    ];

    public function comments()
{
    return $this->hasMany(Comment::class);
}

public function sendEmailVerificationNotification()
{
    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $this->getKey(), 'hash' => sha1($this->getEmailForVerification())]
    );

    (new \App\Services\BrevoMailer)->sendEmail(
        $this->email,
        $this->name,
        'تأكيد عنوان بريدك الإلكتروني',
        '
        <div dir="rtl" style="text-align: right; font-family: Tahoma, sans-serif;">
            <p>يُرجى النقر على الزر أدناه لتأكيد عنوان بريدك الإلكتروني.</p>

            <p>
                <a href="' . $verificationUrl . '" 
                   style="display:inline-block;padding:10px 20px;background-color:#1d4ed8;color:#fff;text-decoration:none;border-radius:5px;">
                   تأكيد عنوان البريد الإلكتروني
                </a>
            </p>

            <p>إن لم تُنشِئ حساباً، فلا يلزم اتخاذ أي إجراءٍ آخر.</p>

            <p>مع تحياتنا،<br><strong>REVIVE</strong></p>
        </div>
        '
    );
}



public function sendPasswordResetNotification($token)
{
    $resetUrl = url(route('password.reset', [
        'token' => $token,
        'email' => $this->email,
    ], false));

    (new \App\Services\BrevoMailer)->sendEmail(
        $this->email,
        $this->name ?? 'المستخدم',
        'طلب إعادة تعيين كلمة المرور',
        '
        <div dir="rtl" style="text-align: right; font-family: Tahoma, sans-serif; background-color: #f9fafb; padding: 30px;">
            <div style="background-color: #ffffff; border-radius: 8px; padding: 20px; border: 1px solid #ddd;">
                <h2 style="color: #1d4ed8;">طلب إعادة تعيين كلمة المرور</h2>

                <p>تلقينا طلباً لإعادة تعيين كلمة المرور الخاصة بك.</p>

                <p style="margin: 25px 0;">
                    <a href="' . $resetUrl . '" 
                       style="display: inline-block; background-color: #1d4ed8; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none;">
                        إعادة تعيين كلمة المرور
                    </a>
                </p>

                <p>تجاهل هذه الرسالة إن لم تكن أنت من طلب إعادة التعيين.</p>

                <p style="margin-top: 40px;">مع تحياتنا،<br><strong>REVIVE</strong></p>
            </div>
        </div>
        '
    );
}
}