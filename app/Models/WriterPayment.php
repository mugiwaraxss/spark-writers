<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WriterPayment extends Model
{
    use HasFactory;

    protected $table = 'writer_payments';

    protected $fillable = [
        'writer_id',
        'order_id',
        'amount',
        'status',
        'payment_date',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
    ];

    public function writer()
    {
        return $this->belongsTo(User::class, 'writer_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}