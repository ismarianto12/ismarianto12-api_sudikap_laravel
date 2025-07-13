<?php

namespace App\Http\Controllers;

use App\Models\Tags;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagsController extends Controller
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
        $data = Tags::select(
            'tag.id_tag as id',
            'tag.tag_title',
            'tag.tag_seo',
            'tag.count',
            DB::raw('DATE_FORMAT(tag.created_on,"%d-%M-%Y %H:%m") as created_on'),
            DB::raw('DATE_FORMAT(tag.updated_on,"%d-%M-%Y %H:%m") as updated_on'),
            'created_by_user.nama_lengkap as created_by',
            'updated_by_user.nama_lengkap as updated_by'
        )->leftJoin('users as created_by_user', 'tag.created_by', '=', 'created_by_user.id_user')
            ->leftJoin('users as updated_by_user', 'tag.updated_by', '=', 'updated_by_user.id_user')
            ->orderBy('tag.id_tag', 'desc')
            ->get();

        return response()->json($data);
    }

    public function createSeoUrl($string)
    {
        $string = strtolower($string); // Convert to lowercase
        $string = preg_replace('/[^a-z0-9\-]/', '-', $string); // Replace non-alphanumeric characters with hyphens
        $string = preg_replace('/-+/', '-', $string); // Replace multiple hyphens with a single hyphen
        $string = trim($string, '-');
        return $string;
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
    public function store(Request $request)
    {
        try {

            $data = new Tags();
            $data->tag_title = $request->title;
            $data->tag_seo = $this->createSeoUrl($request->title);

            $data->count = 1;
            $data->created_by = $this->request->user_id;
            $data->created_on = date('Y-m-d H:i:s');

            $data->save();
        } catch (Tags $th) {
            return response()->json([
                'messages' => $th->getMessage(),
            ], 400);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tags  $tags
     * @return \Illuminate\Http\Response
     */
    public function show(Tags $tags)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tags  $tags
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Tags::where('id_tag', $id)->first();
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tags  $tags
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Tags::where('id_tag', $id)->update([
            'tag_title' => $request->tag_title,
            'tag_seo' => $this->createSeoUrl($request->tag_seo),
            'count' => '1',
            'updated_on' => date('Y-m-d H:i:s'),
            'updated_by' => $this->request->user_id,

        ]);
        return response()->json([
            'messages' => 'data berhasil di edit',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tags  $tags
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $data = Tags::where('id_tag', $id);
            $data->delete();
            return response()->json([
                'messages' => "data tag berhasil di hapus",
            ]);

        } catch (\Tags $th) {
            return response()->json([
                'messages' => $th,
            ]);

        }
    }
}
