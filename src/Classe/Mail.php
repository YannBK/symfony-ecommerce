<?php
namespace App\Classe;

use \Mailjet\Resources;
use \Mailjet\Client;

class Mail
{
    private $api_key = '587ebff5eeabbe94f4997ce41ee63b90';
    private $api_key_secret = 'c7a52d7bfddd925bb53b9d89adeff209';

    public function send($to_email, $to_name, $subject, $content) 
    {
        $mj = new Client($this->api_key, $this->api_key_secret, true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "akryjnid888@hotmail.fr",
                        'Name' => "Boutique TEST"
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