<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $datetime = false;
    public $incrementing = false;
    protected $primaryKey = 'id_slide';
    protected $table = 'slider';
    public $guarded = [];
}
