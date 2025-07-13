<?php

namespace App\Http\Controllers;

use App\Models\pages;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Validator;

class PagesController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function index()
    {
        $data = pages::select(
            'pages.id_pages as id',
            'pages.title',
            'pages.titleen',
            'pages.content',
            'pages.contenten',
            'pages.seotitle',
            'pages.tags',
            'pages.picture',
            'pages.active',
            'created_by_user.nama_lengkap as created_by',
            'updated_by_user.nama_lengkap as updated_by',
            DB::raw('DATE_FORMAT(pages.created_on,"%d-%M-%Y %H:%m") as created_on'),
            DB::raw('DATE_FORMAT(pages.updated_on,"%d-%M-%Y %H:%m") as updated_on'),

        )->leftJoin('users as created_by_user', 'pages.created_by', '=', 'created_by_user.id_user')
            ->leftJoin('users as updated_by_user', 'pages.updated_by', '=', 'updated_by_user.id_user')
            ->orderBy('pages.id_pages', 'desc')
            ->get();

        return response()->json($data);
    }

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

            $validator = Validator::make($request->all(), [
                'picture' => 'required|mimes:jpeg,jpg,png,bmp,pdf,ppt',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'messages' => $validator->errors(),
                ], 400); // Menggunakan status HTTP 400 untuk kesalahan validasi
            }

            $date = Carbon::now()->format('y-m-d h:i:s');
            $gambar = $this->request->file('picture');
            $filename = 'halaman_pages' . rand() . '.' . $gambar->getClientOriginalExtension();
            $gambar->move('./halaman/', $filename);

            $data = new pages();
            $data->id_pages = $this->request->id_pages;
            $data->title = $this->request->title ? $this->request->title : $this->request->headline;
            $data->titleen = $this->request->titleen ? $this->request->titleen : '';
            $data->content = $this->request->content ? $this->request->content : '';
            $data->contenten = $this->request->contenten ? $this->request->contenten : '';
            $data->seotitle = $this->createSeoUrl($data->title);
            $data->created_on = date('Y-m-d H:i:s');
            $data->created_by = $this->request->id_user;
            $data->updated_by = $this->request->id_user;
            $data->tags = $this->request->tags;
            $data->picture = $filename;
            $data->active = $this->request->active;
            $data->created_by = $this->request->user_id;
            $data->created_on = date('Y-m-d H:i:s');

            $data->save();
            return response()->json([
                'status' => 'ok', 'messages' => 'databerhasil di tambahkan',
            ]);

        } catch (\pages $th) {
            return response()->json([
                'status' => 'error event insert data',
                'errorcode' => 'error code' . $th,
            ], 400);
        }

    }

    public function createSeoUrl($string)
    {
        $string = strtolower($string); // Convert to lowercase
        $string = preg_replace('/[^a-z0-9\-]/', '-', $string); // Replace non-alphanumeric characters with hyphens
        $string = preg_replace('/-+/', '-', $string); // Replace multiple hyphens with a single hyphen
        $string = trim($string, '-'); // Trim hyphens from the beginning and end
        return $string;
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\pages  $pages
     * @return \Illuminate\Http\Response
     */
    public function show(pages $pages)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\pages  $pages
     * @return \Illuminate\Http\Response
     */

    private function convertToHTML($text)
    {
        // Define an array of special characters and their corresponding HTML entities
        $htmlEntities = array(
            '<' => '&lt;',
            '>' => '&gt;',
            '"' => '&quot;',
            '&' => '&amp;',
            ' ' => '&nbsp;',
        );

        foreach ($htmlEntities as $char => $entity) {
            $text = str_replace($char, $entity, $text);
        }

        return $text;
    }
    public function edit($id)
    {
        $data = pages::where('id_pages', $id)->first();
        return response()->json($data);
    }
    public function update($id)
    {
        try {

            $data = pages::where('id_pages', $id);
            if ($data->get()->count() > 0) {
                if ($this->request->file('picture')) {

                    $validator = Validator::make($this->request->all(), [
                        'picture' => 'required|mimes:jpeg,jpg,png,bmp,pdf,ppt',
                    ]);

                    if ($validator->fails()) {
                        return response()->json([
                            'status' => 'error',
                            'messages' => $validator->errors(),
                        ], 400);
                    }
                    $gambar = $this->request->file('picture');
                    $filename = 'halaman_pages' . rand() . '.' . $gambar->getClientOriginalExtension();

                    $gambar->move('./halaman/', $filename);
                } else {
                    $filename = $data->first()->picture;
                }

                $data->update([
                    'title' => $this->request->title,
                    'titleen' => $this->request->titleen,
                    'content' => $this->request->content,
                    'contenten' => $this->request->contenten,
                    // 'seotitle' => $this->createSeoUrl($this->request->title),
                    'tags' => $this->request->tags,
                    'picture' => $filename,
                    'active' => $this->request->active,
                    'updated_on' => date('Y-m-d H:i:s'),
                    'updated_by' => $this->request->user_id,

                ]);
                return response()->json([
                    'status' => 'ok',
                    'message' => 'Data berhasil diupdate',
                ]);
            } else {
                return response()->json([
                    'status' => 'ok',
                    'message' => 'Data berhasil diupdate',
                ], 400);
            }
        } catch (\pages $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengupdate data: ' . $th->getMessage(),
                'errorcode' => 'error code' . $th->getCode(),
            ], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $data = pages::where('id_pages', $id)->delete();
            return response()->json([
                'status' => 'ok',
                'messages' => 'berhasil',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'gagal',
                'messages' => $th,
            ]);
        }
    }
}
