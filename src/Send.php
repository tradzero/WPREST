<?php
namespace Tradzero\WPREST;

use GuzzleHttp\Client;
use Tradzero\WPREST\Resources\Post;

class Send
{
    protected $client;

    protected $authenticateUrl = 'jwt-auth/v1/token';
    protected $createPostUrl   = 'wp/v2/posts';
    protected $updatePostUrl   = 'wp/v2/posts/{id}';

    public function __construct()
    {
        $token = $this->getToken();

        $this->client = new Client(['headers' => [
            'Authorization' => "Bearer $token"
        ]]);
    }

    public function createPost(Post $post)
    {
        $createPostUrl = $this->getFullUrl($this->createPostUrl);
        
        $response = $this->client->post($createPostUrl, ['json' => $post]);
    }

    public function updatePost(Post $post)
    {
        $postId = $post->getId();

        $baseUrl = $this->getFullUrl($this->updatePostUrl);

        $updatePostUrl = str_replace('{id}', $postId, $baseUrl);

        $response = $this->client->post($updatePostUrl, ['json' => $post]);
    }

    protected function getToken()
    {
        // because of guzzle 6 change client to immutable
        $templateClient = new Client();

        $credentials = [
            'username' => config('wordpress.account.username'),
            'password' => config('wordpress.account.password'),
        ];

        $authenticateUrl = $this->getFullUrl($this->authenticateUrl);

        $response = $templateClient->post($authenticateUrl, ['form_params' => $credentials]);
        
        $result = \json_decode($response->getBody(), true);
        
        return $result['token'];
    }

    private function getFullUrl($url)
    {
        $baseUrl = config('wordpress.endpoint');

        return $baseUrl . $url;
    }
}