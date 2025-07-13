<?php

namespace App\Http\Controllers;

use App\Models\disposisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DisposisiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $query = DB::table('tbl_disposisi')
            ->select(
                'tbl_disposisi.id_disposisi',
                'tbl_disposisi.tujuan',
                'tbl_disposisi.isi_disposisi',
                'tbl_disposisi.sifat',
                'tbl_disposisi.batas_waktu',
                'tbl_disposisi.catatan',
                'tbl_disposisi.id_surat',
                'tbl_disposisi.id_user',
                'tbl_surat_masuk.no_agenda',
                'tbl_surat_masuk.no_surat',
                'tbl_surat_masuk.asal_surat',
                'tbl_surat_masuk.kode',
                'tbl_surat_masuk.indeks',
                'tbl_surat_masuk.tgl_surat',
                'tbl_surat_masuk.tgl_diterima',
                'tbl_surat_masuk.file'
            )
            ->join('tbl_surat_masuk', 'tbl_disposisi.id_surat', '=', 'tbl_surat_masuk.id_surat');

        $query->selectRaw("CONCAT('" . url('tdisposisi/detail/') . "', tbl_disposisi.id_disposisi, '><i class=\"fa fa-book\"></i>Read', '" . url('tdisposisi/edit/') . "', tbl_disposisi.id_disposisi, '><i class=\"fa fa-edit\"></i> Update', '<a href=\"#\" class=\"btn btn-danger btn-xs delete\" onclick=\"return hapus(', tbl_disposisi.id_disposisi, ')\"><i class=\"fa fa-trash\"></i> Delete</a>') as action");
        $disposisi = $query->paginate(10);
        return $disposisi;
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
     * @param  \App\Models\disposisi  $disposisi
     * @return \Illuminate\Http\Response
     */
    public function show(disposisi $disposisi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\disposisi  $disposisi
     * @return \Illuminate\Http\Response
     */
    public function edit(disposisi $disposisi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\disposisi  $disposisi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, disposisi $disposisi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\disposisi  $disposisi
     * @return \Illuminate\Http\Response
     */
    public function destroy(disposisi $disposisi)
    {
        //
    }

    public function getcurrentdisposisi()
    {
        $search = $this->request->search;
        $query = DB::table('tbl_disposisi')
            ->select(
                'tbl_disposisi.id_disposisi',
                'tbl_disposisi.tujuan',
                'tbl_disposisi.isi_disposisi',
                'tbl_disposisi.sifat',
                'tbl_disposisi.batas_waktu',
                'tbl_disposisi.catatan',
                'tbl_disposisi.id_surat',
                'tbl_disposisi.id_user',
                'tbl_surat_masuk.no_agenda',
                'tbl_surat_masuk.no_surat',
                'tbl_surat_masuk.asal_surat',
                'tbl_surat_masuk.kode',
                'tbl_surat_masuk.indeks',
                'tbl_surat_masuk.tgl_surat',
                'tbl_surat_masuk.tgl_diterima',
                'tbl_surat_masuk.file'
            )
            ->join('tbl_surat_masuk', 'tbl_disposisi.id_surat', '=', 'tbl_surat_masuk.id_surat')
            ->where('tbl_surat_masuk.no_surat', 'LIKE', "%$search%")
            ->orderByRaw('RAND()')
            ->limit(8)
            ->get();

        return response()->json(['data' => $query]);

    }
}
