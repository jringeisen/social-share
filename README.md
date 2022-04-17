# Social Share
Social Share is a Laravel package that allows you to connect and post to social platforms such as Twitter, Facebook, and Instagram.

## Getting Started

Install the package with the following command
```php
composer require jringeisen/social-share
```

Social Share depends on Laravel Socialite to be able to connect your social platforms, so make sure to install it.
```php
composer require laravel/socialite
```

Then migrate the databse
```php
php artisan migrate
```

## How to use Social Share

Social share uses Laravel's Socialite package to connect to your social media platforms. We've provided the following routes for you to use.

Facebook
```php
route('facebook.oauth') // Authenticates facebook and instagram
route('facebook.callback') // Redirect to dashboard and stores pages in database
```

Twitter
```php
route('twitter.oauth') // Authenticates twitter
route('twitter.callback') // Redirect to dashboard and stores pages in database
```

Once you have connected your accounts you can post to them by initializing the class you want to post to. For example this is how we would post to our Facebook page.

```php
// Get our Facebook page that was stored in the database when we connected to it.
$page = Jringeisen\SocialShare\SocialPage::where('platform', 'facebook')->first();

// Initialize the Facebook class with our facebook page.
$facebook = new Jringeisen\SocialShare\Facebook($page);

// Post a link with text to Facebook
$facebook->postLink($link, $text);

// Post a photo wtih text to Facebook
$facebook->postPhoto($url, $text);
```

## Todo
- [ ] Create tests.
- [ ] Implement posting images to Twitter.
- [ ] Add other social platforms such as LinkedIn, Youtube, Pinterest.
- [X] Add callback redirect path to config
