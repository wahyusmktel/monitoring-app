<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Otb extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'otbs';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'nama_otb',
        'olt_port_id',
        'losses',
        'lokasi',
        'latitude',
        'longitude',
    ];

    /**
     * Auto-generate UUID saat create.
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
     * Relasi: Otb milik satu OltPort.
     */
    public function oltPort()
    {
        return $this->belongsTo(OltPort::class, 'olt_port_id');
    }

    /**
     * Relasi: Otb memiliki banyak ODC.
     */
    public function odcs()
    {
        return $this->hasMany(Odc::class, 'otb_id');
    }
}
