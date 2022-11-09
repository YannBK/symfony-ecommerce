<?php
namespace App\Classe;

use \Mailjet\Resources;
use \Mailjet\Client;
use Symfony\Component\Dotenv\Dotenv;

class Mail
{
    private $api_key;
    private $api_key_secret;

    public function __construct(string $api_key, string $api_key_secret)
    {
        $this->api_key = $api_key;
        $this->api_key_secret = $api_key_secret;
    }

    public function send($to_email, $to_name, $subject, $content) 
    {
        $mj = new Client($this->api_key, $this->api_key_secret, true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "akryjnid888@hotmail.fr",
                        'Name' => "MossHeaven"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 4227367,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content,
                    ]
                ]
            ]
        ];

        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }
}


?>