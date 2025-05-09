<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bill extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'bills';

    protected $fillable = [
        'subscription_id',
        'periode',
        'jumlah_tagihan',
        'status_pembayaran',
        'tanggal_jatuh_tempo',
    ];

    /**
     * Relasi: Bill milik satu Subscription.
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }

    /**
     * Relasi: Bill memiliki satu Payment.
     */
    public function payment()
    {
        return $this->hasOne(Payment::class, 'bill_id');
    }
}
