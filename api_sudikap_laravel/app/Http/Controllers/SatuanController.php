<?php

namespace App\Http\Controllers;

use App\Models\satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SatuanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        $search = $request->input('search');
        $sortBy = $request->input('sortBy', 'id_satuan'); // Default sort by id_satuan
        $sortOrder = $request->input('sortOrder', 'asc'); // Default sort order asc
        // Membuat query
        $query = DB::table('m_satuan')
            ->select('id_satuan', 'nama_satuan', 'keterangan')
            ->when($search, function ($query, $search) {
                return $query->where('nama_satuan', 'like', "%{$search}%")
                    ->orWhere('keterangan', 'like', "%{$search}%");
            })
            ->orderBy($sortBy, $sortOrder);

        // Paginate 10 results per page
        $satuans = $query->paginate(10);
        return response()->json($satuans);
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
        $request->validate([
            'nama_satuan' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        DB::table('m_satuan')->insert([
            'nama_satuan' => $request->input('nama_satuan'),
            'keterangan' => $request->input('keterangan'),
        ]);
        return response()->json(['data berhasil disimpan']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\satuan  $satuan
     * @return \Illuminate\Http\Response
     */
    public function show(satuan $satuan, $id)
    {
        $satuan = DB::table('m_satuan')->where('id_satuan', $id)->first();
        return response()->json(['data' => $satuan], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\satuan  $satuan
     * @return \Illuminate\Http\Response
     */
    public function edit(satuan $satuan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\satuan  $satuan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {

            $request->validate([
                'nama_satuan' => 'required|string|max:255',
                'keterangan' => 'nullable|string',
            ]);

            $satuan = DB::table('m_satuan')->where('id_satuan', $id)->first();

            if (!$satuan) {
                return response()->json(['message' => 'Data tidak ditemukan'], 404);
            }

            DB::table('m_satuan')->where('id_satuan', $id)->update([
                'nama_satuan' => $request->input('nama_satuan'),
                'keterangan' => $request->input('keterangan'),
            ]);
            return response()->json(['message' => 'Data satuan berhasil diperbarui'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\satuan  $satuan
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $satuan = DB::table('m_satuan')->where('id_satuan', $id)->first();

        if (!$satuan) {
            abort(404, 'Data tidak ditemukan');
        }

        DB::table('m_satuan')->where('id_satuan', $id)->delete();
        return response()->json(['message' => 'Data satuan berhasil diperbarui'], 200);
    }
}
