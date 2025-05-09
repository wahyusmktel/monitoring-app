<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Paket extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'pakets';

    protected $fillable = [
        'nama_paket',
        'kecepatan',
        'harga',
        'deskripsi',
        'status',
    ];

    /**
     * Relasi: Satu Paket memiliki banyak Subscription.
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'paket_id');
    }
}
