<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'recipient_id',
        'order_id',
        'content',
        'read_status',
        'sender_type',
        'recipient_type',
        'attachment_path',
        'attachment_name',
        'has_revision_request',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}