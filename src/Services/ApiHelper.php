<?php

namespace App\Services;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiHelper
{
    private HttpClientInterface $client;
    private $email_admin;
    private $mdp;
    private $urlApiDialotel;


    /**
     * Get Parameters depuis services.yaml
     *
     * @param HttpClientInterface $client
     * @param $email_admin
     * @param $mdp
     */
    public function __construct(HttpClientInterface $client, $email_admin, $mdp, $urlApiDialotel)
    {
        $this->client = $client;
        $this->email_admin = $email_admin;
        $this->mdp = $mdp;
        $this->urlApiDialotel = $urlApiDialotel;
    }

    public function autorisationPaiement(): array
    {
        dump($this->email_admin);
        dump($this->mdp);
        dump($this->urlApiDialotel);

        $response = $this->client->request(
            'POST',
            $this->urlApiDialotel . "/login",
            [
                'json' => [
                    'username' => $this->email_admin,
                    'password' => $this->mdp
                ]
            ]
        );
        $token = $response->toArray()['token'];
        dd($token);
        return [];
//        return $response->toArray();
    }

    public function debitPaiement(): void
    {
        // To Do Appel API
    }
}