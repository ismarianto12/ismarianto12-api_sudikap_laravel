<?php

namespace App\Http\Controllers;

use App\Models\suratKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuratKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $search = $this->request->search;
        // response()->json($search);
        // die;
        $data = DB::table('tbl_surat_keluar as a')
            ->select(
                'a.id_surat',
                'a.id_jenis_surat',
                'a.tujuan',
                'a.no_surat',
                'a.isi',
                'a.kode',
                'a.tgl_surat',
                'a.tgl_catat',
                'a.file',
                'a.keterangan',
                'a.id_user',
                'a.no_agenda',
                'b.id_user',
                'b.username',
                'b.nama'
            )
            ->leftJoin('login as b', 'a.id_user', '=', 'b.id_user');
        if ($this->request->has('disposisi')) {
            $data->where('disposisi', $this->request->disposisi);
        }

        // if (auth()->user()->level != 'admin') {
        //     $data->where('a.id_user', auth()->id());
        // }
        // if (auth()->user()->level == 'admin' || auth()->user()->level == 'staff') {
        //     $data->addSelect(DB::raw('CONCAT("<a href=\'", "' . url('/tsuratkeluar/detail') . '/", a.id_agenda, "\'><i class=\'fa fa-book\'></i>Read</a>", " ", "<a href=\'", "' . url('/tsuratkeluar/edit') . '/", a.id_agenda, "\'><i class=\'fa fa-edit\'></i> Update</a>", " ", "<a href=\'#\' class=\'delete\' data-id=\'", a.id_agenda, "\'><i class=\'fa fa-trash\'></i> Delete</a>") as action'));
        // }
        if ($search) {
            $data->where(function ($q) use ($search) {
                $q->where('a.no_surat', 'like', '%' . $search . '%')
                    ->orWhere('a.kode', 'like', '%' . $search . '%')
                    ->orWhere('a.tujuan', 'like', '%' . $search . '%');
                // ->orWhere('c.nama', 'like', '%' . $search . '%')
                // ->orWhere('d.nama_satuan', 'like', '%' . $search . '%')
                // ->orWhere('e.nama_lokasi', 'like', '%' . $search . '%');
            });
        }
        return $data->paginate($this->request->page);
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
        // return response($request->input('id_jenis_surat'));
        // die;
        // //
        try {
            $tgl_surat = $request->input('tgl_surat');
            $tgl_catat = date('Y-m-d');

            $f_tgl_surat = date("Y-m-d", strtotime($tgl_surat));
            $f_tgl_catat = date("Y-m-d", strtotime($tgl_catat));
            $request->validate([
                'file' => 'required|mimes:pdf,doc,docx,xls,xlsx',
            ]);
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = 'surat_keluar_' . date('Y-m-d') . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('./file_surat'), $fileName);

                $data = [
                    'no_agenda' => $request->input('no_agenda', 'null'),
                    'tujuan' => $request->input('tujuan'),
                    'no_surat' => $request->input('no_surat'),
                    'isi' => $request->input('isi_surat'),
                    'kode' => $request->input('kode'),
                    'tgl_surat' => $f_tgl_surat,
                    'tgl_catat' => $f_tgl_catat,
                    'id_jenis_surat' => $request->input('id_jenis_surat'),
                    'file' => $fileName,
                    'keterangan' => $request->input('keterangan'),
                    'id_user' => 1,
                ];
                // Simpan data ke dalam database
                DB::table('tbl_surat_keluar')->insert($data);
                session()->flash('message', '<div class="callout callout-success fade-in"><i class="fa fa-check"></i>Data Berhasil Di Tambahkan.</div>');

                return response()->json([
                    'status' => 1,
                    'msg' => 'berhasil'
                ]);
            } else {
                return response()->json([
                    'status' => 2,
                    'msg' => 'File tidak ditemukan'
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 2,
                'msg' => $th->getMessage()
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\suratKeluar  $suratKeluar
     * @return \Illuminate\Http\Response
     */
    public function show(suratKeluar $suratKeluar)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\suratKeluar  $suratKeluar
     * @return \Illuminate\Http\Response
     */
    public function edit(suratKeluar $suratKeluar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\suratKeluar  $suratKeluar
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, suratKeluar $suratKeluar)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\suratKeluar  $suratKeluar
     * @return \Illuminate\Http\Response
     */
    public function destroy(suratKeluar $suratKeluar)
    {
        //
    }
}
