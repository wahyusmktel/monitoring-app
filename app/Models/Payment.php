<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'payments';

    protected $fillable = [
        'bill_id',
        'tanggal_bayar',
        'jumlah_bayar',
        'metode_pembayaran',
        'keterangan',
    ];

    /**
     * Relasi: Payment milik satu Bill.
     */
    public function bill()
    {
        return $this->belongsTo(Bill::class, 'bill_id');
    }
}
