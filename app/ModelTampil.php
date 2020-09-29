<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelTampil extends Model
{
    protected $table = 'tmpegawai';
    protected $primaryKey = 'np';

    protected $fillable = [
        'np'
    ];
}
