<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory;
    use SoftDeletes;

    protected $hidden = [
        'password'
    ];

    protected $appends = [
        'user_type'
    ];


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function scopeUsertype($query, $user_type_name)
    {
        $user_type = UserType::where('name', $user_type_name)->first();
        return $query->where('user_type_id', ($user_type->id ?? 0));
    }

    public function getUserTypeAttribute()
    {
        return $this->type->name;
    }

    public function type()
    {
        return $this->belongsTo(UserType::class, 'user_type_id', 'id');
    }

    public function ownMessages()
    {
        return $this->hasMany(Messages::class, 'created_by', 'id');
    }
}
