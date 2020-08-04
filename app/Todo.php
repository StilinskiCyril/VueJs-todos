<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Todo extends Model
{
    protected $hidden = [
        'id', 'user_id'
    ];
    protected static function boot()
    {
        parent::boot();
        self::creating(function ($todo){
            $todo->uuid = Uuid::uuid4()->toString();
        });
    }

    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }
}
