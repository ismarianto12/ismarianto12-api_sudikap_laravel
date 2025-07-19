<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PegawaiController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
            ->leftJoin('sikd_satker', 'pegawai.sikd_satker_id', '=', 'sikd_satker.id')
            ->where('pegawai.status_deleted', 0);

        if ($id_satker) {
            $query->where('pegawai.sikd_satker_id', $id_satker);
        }
        
        $query->addSelect(DB::raw("CONCAT('" . url('pegawai/edit/') . "', pegawai.id) as edit_url"));
        $query->addSelect(DB::raw("CONCAT('<button type=\"button\" class=\"btn btn-danger btn-xs delete\" onclick=\"return hapus(', pegawai.id, ')\"><i class=\"fa fa-trash\"></i></button>') as delete_button"));
        
        $dataPegawai = $query->paginate($page);
        return $dataPegawai;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nip' => 'required|unique:pegawai,nip',
            'nama' => 'required',
            'sikd_satker_id' => 'required|exists:sikd_satker,id',
            'no_hp' => 'required',
            'alamat' => 'required',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required',
            'golongan' => 'required',
            'jabatan' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $pegawai = new Pegawai();
            $pegawai->fill($request->all());
            $pegawai->username = auth()->user()->username;
            $pegawai->datetime_insert = now();
            $pegawai->status_deleted = 0;
            $pegawai->save();

            return response()->json([
                'success' => true,
                'message' => 'Data pegawai berhasil ditambahkan',
                'data' => $pegawai
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan data pegawai',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pegawai = Pegawai::where('id', $id)
            ->where('status_deleted', 0)
            ->first();

        if (!$pegawai) {
            return response()->json([
                'success' => false,
                'message' => 'Data pegawai tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $pegawai
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pegawai = Pegawai::where('id', $id)
            ->where('status_deleted', 0)
            ->first();

        if (!$pegawai) {
            return response()->json([
                'success' => false,
                'message' => 'Data pegawai tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $pegawai
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::where('id', $id)
            ->where('status_deleted', 0)
            ->first();

        if (!$pegawai) {
            return response()->json([
                'success' => false,
                'message' => 'Data pegawai tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nip' => 'required|unique:pegawai,nip,'.$id,
            'nama' => 'required',
            'sikd_satker_id' => 'required|exists:sikd_satker,id',
            'no_hp' => 'required',
            'alamat' => 'required',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required',
            'golongan' => 'required',
            'jabatan' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $pegawai->fill($request->all());
            $pegawai->username_update = auth()->user()->username;
            $pegawai->datetime_update = now();
            $pegawai->save();

            return response()->json([
                'success' => true,
                'message' => 'Data pegawai berhasil diperbarui',
                'data' => $pegawai
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data pegawai',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pegawai = Pegawai::where('id', $id)
            ->where('status_deleted', 0)
            ->first();

        if (!$pegawai) {
            return response()->json([
                'success' => false,
                'message' => 'Data pegawai tidak ditemukan'
            ], 404);
        }

        try {
            // Soft delete
            $pegawai->status_deleted = 1;
            $pegawai->username_update = auth()->user()->username;
            $pegawai->datetime_update = now();
            $pegawai->save();

            return response()->json([
                'success' => true,
                'message' => 'Data pegawai berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data pegawai',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}