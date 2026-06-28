<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    protected $fillable = [

        'user_id',
        'title',
        'body',
        'is_read',
    ];

    protected $casts = [

        'is_read' => 'boolean',
    ];

    /*
    =========================================
    USER
    =========================================
    */

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }
}