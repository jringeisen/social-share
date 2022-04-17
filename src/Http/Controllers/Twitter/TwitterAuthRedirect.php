<?php

namespace Jringeisen\SocialShare\Http\Controllers\Twitter;

use Laravel\Socialite\Facades\Socialite;

class TwitterAuthRedirect
{
    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        return Socialite::driver('twitter-oauth-2')
            ->scopes(['tweet.read', 'tweet.write', 'users.read', 'offline.access'])
            ->redirect();
    }
}
