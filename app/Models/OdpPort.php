<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class OdpPort extends Model
{
    use HasUuids;

    protected $table = 'odp_ports';

    protected $fillable = [
        'odp_id',
        'port_number',
        'pelanggan_id',
        'status',
        'keterangan',
    ];

    /**
     * Relasi ke ODP
     */
    public function odp()
    {
        return $this->belongsTo(Odp::class);
    }

    /**
     * Relasi ke Pelanggan (jika port ini dipakai oleh pelanggan)
     */
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }
}
