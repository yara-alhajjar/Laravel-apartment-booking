<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{
    use HasFactory;

    protected $fillable = [
        'landlord_id',
        'title',
        'description',
        'governorate',
        'city',
        'price_per_night',
        'features',
        'status',
    ];

    protected $casts = [
        'features' => 'array',
    ];


    public function landlord()
    {
        return $this->belongsTo(User::class, 'landlord_id');
    }

    
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
