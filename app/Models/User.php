<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationships
    public function writerProfile()
    {
        return $this->hasOne(WriterProfile::class);
    }

    public function clientProfile()
    {
        return $this->hasOne(ClientProfile::class);
    }

    public function writtenOrders()
    {
        return $this->hasMany(Order::class, 'writer_id');
    }

    public function clientOrders()
    {
        return $this->hasMany(Order::class, 'client_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'writer_id');
    }

    public function writerPayments()
    {
        return $this->hasMany(WriterPayment::class, 'writer_id');
    }

    // Helper methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isWriter()
    {
        return $this->role === 'writer';
    }

    public function isClient()
    {
        return $this->role === 'client';
    }
    
    /**
     * Get the user's initials from their name.
     *
     * @return string
     */
    public function initials()
    {
        $nameParts = explode(' ', trim($this->name));
        $initials = '';
        
        if (count($nameParts) >= 2) {
            // Get first letter of first and last name
            $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[count($nameParts) - 1], 0, 1));
        } elseif (count($nameParts) == 1) {
            // If only one name, use the first two letters or just first letter if name is one character
            $name = $nameParts[0];
            $initials = strtoupper(substr($name, 0, min(2, strlen($name))));
        }
        
        return $initials ?: 'U'; // Return 'U' as fallback for User
    }
}