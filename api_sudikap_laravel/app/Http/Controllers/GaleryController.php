<?php

namespace App\Http\Controllers;

use App\Models\events;
use Illuminate\Http\Request;
use Validator;

class GaleryController extends Controller
{

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function index()
    {
        $data = events::select(

            'events.id',
            'events.title',
            'events.headline',
            'events.published',
            'events.active',
            'events.images',
            'events.images_desc',
            'events.created_by',
            'events.created_on',
            'events.updated_by',
            'events.updated_on',
            'events.new_version',
            'created_by_user.nama_lengkap as created_by',
            'updated_by_user.nama_lengkap as updated_by',
        )
            ->orderBy('events.id', 'desc')
            ->leftJoin('users as created_by_user', 'events.created_by', '=', 'created_by_user.id_user')
            ->leftJoin('users as updated_by_user', 'events.updated_by', '=', 'updated_by_user.id_user')

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
    public function store()
    {

        try {
            $date = now()->format('Y-m-d H:i:s'); // Use 'Y' for 4-digit year, 'H' for 24-hour format
            $filenames = [];
            $validator = Validator::make($this->request->all(), [
                'fileupload[]' => 'mimes:jpeg,jpg,png,bmp',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'messages' => $validator->errors(),
                ], 400);
                die;
            }
            foreach ($this->request->file('fileupload') as $file) {
                $extension = $file->getClientOriginalExtension();

                $filename = 'galeryfile' . uniqid() . '.' . $extension;
                $file->move(public_path('galery'), $filename);
                $filenames[] = $filename;
            }
            $bulkfileupload = implode("\r\n", $filenames);
            $data = new events();
            $data->title = $this->request->title;
            $data->headline = $this->request->headline;
            $data->published = date('Y-m-d H:i:s');
            $data->active = 1;
            $data->new_version = 1;
            $data->images = $bulkfileupload;
            $data->created_by = ($this->request->user_id) ? $this->request->user_id : $this->request->id_user;
            $data->created_on = date('Y-m-d H:i:s');

            $data->save();
            return response()->json([
                'status' => 'ok', 'messages' => 'data galery data berhasil di tambahkan',
            ]);

        } catch (\galery $th) {
            return response()->json([
                'status' => 'error event insert data',
                'errorcode' => 'error code' . $th,
            ]);
        }

    }

    public function show(galery $galery)
    {
    }
    public function edit($id)
    {
        try {
            $data = events::find($id);
            $imagedata = isset($data->images) ? $data->images : ['adsdasd adasdsad'];
            $fimage = explode("\n", $imagedata);
            $rfilenames = array_filter($fimage, 'trim');

            $countimage = count($fimage);
            $listGalery = [];
            for ($i = 0; $i <= 10; $i++) {
                $listGalery['filegalery' . ($i + 1)] = isset($rfilenames[$i]) ? $rfilenames[$i] : '';
            }
            $merge = [
                'data' => [$data],
                'galery' => [$listGalery]];
            return response()->json($merge);
        } catch (\events $th) {
            return response()->json(['messages' => $th->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\galery  $galery
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        try {
            $date = now()->format('Y-m-d H:i:s');
            $filenames = [];
            $data = events::find($id);
            $fimage = explode(' ', $data->images);
            $countimage = count($this->request->file('fileupload'));
            for ($i = 0; $i <= $countimage; $i++) {
                @unlink(public_path('galery'), $filename[$i]);
            }
            foreach ($this->request->file('fileupload') as $file) {

                $validator = Validator::make($this->request->all(), [
                    $file => 'required|mimes:jpeg,jpg,png,bmp',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => 'error',
                        'messages' => $validator->errors(),
                    ], 400);
                }
                $extension = $file->getClientOriginalExtension();
                $filename = 'galeryfile' . uniqid() . '.' . $extension;
                $file->move(public_path('galery'), $filename);
                $filenames[] = $filename;
            }
            $bulkfileupload = implode("\r\n", $filenames);
            $data->title = $this->request->title;
            $data->headline = $this->request->deskripsiId ? $this->request->deskripsiId : $this->request->deskripsiEn;
            $data->published = date('Y-m-d H:i:s');
            $data->active = 1;
            $data->new_version = 1;
            $data->images = $bulkfileupload;
            $data->updated_on = date('Y-m-d H:i:s');
            $data->updated_by = $this->request->user_id ? $this->request->user_id : $this->request->id_user;
            $data->save();
            return response()->json([
                'status' => 'ok', 'messages' => 'data galery data berhasil di update',
            ]);

        } catch (\galery $th) {
            return response()->json([
                'status' => 'error event insert data',
                'errorcode' => 'error code' . $th,
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\galery  $galery
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        try {
            $data = events::find($id);
            $fimage = explode(' ', $data->images);
            $countimage = count($this->request->file('fileupload'));
            for ($i = 0; $i <= $countimage; $i++) {
                @unlink(public_path('galery'), $filename[$i]);
            }
            $data->delete();
            return response()->json([
                'messages' => 'data berhasil di hapus',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error event insert data',
                'errorcode' => 'error code' . $th,
            ], 500);
        }
    }
}
