<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_image',
        'bio',
        'phone',
        'role',
        'is_online',
        'last_seen',
        'is_banned',
        'fcm_token',

        'otp_code',
        'otp_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp_code',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_online' => 'boolean',
            'is_banned' => 'boolean',
            'last_seen' => 'datetime',
            'otp_expires_at' => 'datetime',
        ];
    }



    /*
    =========================================
    PRIVATE MESSAGES
    =========================================
    */

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    /*
    =========================================
    GROUPS
    =========================================
    */

    public function groups()
    {
        return $this->belongsToMany(
            ChatGroup::class,
            'group_members',
            'user_id',
            'group_id'
        )
        ->withPivot('is_admin')
        ->withTimestamps();
    }

    /*
    =========================================
    CHAT SETTINGS
    =========================================
    */

    public function chatSettings()
    {
        return $this->hasMany(ChatSetting::class);
    }

    /*
    =========================================
    HELPER (OPTIONAL)
    =========================================
    */

    public function isBanned(): bool
    {
        return (bool) $this->is_banned;
    }
}