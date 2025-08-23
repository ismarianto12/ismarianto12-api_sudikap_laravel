<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawai'; // pastikan sesuai dengan nama tabel

    protected $fillable = [];
    public $timestamps = false; // nonaktifkan created_at & updated_at

}
