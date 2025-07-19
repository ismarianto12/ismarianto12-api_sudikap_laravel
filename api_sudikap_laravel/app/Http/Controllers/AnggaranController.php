<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AnggaranController extends Controller
{
    // Get all anggaran perjalanan records
    public function index()
    {
        $anggaran = DB::table('anggaran_perjalanan')->paginate(10);
        return response()->json($anggaran);
    }

    // Get single anggaran perjalanan record
    public function show($id) 
    {
        $anggaran = DB::table('anggaran_perjalanan')->where('id', $id)->first();

        if (!$anggaran) {
            return response()->json(['message' => 'Anggaran perjalanan not found'], 404);
        }

        return response()->json($anggaran);
    }

    // Create new anggaran perjalanan record
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'kode_anggaran' => 'required|string|max:50|unique:anggaran_perjalanan,kode_anggaran',
            'nama_kegiatan' => 'required|string',
            'tahun_anggaran' => 'required|integer|min:2000|max:' . (date('Y') + 5),
            'pagu_anggaran' => 'required|numeric|min:0',
            'sisa_anggaran' => 'sometimes|numeric|min:0',
            // 'status' => 'required|in:aktif,non-aktif'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Set sisa_anggaran equal to pagu_anggaran if not provided
        $data = $request->all();
        if (!isset($data['sisa_anggaran'])) {
            $data['sisa_anggaran'] = $data['pagu_anggaran'];
        }

        $id = DB::table('anggaran_perjalanan')->insertGetId($data);

        return response()->json(['id' => $id, 'message' => 'Anggaran perjalanan created successfully'], 201);
    }

    // Update anggaran perjalanan record
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode_anggaran' => 'sometimes|string|max:50|unique:anggaran_perjalanan,kode_anggaran,' . $id,
            'nama_kegiatan' => 'sometimes|string',
            'tahun_anggaran' => 'sometimes|integer|min:2000|max:' . (date('Y') + 5),
            'pagu_anggaran' => 'sometimes|numeric|min:0',
            'sisa_anggaran' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:aktif,non-aktif'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $affected = DB::table('anggaran_perjalanan')
            ->where('id', $id)
            ->update($request->all());

        if ($affected === 0) {
            return response()->json(['message' => 'Anggaran perjalanan not found'], 404);
        }

        return response()->json(['message' => 'Anggaran perjalanan updated successfully']);
    }

    // Delete anggaran perjalanan record
    public function destroy($id)
    {
        $affected = DB::table('anggaran_perjalanan')->where('id', $id)->delete();

        if ($affected === 0) {
            return response()->json(['message' => 'Anggaran perjalanan not found'], 404);
        }

        return response()->json(['message' => 'Anggaran perjalanan deleted successfully']);
    }

    // Get active anggaran perjalanan
    public function aktif()
    {
        $anggaran = DB::table('anggaran_perjalanan')
            ->where('status', 'aktif')
            ->get();

        return response()->json($anggaran);
    }

    // Update sisa anggaran (usually called when creating/updating SPPD)
    public function updateSisaAnggaran($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jumlah' => 'required|numeric|min:0',
            'operation' => 'required|in:tambah,kurang'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $anggaran = DB::table('anggaran_perjalanan')->where('id', $id)->first();

        if (!$anggaran) {
            return response()->json(['message' => 'Anggaran perjalanan not found'], 404);
        }

        $newSisa = $anggaran->sisa_anggaran;

        if ($request->operation === 'tambah') {
            $newSisa += $request->jumlah;
        } else {
            $newSisa -= $request->jumlah;
        }

        // Validate if sisa anggaran is not negative
        if ($newSisa < 0) {
            return response()->json(['message' => 'Sisa anggaran tidak boleh negatif'], 400);
        }

        // Validate if sisa anggaran doesn't exceed pagu anggaran when adding
        if ($request->operation === 'tambah' && $newSisa > $anggaran->pagu_anggaran) {
            return response()->json(['message' => 'Sisa anggaran tidak boleh melebihi pagu anggaran'], 400);
        }

        DB::table('anggaran_perjalanan')
            ->where('id', $id)
            ->update(['sisa_anggaran' => $newSisa]);

        return response()->json(['message' => 'Sisa anggaran updated successfully']);
    }
}