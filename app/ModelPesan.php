<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelPesan extends Model
{
    protected $table = 'profile_pesan';
    protected $primary_key = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id', 
        'pesan', 
        'np_pengirim', 
        'waktu', 
        'np_penerima', 
        'nama_pengirim', 
        'nama_penerima'
    ];
}
