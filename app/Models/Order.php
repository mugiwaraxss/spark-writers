<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'writer_id',
        'title',
        'description',
        'academic_level',
        'subject_area',
        'word_count',
        'deadline',
        'status',
        'price',
        'submission_note',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'deadline' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function writer()
    {
        return $this->belongsTo(User::class, 'writer_id');
    }

    public function files()
    {
        return $this->hasMany(OrderFile::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function writerPayment()
    {
        return $this->hasOne(WriterPayment::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }
}