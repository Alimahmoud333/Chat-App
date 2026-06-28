<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [

        'sender_id',
        'receiver_id',
        'message',
        'type',
        'file',
        'latitude',
        'longitude',
        'is_seen',
        'seen_at',
        'is_delivered',
'delivered_at',
    ];

    protected $casts = [

        'is_seen' => 'boolean',

        'seen_at' => 'datetime',
        'is_delivered' => 'boolean',

        'delivered_at' => 'datetime',
    ];

    public function sender()
    {
        return $this->belongsTo(
            User::class,
            'sender_id'
        );
    }

    public function receiver()
    {
        return $this->belongsTo(
            User::class,
            'receiver_id'
        );
    }


    /*
=========================================
REPLY
=========================================
*/

public function replyMessage()
{
    return $this->belongsTo(
        Message::class,
        'reply_to'
    );
}

/*
=========================================
REACTIONS
=========================================
*/

public function reactions()
{
    return $this->hasMany(
        MessageReaction::class
    );
}


}