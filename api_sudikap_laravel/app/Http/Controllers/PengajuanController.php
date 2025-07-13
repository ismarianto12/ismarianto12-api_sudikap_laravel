<?php

namespace App\Http\Controllers;

use App\Models\PengajuanArsip;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class PengajuanController extends Controller
{
    public function index(Request $request)
    {
        // Pagination
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);

        // Sorting
        $sortBy = $request->input('sort_by', 'id_pengajuan');
        $sortOrder = $request->input('sort_order', 'asc');

        // Searching
        $search = $request->input('search', '');

        $query = DB::table('pengajuan_arsip');

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('nama_arsip', 'like', "%{$search}%")
                    ->orWhere('tujuan', 'like', "%{$search}%");
            });
        }

        $total = $query->count();
        $pengajuanArsips = $query->orderBy($sortBy, $sortOrder)
            ->offset(($page - 1) * $perPage)
            ->limit($perPage)->orderBy('id_pengajuan', 'desc')
            ->get();

        return response()->json([
            'data' => $pengajuanArsips,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
        ]);
    }

    public function store(Request $request)
    {

        $user = JWTAuth::parseToken()->authenticate();
        $request->validate([
            'jumlah' => 'required|integer',
            'nama_arsip' => 'required|string|max:255',
        ]);
        try {
            DB::table('pengajuan_arsip')->insert([
                'id_pejabat' => $user->id_user,
                'id_satuan' => $request->kodesatuan,
                'nama_arsip' => $request->nama_arsip,
                'jumlah' => $request->jumlah,
                'satuan' => $request->satuan,
                'tanggal' => Carbon::now()->format("Y-m-d"),
                'tujuan' => $request->tujuan,
                // 'file_arsip' => $request->file_arsip,
                'id_jenis' => $request->jenis_id,
                'nonaktif' => 2,

            ]);
            return response()->json(['messages' => 'data brehasild simpan', 'status' => "berhasil"]);
        } catch (ValidationException $e) {
            return response()->json(['messages' => $e->errors(), 'status' => $user], 422);
        } catch (\Exception $e) {
            return response()->json(['messages' => $e->getMessage(), 'status' => $user->id_user], 400);
        }
    }

    public function show($id)
    {
        $pengajuanArsip = DB::table('pengajuan_arsips')->where('id_pengajuan', $id)->first();

        if (!$pengajuanArsip) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        return response()->json($pengajuanArsip);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'id_pejabat' => 'integer',
            'id_satuan' => 'integer',
            'nama_arsip' => 'string|max:255',
            'jumlah' => 'integer',
            'satuan' => 'string|max:50',
            'tanggal' => 'date',
            'tujuan' => 'string|max:255',
            'file_arsip' => 'string|max:255',
            'id_jenis' => 'integer',
            'nonaktif' => 'boolean',
        ]);

        $pengajuanArsip = DB::table('pengajuan_arsip')->where('id_pengajuan', $id)->update($validatedData);

        if (!$pengajuanArsip) {
            return response()->json(['message' => 'Record not found or no changes made'], 404);
        }

        return response()->json(['message' => 'Record updated successfully']);
    }

    public function destroy($id)
    {
        $pengajuanArsip = DB::table('pengajuan_arsip')->where('id_pengajuan', $id)->delete();
        if (!$pengajuanArsip) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        return response()->json(['message' => 'Record deleted successfully']);
    }
}
