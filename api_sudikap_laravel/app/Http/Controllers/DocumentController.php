<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\TemplateProcessor;

class DocumentController extends Controller
{ 
    private function getSppdData($id)
    {
        return DB::table('sppd')
            ->select([
                'sppd.id',
                'a.nip as nip_pimpinan',
                'a.jabatan as jabatan_pimpinan',
                'b.jabatan as jabatan_pengikut',
                'a.golongan as golongan_pimpinan',
                'b.golongan as golongan_pengikut',
                'b.nip as nip_pengikut',
                'sppd.pengikut_nip',
                'a.nama as pimpinan',
                'b.nama as pengikut',
                'letter_code',
                'letter_subject',
                'letter_about',
                'letter_from',
                'letter_content',
                'letter_date',
                'code',
                'date',
                'atasan',
                'bawahan',
                'rate_travel',
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
                'file_update',
                'status',
                'jenis_surat_id',
                'basic',
                'city',
                'rekening',
                'nip_pejabat',
                'nip_leader',
                'pimpinan as pimpinan_nip'
            ])
            ->leftJoin('pegawai as a', 'a.nip', '=', 'sppd.pengikut_nip')
            ->leftJoin('pegawai as b', 'b.nip', '=', 'sppd.atasan')
            ->where('sppd.id', $id)
            ->first();
    }

    /**
     * Get pengikut data
     */
    private function getPengikutData($nip)
    {
        return DB::table('pegawai')
            ->where('nip', $nip)
            ->get();
    }

    /**
     * Get additional pegawai data
     */
    private function getPegawaiData($nip)
    {
        return DB::table('pegawai')
            ->where('nip', $nip)
            ->first();
    }

    /**
     * Get jenis surat data
     */
    private function getJenisSurat($id)
    {
        return DB::table('jenis_surat')
            ->where('id_jenis', $id)
            ->first();
    }

    /**
     * Format tanggal Indonesia
     */
    private function tglIndonesia($date)
    {
        $bulan = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        
        $date = strtotime($date);
        return date('j', $date) . ' ' . $bulan[(int)date('n', $date)] . ' ' . date('Y', $date);
    }

    /**
     * Export SPPD to Word
     */
    public function exportToWord($id, $namaFile)
    {
        // Get main SPPD data
        $sppd = $this->getSppdData($id);
        
        if (!$sppd) {
            abort(404, 'Data SPPD tidak ditemukan');
        }

        // Get additional data
        $perintah = $this->getPegawaiData($sppd->nip_pejabat);
        $diperintah = $this->getPegawaiData($sppd->nip_leader);
        $jsurat = $this->getJenisSurat($sppd->jenis_surat_id);
        $pimpinan = $this->getPegawaiData($sppd->pimpinan_nip);
        
        // Process pengikut data
        $pengikut = $this->getPengikutData($sppd->pengikut_nip);
        $replacements = [];
        
        foreach ($pengikut as $index => $p) {
            $replacements[] = [
                'no' => $index + 1,
                'nama' => $p->nama,
                'golongan_pengikut' => $p->golongan,
                'nip' => $p->nip,
                'jabatan_pengikut' => $p->jabatan,
                'place_to' => $p->place_to,
                'length_journey' => $p->length_journey,
                'date_go' => $this->tglIndonesia($p->date_go),
                'date_back' => $this->tglIndonesia($p->date_back),
                'government' => $p->government,
                'description' => $p->description,
            ];
        }

        // Load Word template
        $templatePath = storage_path('app/templates/' . $namaFile . '.docx');
        $templateProcessor = new TemplateProcessor($templatePath);

        // Set logo
        $logoPath = file_exists(public_path('assets/img/logo.png')) 
            ? public_path('assets/img/logo.png') 
            : public_path('assets/img/no_image.png');
        
        $templateProcessor->setImageValue('CompanyLogo', [
            'path' => $logoPath,
            'width' => 100,
            'height' => 100,
            'ratio' => false
        ]);

        // Clone block for pengikut data
        $templateProcessor->cloneBlock('block_name', 0, true, false, $replacements);

        // Set values
        $jenissuratnya = explode('~', $jsurat->nama_jenis ?? '');

        $templateValues = [
            'nip_pimpinan' => $sppd->nip_pimpinan ?? '',
            'tgl_surat' => $this->tglIndonesia(date('Y-m-d')),
            'jenis_surat' => strtoupper($jenissuratnya[1] ?? ''),
            'letter_code' => $sppd->letter_code ?? '',
            'basic' => $sppd->basic ?? '',
            'date_go' => $this->tglIndonesia($sppd->date_go),
            'date_back' => $this->tglIndonesia($sppd->date_back),
            'nama_kota' => $sppd->city ?? '',
            'purpose' => $sppd->purpose ?? '',
            'city' => $sppd->city ?? '',
            'letter_date' => $this->tglIndonesia(date('Y-m-d')),
            'nama_pemberi_tugas' => $perintah->nama ?? '',
            'pangkat_pemberi_tugas' => $perintah->jabatan ?? '',
            'nip_pemberi_tugas' => $perintah->nip ?? '',
            'jabatan_pemberi_tugas' => $perintah->jabatan ?? '',
            'nama_yang_diperintah' => $diperintah->nama ?? '',
            'nip_pegawai_yang_diperintah' => $diperintah->nip ?? '',
            'jabatan_pegawai_yang_diperintah' => $diperintah->jabatan ?? '',
            'pangkat_gol_pegawai_yang_diperintah' => $diperintah->golongan ?? '',
            'kota_asal' => $diperintah->place_from ?? '',
            'kota_tujuan' => $diperintah->place_to ?? '',
            'transpotasi' => $sppd->transport ?? '',
            'lama_hari' => $sppd->length_journey ?? '',
            'tgl_perjalanan' => $this->tglIndonesia($sppd->date_go),
            'atas_beban' => $sppd->budget_from ?? '',
            'rekening' => $sppd->rekening ?? '',
            'nama_penandatangan_sppd' => $perintah->nama ?? '',
            'pangkat_penandatangan_sppd' => $perintah->jabatan ?? '',
            'no_nip_penandatangan_sppd' => $perintah->nip ?? '',
            'tgl_hari_ini' => $this->tglIndonesia(date('Y-m-d')),
            'nama_pimpinan' => $pimpinan->nama ?? '',
            'id' => $sppd->id,
            'namapimpinan' => $pimpinan->nama ?? '',
            'jabatanpimpinan' => $pimpinan->jabatan ?? '',
            'nippimpinan' => $sppd->pimpinan_nip ?? '',
            'letter_subject' => $sppd->letter_subject ?? '',
            'letter_about' => $sppd->letter_about ?? '',
            'letter_from' => $sppd->letter_from ?? '',
            'letter_content' => $sppd->letter_content ?? '',
            'code' => $sppd->code ?? '',
            'date' => $sppd->date ?? '',
            'bawahan' => $sppd->bawahan ?? '',
            'nip_bawahan' => $sppd->bawahan ?? '',
            'atasan' => $sppd->atasan ?? '',
            'rate_travel' => $sppd->rate_travel ?? '',
            'pengikut_nip' => $sppd->pengikut_nip ?? '',
            'place_from' => $sppd->place_from ?? '',
            'government' => $sppd->government ?? '',
            'budget' => $sppd->budget ?? '',
            'result_date' => $sppd->result_date ?? '',
            'result' => $sppd->result ?? '',
            'result_username' => $sppd->result_username ?? '',
            'file' => $sppd->file ?? '',
            'file_update' => $sppd->file_update ?? '',
            'status' => $sppd->status ?? '',
            'username' => $sppd->username ?? '',
            'username_update' => $sppd->username_update ?? '',
            'datetime_insert' => $sppd->datetime_insert ?? '',
            'datetime_update' => $sppd->datetime_update ?? '',
            'kabag' => $sppd->kabag ?? '',
            'kasubag' => $sppd->kasubag ?? '',
            'pimpinan_spt' => $sppd->pimpinan_spt ?? '',
            'kabag_spt' => $sppd->kabag_spt ?? '',
            'kasubag_spt' => $sppd->kasubag_spt ?? '',
            'letter_code_spt' => $sppd->letter_code_spt ?? '',
        ];

        foreach ($templateValues as $key => $value) {
            $templateProcessor->setValue($key, $value);
        }

        // Download file
        $filename = 'SPPD-Cetak-' . date('Ymd') . '.docx';
        header("Content-Disposition: attachment; filename=$filename");
        $templateProcessor->saveAs('php://output');
        exit;
    }
}