<?php
namespace Tradzero\WPREST;

use GuzzleHttp\Client;

class Send
{
    protected $token;

    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_url' => config('wordpress.endpoint'),
        ]);
    }

    public function getToken()
    {
        $credentials = [
            'username' => config('wordpress.account.username'),
            'password' => config('wordpress.account.password'),
        ];

        $response = $this->client->post('jwt-auth/v1/token', $credentials);
        return $response;
    }
}