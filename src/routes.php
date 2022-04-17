<?php

namespace Jringeisen\SocialShare;

use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function () {

    Route::middleware(['auth'])->group(function () {
        Route::get('/facebook-callback', \Jringeisen\SocialShare\Http\Controllers\Facebook\FacebookCallback::class)->name('facebook.callback');
        Route::get('/twitter-callback', \Jringeisen\SocialShare\Http\Controllers\Twitter\TwitterCallback::class)->name('twitter.callback');
    });

    Route::get('/facebook-auth-redirect', \Jringeisen\SocialShare\Http\Controllers\Facebook\FacebookAuthRedirect::class)->name('facebook.oauth');
    Route::get('/twitter-auth-redirect', \Jringeisen\SocialShare\Http\Controllers\Twitter\TwitterAuthRedirect::class)->name('twitter.oauth');
});
