<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class OltPort extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'olt_ports';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'nama_port',
        'olt_id',
        'status',
        'kapasitas',
        'losses',
    ];

    /**
     * Boot untuk auto-generate UUID saat create.
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
     * Relasi: OltPort milik satu OLT.
     */
    public function olt()
    {
        return $this->belongsTo(Olt::class, 'olt_id');
    }

    /**
     * Relasi: OltPort punya banyak OTB.
     */
    public function otbs()
    {
        return $this->hasMany(Otb::class, 'olt_port_id');
    }
}
