<?php

namespace Jringeisen\SocialShare\Platforms;

use Illuminate\Support\Facades\Http;
use Jringeisen\SocialShare\Models\SocialPage;

class Facebook {

    /**
     * Social Page property
     *
     * @var SocialPage
     */
    public SocialPage $social_page;

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
     * Posts a link with a message to facebook.
     *
     * @param string $url
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function postLink(string $link, string $message)
    {
        $response = Http::post($this->baseUrl() . "/feed?" . $this->buildHttpQuery(compact('link', 'message')));

        return response()->json($response->json(), $response->status());
    }

    /**
     * Posts a photo with a message to facebook.
     *
     * @param string $url
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function postPhoto(string $url, string $message)
    {
        $response = Http::post($this->baseUrl() . "/photos?" . $this->buildHttpQuery(compact('url', 'message')));

        return response()->json($response->json(), $response->status());
    }

    /**
     * Set's the base url with the social page's id.
     *
     * @return string
     */
    protected function baseUrl()
    {
        return 'https://graph.facebook.com/' . $this->social_page->page_id;
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
