<?php
use App\Models\Setting;
use App\Settings\GeneralSettings;


if (!function_exists('getSocialIcon')) {
    function getSocialIcon($platform)
    {
        return match (strtolower($platform)) {
            'telegram' => 'telegram-plane',
            'instagram' => 'instagram',
            'x', 'twitter', 'x-twitter' => 'x-twitter',
            'discord' => 'discord',
            'facebook' => 'facebook',
            'youtube' => 'youtube',
            default => 'globe',
        };
    }
}


if (!function_exists('isPublicRegistrationEnabled')) {
    function isPublicRegistrationEnabled()
    {
        try {
            return app(\App\Settings\GeneralSettings::class)->allow_registration;
        } catch (\Exception $e) {
            return true; // Default value jika error
        }
    }
}
