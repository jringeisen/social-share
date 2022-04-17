<?php

namespace Jringeisen\SocialShare\Platforms;

use Illuminate\Support\Facades\Http;
use Jringeisen\SocialShare\Models\SocialPage;

class Twitter
{

    /**
     * Social page property.
     *
     * @var SocialPage
     */
    public SocialPage $social_page;

    /**
     * Twitter api base url.
     *
     * @var string
     */
    public string $baseUrl = 'https://api.twitter.com';

    /**
     * Twitter client id property.
     *
     * @var string
     */
    public string $client_id;

    /**
     * Twitter client secret property.
     *
     * @var string
     */
    public string $client_secret;

    /**
     * Constructs the social page to post to.
     *
     * @param SocialPage $social_page
     */
    public function __construct(SocialPage $social_page)
    {
        $this->social_page = $social_page;
        $this->client_id = config('services.twitter.client_id');
        $this->client_secret = config('services.twitter.client_secret');
    }

    /**
     * Posts text to twitter.
     *
     * @param string $text
     * @return \Illuminate\Http\JsonResponse
     */
    public function postText(string $text)
    {
        if ($this->accessTokenIsExpired()) {
            $response = $this->refreshToken()->getOriginalContent();

            $this->social_page->update([
                'access_token' => $response['access_token'],
                'access_token_expires_at' => now()->addSeconds($response['expires_in'])
            ]);
        }

        $response = Http::withToken($this->social_page->access_token)
            ->post($this->baseUrl . '/2/tweets', compact('text'));

        return response()->json($response->json(), $response->status());
    }

    /**
     * Refreshed the twitter access token with the refresh token.
     *
     * @link https://developer.twitter.com/en/docs/authentication/oauth-2-0/user-access-token
     * @param SocialPage $social_page
     * @return \Illuminate\Http\JsonResponse
     */
    protected function refreshToken()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $this->encodedString()
        ])
            ->asForm()
            ->post($this->baseUrl . '/2/oauth2/token', [
                'refresh_token' => $this->social_page->refresh_token,
                'grant_type' => 'refresh_token',
                'client_id' => $this->client_id
            ]);

        return response()->json($response->json(), $response->status());
    }

    /**
     * Checks to see if the access token is expired.
     *
     * @link https://developer.twitter.com/en/docs/authentication/oauth-2-0/user-access-token
     * @param SocialPage $social_page
     * @return boolean
     */
    protected function accessTokenIsExpired()
    {
        return $this->social_page->access_token_expires_at
            ->timezone('America/New_York')
            ->lessThanOrEqualTo(now()->timezone('America/New_York'));
    }

    /**
     * Base 64 encode string for Twitter basic authentication.
     *
     * @link https://developer.twitter.com/en/docs/authentication/oauth-2-0/user-access-token
     * @return void
     */
    protected function encodedString()
    {
        return base64_encode($this->client_id . ':' . $this->client_secret);
    }
}
