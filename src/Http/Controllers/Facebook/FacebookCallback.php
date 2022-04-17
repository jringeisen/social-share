<?php

namespace Jringeisen\SocialShare\Http\Controllers\Facebook;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use Jringeisen\SocialShare\Models\SocialPage;

class FacebookCallback
{
    /**
     * Handle the incoming request.
     *
     */
    public function __invoke(Request $request)
    {
        if ($request->error) {
            return redirect()->route('dashboard');
        }

        $facebook_user = Socialite::driver('facebook')->user();

        $response = Http::get("https://graph.facebook.com/v13.0/me/accounts?access_token=$facebook_user->token")->json();

        $pages = collect($response['data']);

        $this->deleteSocialPages($pages);

        $pages->each(function ($page) {
            request()->user()->socialPages()->updateOrCreate([
                'page_id' => $page['id'],
            ], [
                'name' => $page['name'],
                'access_token' => $page['access_token'],
                'social_platform' => 'facebook',
            ]);
        });

        return redirect()->route(config('social-share.callback_redirect'));
    }

    /**
     * Removes social pages that had permissions revoked.
     *
     * @param Collection $pages
     * @return void
     */
    protected function deleteSocialPages($pages)
    {
        // Get collection of pages that were not granted permissions.
        $social_pages = SocialPage::where('social_platform', 'facebook')->whereNotIn('page_id', $pages->pluck('id'))->get();

        // Loop through the collection and delete them.
        $social_pages->each(function ($page) {
            $page->delete();
        });
    }
}
