<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sppd extends Model
{
    use HasFactory;

    protected $table = 'sppd';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'pimpinan',
        'letter_code',
        'letter_subject',
        'letter_about',
        'letter_from',
        'letter_content',
        'letter_date',
        'code',
        'date',
        'bawahan',
        'atasan',
        'rate_travel',
        'pengikut_nip',
        'purpose',
        'transport',
        'place_from',
        'place_to',
        'length_journey',
        'date_go',
        'date_back',
        'government',
        'budget',
        'budget_from',
        'description',
        'result_date',
        'result',
        'result_username',
        'file',
        'jenis_surat_id',
        'file_update',
        'status',
        'username',
        'username_update',
        'datetime_insert',
        'datetime_update',
        'basic',
        'city',
        'rekening',
        'kabag',
        'kasubag',
        'pimpinan_spt',
        'kabag_spt',
        'kasubag_spt',
        'letter_code_spt',
        'nip_pejabat',
        'nip_leader'
    ];

    protected $casts = [
        'budget' => 'decimal:2',
        'length_journey' => 'integer',
        'jenis_surat_id' => 'integer',
        'result_date' => 'date',
        'status' => 'string'
    ];

    /**
     * Scope untuk filter status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk filter berdasarkan tanggal pembuatan
     */
    public function scopeBetweenDates($query, $start, $end)
    {
        return $query->whereBetween('date', [$start, $end]);
    }

    /**
     * Accessor untuk format budget
     */
    public function getFormattedBudgetAttribute()
    {
        return number_format($this->budget, 2, ',', '.');
    }

    /**
     * Mutator untuk menyimpan tanggal pergi
     */
    public function setDateGoAttribute($value)
    {
        $this->attributes['date_go'] = date('Y-m-d', strtotime($value));
    }

    /**
     * Mutator untuk menyimpan tanggal kembali
     */
    public function setDateBackAttribute($value)
    {
        $this->attributes['date_back'] = date('Y-m-d', strtotime($value));
    }

    /**
     * Relasi ke model JenisSurat (jika ada)
     */
    // public function jenisSurat()
    // {
    //     return $this->belongsTo(JenisSurat::class, 'jenis_surat_id');
    // }
}