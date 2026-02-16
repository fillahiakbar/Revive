<?php

namespace App\Services;

use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Model\SendSmtpEmail;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;

class BrevoMailer
{
    protected $apiInstance;

    public function __construct()
{
    $apiKey = Config::get('services.brevo.api_key'); // Ambil dari config/services.php
    $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', $apiKey);
    $this->apiInstance = new TransactionalEmailsApi(new Client(), $config);
}

    public function sendEmail(string $toEmail, string $toName, string $subject, string $htmlContent)
{
    $email = new SendSmtpEmail([
        'sender' => [
            'name'  => config('app.name'),
            'email' => config('mail.from.address'), // GUNAKAN config() BUKAN env()
        ],
        'to' => [[
            'email' => $toEmail,
            'name'  => $toName,
        ]],
        'subject'     => $subject,
        'htmlContent' => $htmlContent,
    ]);

    return $this->apiInstance->sendTransacEmail($email);
}
}
