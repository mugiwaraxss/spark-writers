<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WriterApplication extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'education_level',
        'experience',
        'specialization_areas',
        'resume_path',
        'cover_letter',
        'status',
        'processed_at',
        'processed_by',
        'rejection_reason',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'specialization_areas' => 'array',
        'processed_at' => 'datetime',
    ];

    /**
     * Get the admin who processed this application.
     */
    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Get the user created from this application (if approved).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
} 