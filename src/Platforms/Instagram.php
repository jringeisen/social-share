<?php

namespace Jringeisen\SocialShare\Platforms;

use Illuminate\Support\Facades\Http;
use Jringeisen\SocialShare\Models\SocialPage;

class Instagram {

    /**
     * Social Page property
     *
     * @var SocialPage
     */
    public SocialPage $social_page;

    /**
     * Image url of image to post to instagram
     *
     * @var string|null
     */
    public string|null $image_url = null;

    /**
     * Caption used with image
     *
     * @var string|null
     */
    public string|null $caption = null;

    /**
     * Construct the social page that we want to post to.
     *
     * @param SocialPage $social_page
     */
    public function __construct(SocialPage $social_page)
    {
        $this->social_page = $social_page;
    }

    /**
     * Posts image to instagram.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postImage($image_url, $caption)
    {
        $this->image_url = $image_url;
        $this->caption = $caption;

        $response = Http::post($this->publishUrl());

        return response()->json($response->json(), $response->status());
    }

    /**
     * Creates a container
     *
     * @link https://developers.facebook.com/docs/instagram-api/guides/content-publishing/#single-media-posts
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createContainer()
    {
        $response = Http::post($this->containerUrl());

        return response()->json($response->json(), $response->status());
    }

    /**
     * Gets the IG user connected to a Facebook page.
     *
     * @link https://developers.facebook.com/docs/instagram-api/reference/page
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getUserId()
    {
        $response = Http::get('https://graph.facebook.com/' . $this->social_page->page_id . '?fields=instagram_business_account&access_token=' . $this->social_page->access_token);

        return response()->json($response->json(), $response->status());
    }

    /**
     * Facebook graph url with the instagram business account id.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function baseUrl()
    {
        return "https://graph.facebook.com/v13.0/" . $this->getUserId()->getOriginalContent()['instagram_business_account']['id'];
    }

    /**
     * Formats the url for creating a container.
     *
     * @return string
     */
    protected function containerUrl()
    {
        return $this->baseUrl() . "/media?" . $this->buildHttpQuery([
            'image_url' => $this->image_url,
            'caption' => $this->caption
        ]);
    }

    /**
     * Publish container url
     *
     * @link https://developers.facebook.com/docs/instagram-api/guides/content-publishing#single-media-posts
     * @return string
     */
    protected function publishUrl()
    {
        $container_id = $this->createContainer()->getOriginalContent()['id'];

        return $this->baseUrl() . "/media_publish?" . $this->buildHttpQuery([
            'creation_id' => $container_id
        ]);
    }

    /**
     * Generates a URL-encoded query string and removes keys with null values.
     *
     * @param string $url
     * @param string $message
     * @return string
     */
    protected function buildHttpQuery(array $params)
    {
        return http_build_query(
            $this->mergeAccessTokenWithValues($params, $this->social_page->access_token)
        );
    }

    /**
     * Merges access token array with array of values.
     *
     * @param array $values
     * @return array
     */
    protected function mergeAccessTokenWithValues($values, $access_token)
    {
        return array_merge(
            $values,
            compact('access_token')
        );
    }

}
