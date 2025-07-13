<?php

namespace App\Http\Controllers;

use App\Models\video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VideoController extends Controller
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
        $data = video::select(
            'id_video as id',
            'video.title',
            'video.date',
            'video.url',
            'video.desc',
            'video.link',
            'video.description',
            'video.headline',
            'video.user_id',
            'created_by_user.nama_lengkap as created_by',
            'updated_by_user.nama_lengkap as updated_by',
            DB::raw('DATE_FORMAT(video.created_on,"%d-%M-%Y %H:%m") as created_on'),
            DB::raw('DATE_FORMAT(video.updated_on,"%d-%M-%Y %H:%m") as updated_on'),

        )
            ->leftJoin('users as created_by_user', 'video.created_by', '=', 'created_by_user.id_user')
            ->leftJoin('users as updated_by_user', 'video.updated_by', '=', 'updated_by_user.id_user')
            ->get();
        return response()->json($data);
    }

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
        try {
            $data = new video;
            $data->id_video = $this->request->id_video;
            $data->title = $this->request->title;
            $data->date = date('Y-m-d h:i:s');
            $data->url = $this->request->url;
            $data->desc = $this->request->desc;
            $data->link = $this->request->link;
            $data->description = $this->request->description;
            $data->headline = $this->request->headline;
            $data->created_on = date('Y-m-d H:i:s');
            $data->created_by = $this->request->user_id;
            $data->save();
            return response()->json([
                'status' => 'ok',
                'messages' => 'data video berhasil di tambahkan',
            ]);
        } catch (\video $th) {
            return response()->json([
                'status' => 'ok',
                'messages' => $th,
            ], 500);

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\video  $video
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = video::where('id_video', $id)->first();
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\video  $video
     * @return \Illuminate\Http\Response
     */
    public function edit(video $video)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\video  $video
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        try {
            // Mengambil data video berdasarkan id_video
            Video::where('id_video', $id)->update([
                'id_video' => $this->request->id_video,
                'title' => $this->request->title,
                'date' => $this->request->date,
                'url' => $this->request->url,
                'desc' => $this->request->desc,
                'link' => $this->request->link,
                'description' => $this->request->description,
                'headline' => $this->request->headline,
                'updated_on' => date('Y-m-d H:i:s'),
                'updated_by' => $this->request->user_id,

            ]);

            return response()->json([
                'status' => 'ok',
                'messages' => 'Data video berhasil diubah',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'messages' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\video  $video
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        try {
            video::where('id_video', $id)->delete();
            return response()->json([
                'status' => 'ok',
                'messages' => 'data video berhasil di hapus.',
            ]);
        } catch (\video $th) {
            return response()->json([
                'status' => 'ok',
                'messages' => 'data video gagal di tambahkan',
            ]);

        }
    }
}
