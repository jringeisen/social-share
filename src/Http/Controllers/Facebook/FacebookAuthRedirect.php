<?php

namespace Jringeisen\SocialShare\Http\Controllers\Facebook;

use Laravel\Socialite\Facades\Socialite;

class FacebookAuthRedirect
{
    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        return Socialite::driver('facebook')
            ->scopes([
                'pages_manage_posts',
                'pages_read_engagement',
                'instagram_basic',
                'instagram_content_publish',
                'business_management',
                'pages_show_list',
            ])
            ->redirect();
    }
}
