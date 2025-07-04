<?php

namespace App\Services;

use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Model\SendSmtpEmail;
use GuzzleHttp\Client;

class BrevoMailer
{
    protected $apiInstance;

    public function __construct()
    {
        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', env('BREVO_API_KEY'));
        $this->apiInstance = new TransactionalEmailsApi(new Client(), $config);
    }

    public function sendEmail(string $toEmail, string $toName, string $subject, string $htmlContent)
    {
        $email = new SendSmtpEmail([
            'sender' => [
                'name' => config('app.name'),
                'email' => env('MAIL_FROM_ADDRESS'),
            ],
            'to' => [[
                'email' => $toEmail,
                'name' => $toName,
            ]],
            'subject' => $subject,
            'htmlContent' => $htmlContent,
        ]);

        return $this->apiInstance->sendTransacEmail($email);
    }
}
