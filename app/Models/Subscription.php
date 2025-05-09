<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscription extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'subscriptions';

    protected $fillable = [
        'pelanggan_id',
        'paket_id',
        'tanggal_mulai',
        'tanggal_berakhir',
        'status',
    ];

    /**
     * Relasi: Subscription milik satu Pelanggan.
     */
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    /**
     * Relasi: Subscription memiliki satu Paket.
     */
    public function paket()
    {
        return $this->belongsTo(Paket::class, 'paket_id');
    }

    /**
     * Relasi: Subscription memiliki banyak Bill.
     */
    public function bills()
    {
        return $this->hasMany(Bill::class, 'subscription_id');
    }
}
