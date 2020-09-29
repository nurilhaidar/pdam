<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelWork extends Model
{
    protected $table = "profile_workorder";
    protected $primaryKey = "id";
    public $timestamps = false;
    protected $fillable = [
        "id",
        'np_penerima',
        'np_pengirim',
        'tanggal',
        'deskripsi',
        'foto',
        'tanggal_dikerjakan',
        'keterangan',
        'nama_penerima',
        'nama_pengirim',
    ];

}
