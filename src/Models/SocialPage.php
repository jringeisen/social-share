<?php

namespace Jringeisen\SocialShare\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SocialPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'name',
        'category',
        'access_token',
        'access_token_expires_at',
        'access_token_secret',
        'refresh_token',
        'social_platform'
    ];

    protected $casts = [
        'access_token_expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
