<?php

namespace App\Http\Controllers;

use App\Models\album as Album;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlbumController extends Controller
{

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function index()
    {
        //

        $data = Album::select(
            'album.id_album as id',
            'album.title',
            'album.seotitle',
            'album.active',
            'created_by_user.nama_lengkap as created_by',
            'updated_by_user.nama_lengkap as updated_by',
            DB::raw('DATE_FORMAT(album.created_on,"%d-%M-%Y %H:%m") as created_on'),
            DB::raw('DATE_FORMAT(album.updated_on,"%d-%M-%Y %H:%m") as updated_on'),

        )->leftJoin('users as created_by_user', 'album.created_by', '=', 'created_by_user.id_user')
            ->leftJoin('users as updated_by_user', 'album.updated_by', '=', 'updated_by_user.id_user')
            ->orderBy('album.id_album', 'desc')
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
    private function createSeoUrl($string)
    {
        $string = strtolower($string); // Convert to lowercase
        $string = preg_replace('/[^a-z0-9\-]/', '-', $string); // Replace non-alphanumeric characters with hyphens
        $string = preg_replace('/-+/', '-', $string); // Replace multiple hyphens with a single hyphen
        $string = trim($string, '-');
        return $string;
    }

    public function store(Request $request)
    {
        try {
            $data = new Album;
            $data->title = $this->request->title;
            $data->seotitle = $this->createSeoUrl($this->request->url);
            $data->active = $this->request->active;
            $data->created_by = $this->request->user_id;
            $data->created_on = date('Y-m-d H:i:s');

            $data->save();
            return response()->json([
                'messages' => 'data berhsil di simpan',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'messages' => $th->getMesage(),
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Album  $Album
     * @return \Illuminate\Http\Response
     */
    public function show(Album $Album)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Album  $Album
     * @return \Illuminate\Http\Response
     */
    public function edit(Album $Album, $id)
    {
        $album = Album::find($id);
        return response()->json($album);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Album  $Album
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {

        try {
            // $data = Album::we;
            $data = Album::where('id_Album', $id)
                ->update([
                    'title' => $this->request->title,
                    'seotitle' => $this->createSeoUrl($this->request->url),
                    'active' => $this->request->active,
                    'updated_on' => date('Y-m-d H:i:s'),
                    'updated_by' => $this->request->user_id,

                ]);
            return response()->json([
                'messages' => 'data berhsil di simpan',
            ]);
        } catch (\Album $th) {
            return response()->json([
                'messages' => $th->getMesage(),
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Album  $Album
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $data = Album::find($id);
            $data->delete();
            return response()->json([
                'messages' => 'data berhasil di hapus',
            ]);
        } catch (Album $th) {
            return response()->json([
                'messages' => $th->getMessage(),
            ], 500);

        }
    }
}
