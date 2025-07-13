<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_level extends Model
{

    protected $table = 'user_level';
    public $guarded = [];
    public $incrementing = false;
    public $timestime = false;
    public static $tahun;
    use HasFactory;

}
