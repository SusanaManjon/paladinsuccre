<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'order_number', 'status', 'total',
        'notes', 'shipping_address', 'phone',
        'payment_method', 'payment_status', 'transaction_id', 'payment_proof'
    ];

    protected $casts = [
        'total' => 'decimal:2',
    ];

    protected $appends = ['payment_proof_url', 'status_label'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'PLD-' . strtoupper(uniqid());
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'    => 'Pendiente',
            'confirmed'  => 'Confirmado',
            'processing' => 'En Proceso',
            'delivered'  => 'Entregado',
            'cancelled'  => 'Cancelado',
            default      => $this->status,
        };
    }

    public function getPaymentProofUrlAttribute()
    {
        if (!$this->payment_proof) {
            return null;
        }

        if (\Illuminate\Support\Str::startsWith($this->payment_proof, 'http')) {
            return $this->payment_proof;
        }

        return asset('storage/' . $this->payment_proof);
    }
}
