<?php

namespace Jringeisen\SocialShare\Http\Controllers\Twitter;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class TwitterCallback
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

        $twitter_user = Socialite::driver('twitter-oauth-2')->user();

        request()->user()->socialPages()->updateOrCreate([
            'page_id' => $twitter_user->id,
        ], [
            'name' => $twitter_user->name,
            'access_token' => $twitter_user->token,
            'access_token_expires_at' => now()->addSeconds($twitter_user->expiresIn),
            'refresh_token' => $twitter_user->refreshToken,
            'social_platform' => 'twitter',
        ]);

        return redirect()->route(config('social-share.callback_redirect'));
    }
}
