<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function index()
    {
        //

        $data = Category::select('category.id_category as id',
            'category.title',
            'category.seotitle',
            'category.active',
            'created_by_user.nama_lengkap as created_by',
            'updated_by_user.nama_lengkap as updated_by',
            DB::raw('DATE_FORMAT(category.created_on,"%d-%M-%Y %H:%m") as created_on'),
            DB::raw('DATE_FORMAT(category.updated_on,"%d-%M-%Y %H:%m") as updated_on'),

        )->leftJoin('users as created_by_user', 'category.created_by', '=', 'created_by_user.id_user')
            ->leftJoin('users as updated_by_user', 'category.updated_by', '=', 'updated_by_user.id_user')
            ->orderBy('category.id_category', 'desc')
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
            $data = new Category;
            $data->title = $this->request->title;
            $data->seotitle = $this->request->seotitle;
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
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {

        try {
            // $data = Category::we;
            $data = Category::where('id_category', $id)
                ->update([
                    'title' => $this->request->title,
                    'seotitle' => $this->request->seotitle,
                    'active' => $this->request->active,
                    'updated_on' => date('Y-m-d H:i:s'),
                    'updated_by' => $this->request->user_id,

                ]);
            return response()->json([
                'messages' => 'data berhsil di simpan',
            ]);
        } catch (\Category $th) {
            return response()->json([
                'messages' => $th->getMesage(),
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $data = Category::find($id);
            $data->delete();
            return response()->json([
                'messages' => 'data berhasil di hapus',
            ]);
        } catch (Category $th) {
            return response()->json([
                'messages' => $th->getMessage(),
            ], 500);

        }
    }
}
