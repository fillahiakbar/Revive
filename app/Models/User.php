<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Laravel\Jetstream\HasProfilePhoto;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;

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

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function comments()
{
    return $this->hasMany(Comment::class);
}

    /**
     * Validation rules for user registration
     */
    public static function registrationRules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:30',
                new \App\Rules\ValidUsername,
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users'),
                function ($attribute, $value, $fail) {
                    // Check if email is blacklisted
                    if (\App\Models\EmailBlacklist::isBlacklisted($value)) {
                        $fail('هذا البريد الإلكتروني محظور ولا يمكن استخدامه.');
                    }
                },
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function refClicks()
    {
        return $this->hasMany(RefClick::class, 'ref_user_id');
    }

    public function refStats()
    {
        return $this->hasMany(RefStat::class);
    }

    public function currentRefStat()
    {
        $activeSeason = LeaderboardSeason::active();
        return $this->hasOne(RefStat::class)->where('season_id', $activeSeason?->id);
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