<?php
namespace Tradzero\WPREST;

use GuzzleHttp\Client;
use Tradzero\WPREST\Resources\Post;
use Tradzero\WPREST\Resources\Category;

class Send
{
    protected $client;

    protected $authenticateUrl = 'jwt-auth/v1/token';
    protected $createPostUrl   = 'wp/v2/posts';
    protected $updatePostUrl   = 'wp/v2/posts/{id}';
    protected $listCategoriesUrl = 'wp/v2/categories';
    protected $createCategoriesUrl = 'wp/v2/categories';

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


    public function findCategoryOrCreate(Category $category)
    {
        $listCategoriesUrl = $this->getFullUrl($this->listCategoriesUrl);
        
        $queryFilter = [
            'search' => $category->getName(),
        ];

        $response = $this->client->get($listCategoriesUrl, ['query' => $queryFilter]);

        $result = json_decode($response->getBody());
        
        if ($result) {
            if ($result[0]->name == $category->getName()) {
                return Category::build($result[0]);
            }
        }
        return $this->createCategory($category);
    }

    public function createCategory(Category $category)
    {
        $createCategoriesUrl = $this->getFullUrl($this->createCategoriesUrl);

        $response = $this->client->post($createCategoriesUrl, ['json' => $category]);

        $result = json_decode($response->getBody());

        if ($response->getStatusCode() == 201) {
            $category->setId($result->id);
            return $category;
        } else {
            return null;
        }
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
        
        $result = json_decode($response->getBody(), true);
        
        return $result['token'];
    }

    private function getFullUrl($url)
    {
        $baseUrl = config('wordpress.endpoint');

        return $baseUrl . $url;
    }
}