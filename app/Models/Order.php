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
        'citation_style',
        'sources_required',
        'services',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'deadline' => 'datetime',
        'services' => 'array',
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

    protected static function booted()
    {
        static::updated(function ($order) {
            if ($order->isDirty('status') && $order->status === 'completed' && !$order->writerPayment) {
                // Calculate writer's payment (40% of order price)
                $writerAmount = $order->price * 0.4;

                // Create the writer payment
                WriterPayment::create([
                    'writer_id' => $order->writer_id,
                    'order_id' => $order->id,
                    'amount' => $writerAmount,
                    'status' => 'pending',
                    'payment_date' => null,
                ]);
                
                // Update writer statistics
                if ($order->writer_id) {
                    $writer = $order->writer;
                    if ($writer && $writer->writerProfile) {
                        // Count completed orders for this writer
                        $completedOrders = Order::where('writer_id', $order->writer_id)
                                             ->where('status', 'completed')
                                             ->count();
                        
                        // Update writer profile with the new completed orders count
                        $writer->writerProfile->update([
                            'completed_orders' => $completedOrders,
                        ]);
                    }
                }
            }
        });
    }
}