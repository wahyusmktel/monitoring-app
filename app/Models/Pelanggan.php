<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Pelanggan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pelanggans';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'nama_pelanggan',
        'odp_id',
        'alamat',
        'no_hp',
        'latitude',
        'longitude',
        'status',
        'keterangan',
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
     * Relasi: Pelanggan milik satu ODP.
     */
    public function odp()
    {
        return $this->belongsTo(Odp::class, 'odp_id');
    }
}
