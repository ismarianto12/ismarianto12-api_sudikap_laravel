<?php

namespace App\Http\Controllers;

use App\Models\statistik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatistikController extends Controller
{

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function index()
    {
        $data = statistik::select(
            'statistik.id',
            'statistik.status',
            'statistik.keterangan', 'statistik.jumlah',
            'created_by_user.nama_lengkap as created_by',
            'updated_by_user.nama_lengkap as updated_by',
            DB::raw('DATE_FORMAT(statistik.created_on,"%d-%M-%Y %H:%m") as created_on'),
            DB::raw('DATE_FORMAT(statistik.updated_on,"%d-%M-%Y %H:%m") as updated_on'),

        )
            ->leftJoin('users as created_by_user', 'statistik.created_by', '=', 'created_by_user.id_user')
            ->leftJoin('users as updated_by_user', 'statistik.updated_by', '=', 'updated_by_user.id_user')
            ->orderBy('statistik.id', 'desc')
            ->get();

        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            $create = new statistik();
            $create->keterangan = $this->request->keterangan;
            $create->jumlah = $this->request->jumlah;
            $create->user_id = $this->request->user_id;
            $create->created_by = $this->request->user_id;
            $create->created_on = date('Y-m-d H:i:s');

            $create->save();

            return response()->json([
                'data' => 'berhasil',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'messages' => $th,
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\statistik  $statistik
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $create = statistik::find($id);
            return response()->json([
                'data' => $create,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'messages' => $th,
            ]);
        }
    }

    public function edit($id)
    {
        $data = Statistik::find($id);
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\statistik  $statistik
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        try {
            $create = statistik::find($id);
            $create->keterangan = $this->request->keterangan;
            $create->jumlah = $this->request->jumlah;
            $create->user_id = $this->request->user_id;
            $create->updated_on = date('Y-m-d H:i:s');
            $create->updated_by = $this->request->user_id;

            $create->save();
            return response()->json([
                'data' => 'berhasil di update',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'messages' => $th,
            ]);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\statistik  $statistik
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            statistik::find($id)->delete();
            return response()->json([
                'msg' => 'berhasil hapus data',
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'msg' => 'gagal hapus data',
            ]);
        }
    }
}
