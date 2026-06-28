<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatGroup extends Model
{
    protected $fillable = [

        'name',
        'image',
        'admin_id',
    ];

    public function admin()
    {
        return $this->belongsTo(
            User::class,
            'admin_id'
        );
    }

    public function members()
    {
        return $this->belongsToMany(
            User::class,
            'group_members',
            'group_id',
            'user_id'
        )
        ->withPivot('is_admin')
        ->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(
            GroupMessage::class,
            'group_id'
        );
    }
}