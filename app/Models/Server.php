<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Server extends Model
{
    use SoftDeletes;

    protected $table = 'servers';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nama_server',
        'lokasi_server',
        'alamat_server',
        'ip_address',
        'operating_system',
        'status',
        'jenis_server',
        'keterangan',
        'id_user',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (! $model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }
}
