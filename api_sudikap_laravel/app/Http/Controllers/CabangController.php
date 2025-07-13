<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CabangController extends Controller
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
        $perPage = $this->request->page ? $this->request->page : 1;
        $limit = isset($this->request->limit) ? $this->request->limit : 7;

        $query = DB::table('map_cabang as c')
            ->select(
                'c.id_cabang as id',
                'c.id_provinsi',
                'c.nama_cabang',
                'c.alamat1',
                'c.alamat2',
                'c.no_telp',
                'c.email',
                'c.latitude',
                'c.longitude',
                'c.tipe',
                'p.description as nama_provinsi',
                'c.updated_on',
                'c.updated_by',
                'c.created_by',
                'c.created_on',
                'created_by_user.nama_lengkap as created_by',
                'updated_by_user.nama_lengkap as updated_by',

                DB::raw('DATE_FORMAT(c.created_on,"%d-%M-%Y %H:%m") as created_on'),
                DB::raw('DATE_FORMAT(c.updated_on,"%d-%M-%Y %H:%m") as updated_on'),

            )
            ->leftJoin('users as created_by_user', 'c.created_by', '=', 'created_by_user.id_user')
            ->leftJoin('users as updated_by_user', 'c.updated_by', '=', 'updated_by_user.id_user')
            ->leftJoin('province as p', 'p.province_no', '=', 'c.id_provinsi');

        if ($this->request->q) {
            $searchTerm = '%' . $this->request->q . '%';
            $query->where(function ($query) use ($searchTerm) {
                $query->where('c.nama_cabang', 'LIKE', $searchTerm);
                $query->orWhere('c.nama_cabang', 'LIKE', $searchTerm);
            });
        }
        if ($this->request->sort) {
            $query->orderBy('c.id_cabang', $this->request->sort);
        }
        $posts = $query->paginate(7, ['*'], 'page', $perPage);
        return response()->json($posts);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data->id_provinsi = $this->request->id_provinsi;
        $data->nama_cabang = $this->request->nama_cabang;
        $data->alamat1 = $this->request->alamat1;
        $data->alamat2 = $this->request->alamat2;
        $data->no_telp = $this->request->no_telp;
        $data->email = $this->request->email;
        $data->latitude = $this->request->latitude;
        $data->longitude = $this->request->longitude;
        $data->tipe = $this->request->tipe;

        $data->save();
        return response()->json([
            'messages' => 'Data berhasil di simpan',
        ]);

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
            $data = new Cabang;
            $data->id_provinsi = $this->request->id_provinsi;
            $data->nama_cabang = $this->request->nama_cabang;
            $data->alamat1 = $this->request->alamat1;
            $data->alamat2 = $this->request->alamat2;
            $data->no_telp = $this->request->no_telp;
            $data->email = $this->request->email;
            $data->latitude = $this->request->latitude;
            $data->longitude = $this->request->longitude;
            $data->tipe = $this->request->tipe;
            $data->created_by = $this->request->user_id;
            $data->created_on = date('Y-m-d H:i:s');

            $data->save();
            return response()->json([
                'messages' => 'Data berhasil di simpan',
            ]);

        } catch (\Cabang $th) {
            return response()->json([
                'messages' => 'Data gagal di simpan',
            ], 400);

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cabang  $cabang
     * @return \Illuminate\Http\Response
     */
    public function show(Cabang $cabang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cabang  $cabang
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Cabang::where('map_cabang.id_cabang', $id)->get()->first();
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cabang  $cabang
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        try {
            $data = Cabang::where('id_cabang', $id)->update([
                'id_provinsi' => $this->request->id_provinsi,
                'nama_cabang' => $this->request->nama_cabang,
                'alamat1' => $this->request->alamat1,
                'alamat2' => $this->request->alamat2,
                'no_telp' => $this->request->no_telp,
                'email' => $this->request->email,
                'latitude' => $this->request->latitude,
                'longitude' => $this->request->longitude,
                'tipe' => $this->request->tipe,
                'updated_on' => date('Y-m-d H:i:s'),
                'updated_by' => $this->request->user_id,
            ]);
            return response()->json([
                'messages' => 'Data berhasil di simpan',
            ]);
        } catch (\App\Models\Cabang $th) {
            return response()->json([
                'messages' => 'Data gagal di simpan',
            ]);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cabang  $cabang
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Cabang::where('id_cabang', $id)->delete();
            return response()->json(['messages' => 'data berhasil di delete']);
        } catch (Cabang $th) {
            return response()->json([
                'messages' => 'data berhasil di hapus' . $th->getMessage(),
            ]);
        }
    }
}
