<?php

namespace App\Http\Controllers;

use App\Models\surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuratController extends Controller
{

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function index()
    {
        $search = $this->request->search;
        $query = DB::table('tbl_surat_masuk')
            ->select('id_surat', 'no_agenda', 'no_surat', 'asal_surat', 'isi', 'kode', 'indeks', 'tgl_surat', DB::raw('DATE_FORMAT(tgl_surat, "%Y-%M-%d") as tgl_ind'), 'tgl_diterima', 'file', 'keterangan', 'id_user', 'disposisi');
        if ($this->request->has('disposisi')) {
            $query->where('disposisi', $this->request->disposisi);
        }
        // tambahkan kolom file_surat dengan hyperlink download
        $query->selectRaw("CONCAT('<a href=\"', '" . url('sppdprint/download') . "?file=assets/file_surat/', tbl_surat_masuk.file, '\" class=\"btn btn-success btn-xs\"><i class=\"fa fa-download\"></i></a>') as file_surat");
        // tambahkan kolom action sesuai dengan level user
        if ($this->request->level == 'admin' || $this->request->level == 'staff') {
            $query->selectRaw("CONCAT('" . Url('tsuratmasuk/detail/', '<i class="fa fa-book"></i>', ['class' => 'btn btn-info btn-xs edit']) . "', ' <button id=\"edit\" to=\"" . url('tsuratmasuk/edit/') . "', tbl_surat_masuk.id_surat, '\" class=\"btn btn-warning btn-xs\"><i class=\"fa fa-edit\"></i></button>', ' <a href=\"#\" class=\"btn btn-danger btn-xs delete\" onclick=\"return hapus(', tbl_surat_masuk.id_surat, ')\"><i class=\"fa fa-trash\"></i></a>&nbsp; <a href=\"#\" class=\"btn btn-info btn-xs\" onclick=\"return set_disposisi(', tbl_surat_masuk.id_surat, ')\"><i class=\"fa fa-check\"></i> </a>') as action");
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('tbl_surat_masuk.no_surat', 'like', '%' . $search . '%')
                    ->orWhere('tbl_surat_masuk.asal_surat', 'like', '%' . $search . '%');
            });
        }
        $suratMasuk = $query->paginate(10);
        return $suratMasuk;
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
        // rturn($this->request->file('files'));
        // die;



    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\surat  $surat
     * @return \Illuminate\Http\Response
     */
    public function show(surat $surat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\surat  $surat
     * @return \Illuminate\Http\Response
     */
    public function edit(surat $surat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\surat  $surat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, surat $surat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\surat  $surat
     * @return \Illuminate\Http\Response
     */
    public function destroy(surat $surat)
    {
        //
    }

    //report action
    function datareportsurat()
    {
        try {
            $search = $this->request->search;
            $query = DB::table('tbl_surat_masuk')
                ->select('id_surat', 'no_agenda', 'no_surat', 'asal_surat', 'isi', 'kode', 'indeks', 'tgl_surat', DB::raw('DATE_FORMAT(tgl_surat, "%Y-%M-%d") as tgl_ind'), 'tgl_diterima', 'file', 'keterangan', 'id_user', 'disposisi');
            if ($this->request->has('disposisi')) {
                $query->where('disposisi', $this->request->disposisi);
            }
            if ($this->request->level == 'admin' || $this->request->level == 'staff') {
                $query->selectRaw("CONCAT('" . Url('tsuratmasuk/detail/', '<i class="fa fa-book"></i>', ['class' => 'btn btn-info btn-xs edit']) . "', ' <button id=\"edit\" to=\"" . url('tsuratmasuk/edit/') . "', tbl_surat_masuk.id_surat, '\" class=\"btn btn-warning btn-xs\"><i class=\"fa fa-edit\"></i></button>', ' <a href=\"#\" class=\"btn btn-danger btn-xs delete\" onclick=\"return hapus(', tbl_surat_masuk.id_surat, ')\"><i class=\"fa fa-trash\"></i></a>&nbsp; <a href=\"#\" class=\"btn btn-info btn-xs\" onclick=\"return set_disposisi(', tbl_surat_masuk.id_surat, ')\"><i class=\"fa fa-check\"></i> </a>') as action");
            }
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('tbl_surat_masuk.no_surat', 'like', '%' . $search . '%')
                        ->orWhere('tbl_surat_masuk.asal_surat', 'like', '%' . $search . '%');
                });
            }
            $suratMasuk = $query->paginate(10);
            return $suratMasuk;
        } catch (\Throwable $e) {
            return response()->json([
                'messages' => $e->getMessage()
            ]);
        }
    }
    function reportDisposisi()
    {
        try {
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
            return response()->json([
                'data' => $disposisi
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'data' => [],
                'messages' => $e->getMessage()
            ], 400);

        }
    }
}
