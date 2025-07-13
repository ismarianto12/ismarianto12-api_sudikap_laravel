<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\post;
use App\Models\Promosi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JadwalController extends Controller
{

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function index()
    {
        $data = Jadwal::select(
            'jadwal.id',
            'jadwal.topic',
            'jadwal.descriptionId',
            'jadwal.descriptionEn',
            'jadwal.type_edukasi',
            'jadwal.link_registrasi',
            'jadwal.image',
            'jadwal.created_at',
            'jadwal.user_id',
            'jadwal.updated_at',
            'jadwal.venue',
            'jadwal.tanggal_edukasi',
            'jadwal.jam_berakhir',
            'jadwal.jam_mulai',
            'created_by_user.nama_lengkap as created_by',
            'updated_by_user.nama_lengkap as updated_by',
            DB::raw('DATE_FORMAT(jadwal.created_on,"%d-%M-%Y %H:%m") as created_on'),
            DB::raw('DATE_FORMAT(jadwal.updated_on,"%d-%M-%Y %H:%m") as updated_on'),
        )
            ->leftJoin('users as created_by_user', 'jadwal.created_by', '=', 'created_by_user.id_user')
            ->leftJoin('users as updated_by_user', 'jadwal.updated_by', '=', 'updated_by_user.id_user')
            ->orderBy('jadwal.id', 'desc')
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function createSeoUrl($string)
    {
        $string = strtolower($string); // Convert to lowercase
        $string = preg_replace('/[^a-z0-9\-]/', '-', $string); // Replace non-alphanumeric characters with hyphens
        $string = preg_replace('/-+/', '-', $string); // Replace multiple hyphens with a single hyphen
        $string = trim($string, '-'); // Trim hyphens from the beginning and end
        return $string;
    }

    public function store(Request $request)
    {
        try {
            $gambar = $this->request->file('gambar');
            $filename = 'artikel_file' . rand() . '.' . $gambar->getClientOriginalExtension();
            $gambar->move('./public/promosi/', $filename);
            $data = new Jadwal;
            $data->topic = $this->request->topic;
            $data->venue = $this->request->venue;
            $data->descriptionId = $this->request->deskripsiId;
            $data->descriptionEn = $this->request->deskripsiEn;
            $data->type_edukasi = $this->request->type_edukasi;
            $data->link_registrasi = $this->request->link_registrasi ? $this->request->link_registrasi : 'http://example.com';
            $data->tanggal_edukasi = $this->request->tanggal_edukasi;
            $data->jam_mulai = $this->request->jam_mulai;
            $data->jam_berakhir = $this->request->jam_berakhir;

            $data->image = $filename;
            $data->created_on = date('Y-m-d H:i:s');
            $data->updated_at = date('Y-m-d H:i:s');
            $data->created_by = $this->request->user_id ? $this->request->user_id : $this->request->id_user;

            $data->save();

            return response()->json([
                'status' => 'ok', 'messages' => 'databerhasil di tambahkan',
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error event insert data',
                'errorcode' => 'error code' . $th,
            ], 400);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Promosi::find($id);
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $data = Jadwal::find($id);
            return response()->json($data);
        } catch (\App\Models\Jadwal $th) {
            return response()->json($th->getMessage());

        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\post  $post
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {

        try {

            $jadwal = Jadwal::where('id', $id);

            if ($this->request->file('gambar')) {
                $gambar = $this->request->file('gambar');

                $filename = 'artikel_file' . rand() . '.' . $gambar->getClientOriginalExtension();
                $gambar->move('./public/promosi/', $filename);
            } else {
                $ddata = $jadwal->get();
                $filename = $ddata->first()->image;
            }
            $jadwal->update(
                [
                    'topic' => $this->request->topic,
                    'venue' => $this->request->venue,
                    'descriptionId' => $this->request->deskripsiId,
                    'descriptionEn' => $this->request->deskripsiEn,
                    'type_edukasi' => $this->request->type_edukasi,
                    'tanggal_edukasi' => $this->request->tanggal_edukasi,
                    'jam_mulai' => $this->request->jam_mulai,
                    'jam_berakhir' => $this->request->jam_berakhir,

                    'link_registrasi' => $this->request->link_registrasi,
                    'image' => $filename,
                    'created_at' => Carbon::now()->format('Y-m-d h:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d h:i:s'),
                    'updated_on' => date('Y-m-d H:i:s'),
                    'updated_by' => $this->request->user_id ? $this->request->user_id : $this->request->id_user,

                ]
            );
            return response()->json([
                'status' => 'ok', 'messages' => 'data berhasil di upadate',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error event insert data',
                'errorcode' => 'error code' . $th,
            ], 400);
        }

    }

    public function destroy($id)
    {
        try {
            Jadwal::where('id', $id)->delete($id);
            return response()->json(['messages' => 'data berhasil dihapus']);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
