<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Olt extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'olts';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'nama_olt',
        'ip_address',
        'lokasi',
        'losses',
        'latitude',
        'longitude',
    ];

    /**
     * Boot function to assign UUID automatically.
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
     * Relasi: OLT memiliki banyak port.
     */
    public function ports()
    {
        return $this->hasMany(OltPort::class, 'olt_id');
    }
}
