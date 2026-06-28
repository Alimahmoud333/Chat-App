<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatSetting extends Model
{
    protected $fillable = [

        'user_id',

        'target_user_id',

        'is_pinned',

        'is_archived',

        'is_muted',

        'is_blocked',
    ];

    /*
    =========================================
    OWNER
    =========================================
    */

    public function user()
    {
        return $this->belongsTo(
            User::class
        );
    }

    /*
    =========================================
    TARGET USER
    =========================================
    */

    public function target()
    {
        return $this->belongsTo(
            User::class,
            'target_user_id'
        );
    }
}