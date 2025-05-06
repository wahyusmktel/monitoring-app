<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Odc extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'odcs';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'nama_odc',
        'otb_id',
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
     * Relasi: ODC milik satu OTB.
     */
    public function otb()
    {
        return $this->belongsTo(Otb::class, 'otb_id');
    }

    /**
     * Relasi: ODC memiliki banyak ODP.
     */
    public function odps()
    {
        return $this->hasMany(Odp::class, 'odc_id');
    }
}
