<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class SliderController extends Controller
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
        $data = Slider::select(
            'slider.id_slide as  id',
            'slider.title',
            'slider.active',
            'slider.user_id',
            'slider.image',
            'slider.created_on',
            'slider.updated_on',
            'created_by_user.nama_lengkap as created_by',
            'updated_by_user.nama_lengkap as updated_by'
        )->leftJoin('users as created_by_user', 'slider.created_by', '=', 'created_by_user.id_user')
            ->leftJoin('users as updated_by_user', 'slider.updated_by', '=', 'updated_by_user.id_user')
            ->orderBy('slider.id_slide', 'desc')
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

    public function actived()
    {
        $id_slider = $this->request->slider_id;
        $parameter = $this->request->active;
        $data = Slider::where('id_slide', $id_slider)->update([
            'active' => $parameter,
        ]);
        return response()->json([
            'messages' => "data berhasil di simpan",
        ]);
    }
    public function store(Request $request)
    {
        try {
            $rules = array(
                'judul' => 'required',
                'user_id' => 'required',
            );

            $messages = array(
                'gambar' => 'required|mimes:jpeg,jpg,png,bmp',
                'link.required' => 'Link Url wajib di isi',
            );
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                $messages = $validator->messages();
                $errors = $messages->all();
                return response()->json($errors, 400);
            }
            $date = Carbon::now()->format('y-m-d h:i:s');
            $gambar = $this->request->file('gambar');
            $filename = 'slider_gambar' . rand() . '.' . $gambar->getClientOriginalExtension();
            $gambar->move('./slider/', $filename);

            $data = new Slider();
            $data->title = $this->request->titlen;
            $data->judul = $this->request->judul;
            $data->link = $this->request->link ? $this->request->link : '';
            $data->active = $this->request->active ? $this->request->active : 'N';
            $data->created_at = date('Y-m-d h:i:s');
            $data->updated_at = date('Y-m-d h:i:s');
            $data->created_by = $this->request->user_id;
            $data->created_on = date('Y-m-d H:i:s');
            $data->image = $filename;
            $data->save();
            return response()->json([
                'messages' => 'data berhasil di tambahkan',
            ]);

        } catch (\App\Models\Slider $th) {
            return response()->json([
                'code' => '400',
                'messages' => $th->getMessage(),
            ], 400);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function show(Slider $slider)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Slider::where('id_slide', $id)->get();
        return response()->json($data);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $rules = array(
                'judul' => 'required',
                'link' => 'required',
                'title' => 'required',
            );
            $messages = array(
                'judul.required' => 'Judul wajib di isi',
                'link.required' => 'Link Wajib di isi',
                'gambar' => 'required|mimes:jpeg,jpg,png,bmp',
                'title.required' => 'Judul dalam bahasa inggris wajib di isi',
            );
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {

                $messages = $validator->messages();
                $errors = $messages->all();
                return response()->json($errors, 400);
            }

            $data = Slider::find($id);
            $date = Carbon::now()->format('y-m-d h:i:s');

            $gambar = $this->request->file('gambar');
            if ($gambar) {
                $filename = 'artikel_file' . rand() . '.' . $gambar->getClientOriginalExtension();
                $gambar->move('./slider/', $filename);
            } else {
                $filename = $data->image;
            }
            $data->title = $this->request->title;
            $data->judul = $this->request->judul;
            $data->link = $this->request->link;
            $data->image = $filename;
            $data->updated_on = date('Y-m-d H:i:s');
            $data->active = $this->request->active ? $this->request->active : 'N';
            $data->updated_by = $this->request->user_id;

            $data->save();
            return response()->json([
                'messages' => 'data berhasil di tambahkan',
            ]);
        } catch (\App\Models\Slider $th) {
            return response()->json([
                'code' => '400',
                'messages' => $th->getMessage(),
            ], 400);
        }

    }
    public function destroy($id)
    {
        try {
            $data = Slider::where('id_slide', $id);
            if ($data->get()->count() > 0) {
                $filename = $data->get()->first()->filename;
                @unlink('./slider/', $filename);
                $data->delete();
                return response()->json([
                    'messages' => 'data berhasil di hapus',
                ]);
            }
        } catch (\App\Models\Slider $th) {
            return response()->json([
                'code' => '400',
                'messages' => $th->getMessage(),
            ], 400);
        }

    }
}
