<?php

namespace App\Http\Controllers;

use App\Models\pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PegawaiController extends Controller
{

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function index()
    {
        $id_satker = $this->request->get('satker_id');
        $page = $this->request->page ? $this->request->page : 10;
        $query = DB::table('pegawai')
            ->select(
                'pegawai.id',
                'pegawai.sikd_satker_id',
                'pegawai.nip',
                'pegawai.nama',
                'pegawai.no_hp',
                'pegawai.alamat',
                'pegawai.tanggal_lahir',
                'pegawai.tempat_lahir',
                'pegawai.golongan',
                'pegawai.golongan_tanggal',
                'pegawai.jabatan',
                'pegawai.jabatan_tanggal',
                'pegawai.kerja_tahun',
                'pegawai.kerja_bulan',
                'pegawai.latihan_jabatan',
                'pegawai.latihan_jabatan_tanggal',
                'pegawai.latihan_jabatan_jam',
                'pegawai.pendidikan',
                'pegawai.pendidikan_lulus',
                'pegawai.pendidikan_ijazah',
                'pegawai.catatan_mutasi',
                'pegawai.keterangan',
                'pegawai.username',
                'pegawai.username_update',
                'pegawai.datetime_insert',
                'pegawai.datetime_update',
                'pegawai.status_deleted',
                'pegawai.pangkat',
                'sikd_satker.kode',
                DB::raw('IFNULL(sikd_satker.nama, "Belum diset") as namasatker')
            )
            ->leftJoin('sikd_satker', 'pegawai.sikd_satker_id', '=', 'sikd_satker.id');

        if ($id_satker) {
            $query->where('pegawai.sikd_satker_id', $id_satker);
        }
        $query->addSelect(DB::raw("CONCAT('" . url('pegawai/edit/') . "', pegawai.id) as edit_url"));
        $query->addSelect(DB::raw("CONCAT('<button type=\"button\" class=\"btn btn-danger btn-xs delete\" onclick=\"return hapus(', pegawai.id, ')\"><i class=\"fa fa-trash\"></i></button>') as delete_button"));
        $dataPegawai = $query->paginate(15);
        return $dataPegawai;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\pegawai  $pegawai
     * @return \Illuminate\Http\Response
     */
    public function show(pegawai $pegawai)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\pegawai  $pegawai
     * @return \Illuminate\Http\Response
     */
    public function edit(pegawai $pegawai)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\pegawai  $pegawai
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, pegawai $pegawai)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\pegawai  $pegawai
     * @return \Illuminate\Http\Response
     */
    public function destroy(pegawai $pegawai)
    {
        //
    }
}
