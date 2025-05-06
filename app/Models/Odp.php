<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Odp extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'odps';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'nama_odp',
        'odc_id',
        'kapasitas',
        'losses',
        'lokasi',
        'latitude',
        'longitude',
    ];

    /**
     * Generate UUID otomatis saat create.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * Relasi: ODP milik satu ODC.
     */
    public function odc()
    {
        return $this->belongsTo(Odc::class, 'odc_id');
    }

    /**
     * Relasi: ODP memiliki banyak Pelanggan.
     */
    public function pelanggans()
    {
        return $this->hasMany(Pelanggan::class, 'odp_id');
    }
}
