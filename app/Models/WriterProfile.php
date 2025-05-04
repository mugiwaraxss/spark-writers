<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WriterProfile extends Model
{
    use HasFactory;

    protected $table = 'writer_profiles';

    protected $fillable = [
        'user_id',
        'education_level',
        'bio',
        'expertise_areas',
        'hourly_rate',
        'rating',
        'completed_orders',
        'availability_status'
    ];

    protected $casts = [
        'expertise_areas' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}