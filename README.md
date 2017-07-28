# WPREST
laravel package for easy use wordpress rest api, only provide post, category create and update at this version.

# Prepare

## WP REST API V2

All REST API provided by WP REST API V2, if your wordpress version more than 4.7, you can skip this, because it's included in wordpress 4.7, if not, you should install it [WP REST API V2 plugin](https://wordpress.org/plugins/rest-api/)

## JWT Authentication for WP-API

Wordpress don't provide a easy way to authenticate api, so i decide use JWT plugin to authenticate.

Here is [link](https://wordpress.org/plugins/jwt-authentication-for-wp-rest-api/)

### Usage

when you installed, you need to [configure](https://wordpress.org/plugins/jwt-authentication-for-wp-rest-api/#description) and enable it.

# Install

`$ composer require tradzero/wp_rest_laravel:dev-master -vvv`

Edit `/pathto/config/app.php`

```php
'providers' => [
    // Other service providers...
    Tradzero\WPREST\WPRESTServiceProvider::class,
],
```

You can also add alias in

```php
'aliases' => [
    'WPREST' => Tradzero\WPREST\Facade::class,
]
```

Publish config file

```bash
$ php artisan vendor:publish --provider=Tradzero\WPREST\WPRESTServiceProvider
```

Configure `wordpress.php`  
enter your wordpress endpoint and account infomation.

# Usage

```php
use Tradzero\WPREST\Resources\Post;
use Tradzero\WPREST\Resources\Category;
use WPREST;

$post = new Post();
$post->setTitle('hello world');
$post->setContent('Its post created using WP REST API');

WPREST::createPost($post);

$post->setId('XX');
WPREST::updatePost($post);

$category = new Category;
$category->setName('category 1');
WPREST::findCategoryOrCreate($category);
// or
WPREST::createCategory($category);
$post->setCategories(array($category));
WPREST::createPost($post);
```
