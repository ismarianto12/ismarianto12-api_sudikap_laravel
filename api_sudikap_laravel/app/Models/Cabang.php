<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    public $timestamps = false;
    public $incrementing = false;
    protected $table = 'map_cabang';
    public $guarded = [];
    use HasFactory;

}
