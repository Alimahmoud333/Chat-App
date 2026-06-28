<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{
    protected $fillable = [

        'group_id',
        'user_id',
        'is_admin',
    ];

    /*
    =========================================
    GROUP
    =========================================
    */

    public function group()
    {
        return $this->belongsTo(
            ChatGroup::class,
            'group_id'
        );
    }

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