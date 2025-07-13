<?php

namespace App\Http\Controllers;

use App\Models\Penghargaan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PenghargaanController extends Controller
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
        $data = Penghargaan::select(
            'penghargaan.id',
            'penghargaan.namapenghargaan',
            'penghargaan.kategori',
            'penghargaan.diberikanoleh',
            'penghargaan.lokasi',
            'penghargaan.tahun',
            'penghargaan.file',
            'penghargaan.created_at',
            'penghargaan.updated_at',
            'created_by_user.nama_lengkap as created_by',
            'updated_by_user.nama_lengkap as updated_by',
            'penghargaan.created_on',
            'penghargaan.updated_on',
            DB::raw('DATE_FORMAT(penghargaan.created_on,"%d-%M-%Y %H:%m") as created_on'),
            DB::raw('DATE_FORMAT(penghargaan.updated_on,"%d-%M-%Y %H:%m") as updated_on'),

        )
            ->leftJoin('users as created_by_user', 'penghargaan.created_by', '=', 'created_by_user.id_user')
            ->leftJoin('users as updated_by_user', 'penghargaan.updated_by', '=', 'updated_by_user.id_user')
            ->orderBy('penghargaan.id', 'desc')
            ->get();
        return response()->json($data);
    }
    public function create()
    {
        //
    }
    public function store(Request $request)
    {
        try {
            $date = Carbon::now()->format('y-m-d h:i:s');
            $gambar = $this->request->file('file');
            $fileName = 'award' . rand() . '.' . $gambar->getClientOriginalExtension();
            $gambar->move(public_path('award'), $fileName);

            $data = new Penghargaan;
            $data->namapenghargaan = $this->request->namapenghargaan;
            $data->kategori = $this->request->kategori;
            $data->diberikanoleh = $this->request->diberikanoleh;
            $data->lokasi = $this->request->lokasi;
            $data->tahun = $this->request->tahun;
            $data->file = $fileName;
            $data->created_at = $this->request->created_at ? $this->request->created_at : date('Y-m-d');
            $data->updated_at = $this->request->updated_at ? $this->request->updated_at : date('Y-m-d');
            $data->created_by = $this->request->user_id;
            $data->created_on = date('Y-m-d H:i:s');
            $data->save();

            return response()->json([
                'status' => 'ok', 'messages' => 'databerhasil di tambahkan',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error event insert data',
                'messages' => 'error code' . $th,
            ], 400);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Penghargaan  $penghargaan
     * @return \Illuminate\Http\Response
     */
    public function show(Penghargaan $penghargaan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Penghargaan  $penghargaan
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Penghargaan::find($id);
        return response()->json($data);
    }
    public function update($id)
    {
        try {
            // Check if the record exists
            $data = Penghargaan::find($id);
            if (!$data) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Record not found',
                ], 404);
            }

            $date = date('Y-m-d H:i:s');
            if ($this->request->file('file')) {
                $gambar = $this->request->file('file');
                $fileName = 'award' . rand() . '.' . $gambar->getClientOriginalExtension();
                $gambar->move(public_path('award'), $fileName);

                $data->file = $fileName;
            }

            $data->namapenghargaan = $this->request->namapenghargaan;
            $data->kategori = $this->request->kategori;
            $data->diberikanoleh = $this->request->diberikanoleh;
            $data->lokasi = $this->request->lokasi;
            $data->tahun = $this->request->tahun;
            $data->created_at = $date;
            $data->updated_at = $date;
            $data->updated_on = date('Y-m-d H:i:s');
            $data->updated_by = $this->request->id_user;

            $data->save();

            return response()->json([
                'status' => 'ok', 'message' => 'Data berhasil diupdate',
            ], 200);
        } catch (\Exception $th) {

            return response()->json([
                'status' => 'error',
                'message' => 'Error updating data',
                'errorcode' => $th->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $data = Penghargaan::find($id);
            // $file = $data->file;
            // @unlink(public_path('award'), $file);
            $data->delete();
            return response()->json([
                'status' => 'ok', 'messages' => 'databerhasil di hapus',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'gagal hapus data',
                'errorcode' => 'error code' . $th,
            ], 500);
        }
    }
}
