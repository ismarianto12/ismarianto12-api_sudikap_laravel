<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class album extends Model
{
    use HasFactory;
    public $primaryKey = 'id_album';
    public $timestamps = false;
    public $incrementing = false;
    protected $table = 'album';
    public $guarded = [];
    public static $tahun;

}
