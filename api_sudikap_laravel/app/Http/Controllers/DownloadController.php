<?php

namespace App\Http\Controllers;

use App\Models\Download;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DownloadController extends Controller
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
        $data = Download::select(
            'download.id',
            'download.judul',
            'download.judulEng',
            'download.isi',
            'download.isiEng',
            'download.file',
            'download.category_donwload_id',
            'download.updated_at',
            'download.user_id',
            'created_by_user.nama_lengkap as created_by',
            'updated_by_user.nama_lengkap as updated_by',
            DB::raw('DATE_FORMAT(download.created_on,"%d-%M-%Y %H:%m") as created_on'),
            DB::raw('DATE_FORMAT(download.updated_on,"%d-%M-%Y %H:%m") as updated_on'),

        )->join('users', 'users.id_user', '=', 'download.user_id', 'left')
            ->leftJoin('users as created_by_user', 'download.created_by', '=', 'created_by_user.id_user')
            ->leftJoin('users as updated_by_user', 'download.updated_by', '=', 'updated_by_user.id_user')
            ->join('category_download', 'category_download.id', '=', 'download.category_donwload_id', 'left')->get();

        return response()->json($data);

    }
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        try {
            $date = Carbon::now()->format('Y-m-d');
            $gambar = $request->file('file');
            $filename = 'file_download' . rand() . '.' . $gambar->getClientOriginalExtension();
            $gambar->move('./download/', $filename);

            $data = new Download();

            $data->judul = $request->input('judulin');
            $data->judulEng = $request->input('judulEn');
            $data->isi = $request->input('isi');
            $data->isiEng = $request->input('isiEng');
            $data->file = $filename;
            $data->category_donwload_id = 1;
            $data->created_by = $this->request->user_id;
            $data->created_on = date('Y-m-d H:i:s');

            $data->save();

            return response()->json([
                'status' => 'ok',
                'messages' => 'data download berhasil di tambahkan',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'messages' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Download  $download
     * @return \Illuminate\Http\Response
     */
    public function show(Download $download)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Download  $download
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Download::find($id);
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Download  $download
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        try {
            $date = Carbon::now()->format('y-m-d h:i:s');
            $gambar = $this->request->file('file');
            $data = Download::find($id);
            if ($gambar) {
                $filename = 'file_download' . rand() . '.' . $gambar->getClientOriginalExtension();
                $gambar->move('./public/download/', $filename);
            } else {
                $filename = $data->file;
            }
            $data->judul = $this->request->judulin;
            $data->judulEng = $this->request->judulEng;
            $data->isi = $this->request->isi;
            $data->isiEng = $this->request->isiEng;
            $data->file = $filename;
            $data->category_donwload_id = 1;
            $data->updated_on = date('Y-m-d H:i:s');
            $data->updated_by = $this->request->user_id;
            $data->save();
            return response()->json([
                'status' => 'ok',
                'messages' => 'data download berhasil di tambahkan',
            ]);
        } catch (\Download $th) {
            return response()->json([
                'status' => 'ok',
                'messages' => $th->getMessage(),
            ], 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Download  $download
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $data = Download::find($id);
            // print($data);
            // die;
            // $file = $data->file;
            // if ($file) {
            //     @unlink('./public/download/', $file);
            // }
            $data->delete();
            return response()->json([
                'status' => 'ok', 'messages' => 'databerhasil di hapus',
            ], 200);
        } catch (\Download $th) {
            return response()->json([
                'status' => 'gagal hapus data',
                'errorcode' => 'error code' . $th,
            ], 500);
        }
    }
}
