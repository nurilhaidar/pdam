<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelPegawai extends Model
{
    protected $table = 'tmpegawai';
    protected $primarykey = "np";

    public $timestamps = false;
}
