<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InstansiController extends Controller
{
    // Get all instansi records
    public function index()
    {
        $instansi = DB::table('instansi')->get();
        return response()->json($instansi);
    }

    // Get single instansi record
    public function show($id)
    {
        $instansi = DB::table('instansi')->where('id', $id)->first();
        
        if (!$instansi) {
            return response()->json(['message' => 'Instansi not found'], 404);
        }
        
        return response()->json($instansi);
    }

    // Create new instansi record
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_instansi' => 'required|string|max:100',
            'alamat_lengkap' => 'required|string',
            'telp' => 'required|string|max:30',
            'informasi' => 'nullable|string',
            'keterangan_situs' => 'nullable|string',
            'fax' => 'nullable|string|max:30',
            'npwp' => 'nullable|string|max:40',
            'logo' => 'nullable|string|max:50',
            'jabatan' => 'nullable|string|max:100',
            'nip' => 'nullable|string|max:30',
            'nama_pejabat' => 'nullable|string|max:100',
            'favicon' => 'nullable|string|max:40',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $id = DB::table('instansi')->insertGetId($request->all());

        return response()->json(['id' => $id, 'message' => 'Instansi created successfully'], 201);
    }

    // Update instansi record
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_instansi' => 'sometimes|string|max:100',
            'alamat_lengkap' => 'sometimes|string',
            'telp' => 'sometimes|string|max:30',
            'informasi' => 'nullable|string',
            'keterangan_situs' => 'nullable|string',
            'fax' => 'nullable|string|max:30',
            'npwp' => 'nullable|string|max:40',
            'logo' => 'nullable|string|max:50',
            'jabatan' => 'nullable|string|max:100',
            'nip' => 'nullable|string|max:30',
            'nama_pejabat' => 'nullable|string|max:100',
            'favicon' => 'nullable|string|max:40',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $affected = DB::table('instansi')
             ->update($request->all());

        if ($affected === 0) {
            return response()->json(['message' => 'Instansi not found'], 404);
        }

        return response()->json(['message' => 'Instansi updated successfully']);
    }

    // Delete instansi record
    public function destroy($id)
    {
        $affected = DB::table('instansi')->where('id', $id)->delete();

        if ($affected === 0) {
            return response()->json(['message' => 'Instansi not found'], 404);
        }

        return response()->json(['message' => 'Instansi deleted successfully']);
    }
}