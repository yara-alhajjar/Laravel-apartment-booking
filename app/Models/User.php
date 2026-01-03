<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasFactory;

    protected $fillable = [
        'phone',
        'password',
        'role',
        'approval_status',
        'first_name',
        'last_name',
        'birth_date',
        'personal_image',
        'identity_image',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];


    
    public function apartments()
    {
        return $this->hasMany(Apartment::class, 'landlord_id');
    }

    
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'tenant_id');
    }

    
    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'tenant_id');
    }


    public function ratings()
    {
        return $this->hasMany(Rating::class, 'tenant_id');
    }

    
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }
}
