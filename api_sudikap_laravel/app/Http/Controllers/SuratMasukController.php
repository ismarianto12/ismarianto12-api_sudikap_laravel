<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SuratMasukController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        try {   
            $suratMasuk = DB::table('tbl_surat_masuk')
                ->leftJoin('login', 'tbl_surat_masuk.id_user', '=', 'login.id_user')
                ->select('tbl_surat_masuk.*', 'login.nama as user_nama')
                ->paginate(10);
            
            return response()->json($suratMasuk, Response::HTTP_OK);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        try {
            $suratMasuk = DB::table('tbl_surat_masuk')
                ->leftJoin('login', 'tbl_surat_masuk.id_user', '=', 'login.id_user')
                ->select('tbl_surat_masuk.*', 'login.nama as user_nama')
                ->where('tbl_surat_masuk.id_surat', $id)
                ->first();
            
            if (!$suratMasuk) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], Response::HTTP_NOT_FOUND);
            }
            
            return response()->json([
                'success' => true,
                'data' => $suratMasuk,
                'message' => 'Detail surat masuk berhasil diambil'
            ], Response::HTTP_OK);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail data: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = $this->validateForm($request);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            
            $tgl_surat = Carbon::parse($request->tgl_surat);
            $tgl_diterima = Carbon::parse($request->tgl_diterima);
            
            $data = [
                'no_agenda' => $request->no_agenda,
                'no_surat' => $request->no_surat,
                'asal_surat' => $request->asal_surat,
                'isi' => $request->isi,
                'kode' => $request->kode,
                'indeks' => $request->indeks ?? 'null',
                'tgl_surat' => $tgl_surat->format('Y-m-d'),
                'tgl_diterima' => $tgl_diterima->format('Y-m-d'),
                'keterangan' => $request->keterangan,
                'id_user' => Auth::id(),
                'disposisi' => 'n',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            // Handle file upload
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $allowedTypes = ['pdf', 'jpg', 'png', 'ico', 'bmp', 'docx'];
                
                if (!in_array($file->getClientOriginalExtension(), $allowedTypes)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tipe file tidak diizinkan'
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                }
                
                $filename = time() . '_file_surat.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('public/file_surat', $filename);
                $data['file'] = $filename;
            }
            
            $id = DB::table('tbl_surat_masuk')->insertGetId($data);
            
            $suratMasuk = DB::table('tbl_surat_masuk')
                ->leftJoin('login', 'tbl_surat_masuk.id_user', '=', 'login.id_user')
                ->select('tbl_surat_masuk.*', 'login.nama as user_nama')
                ->where('tbl_surat_masuk.id_surat', $id)
                ->first();
            
            return response()->json([
                'success' => true,
                'data' => $suratMasuk,
                'message' => 'Data berhasil ditambahkan'
            ], Response::HTTP_CREATED);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan data: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = $this->validateForm($request);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            
            $suratMasuk = DB::table('tbl_surat_masuk')->where('id_surat', $id)->first();
            
            if (!$suratMasuk) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], Response::HTTP_NOT_FOUND);
            }
            
            $tgl_surat = Carbon::parse($request->tgl_surat);
            $tgl_diterima = Carbon::parse($request->tgl_diterima);
            
            $data = [
                'no_agenda' => $request->no_agenda,
                'no_surat' => $request->no_surat,
                'asal_surat' => $request->asal_surat,
                'isi' => $request->isi,
                'kode' => $request->kode,
                'indeks' => $request->indeks ?? 'null',
                'tgl_surat' => $tgl_surat->format('Y-m-d'),
                'tgl_diterima' => $tgl_diterima->format('Y-m-d'),
                'keterangan' => $request->keterangan,
                'id_user' => Auth::id(),
                'updated_at' => now(),
            ];
            
            // Handle file upload
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $allowedTypes = ['docx', 'pdf', 'jpg', 'png', 'ico', 'bmp'];
                
                if (!in_array($file->getClientOriginalExtension(), $allowedTypes)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tipe file tidak diizinkan'
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                }
                
                // Delete old file if exists
                if ($suratMasuk->file && Storage::exists('public/file_surat/' . $suratMasuk->file)) {
                    Storage::delete('public/file_surat/' . $suratMasuk->file);
                }
                
                $filename = time() . '_file_surat.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('public/file_surat', $filename);
                $data['file'] = $filename;
            }
            
            DB::table('tbl_surat_masuk')->where('id_surat', $id)->update($data);
            
            $updatedSuratMasuk = DB::table('tbl_surat_masuk')
                ->leftJoin('login', 'tbl_surat_masuk.id_user', '=', 'login.id_user')
                ->select('tbl_surat_masuk.*', 'login.nama as user_nama')
                ->where('tbl_surat_masuk.id_surat', $id)
                ->first();
            
            return response()->json([
                'success' => true,
                'data' => $updatedSuratMasuk,
                'message' => 'Data berhasil diupdate'
            ], Response::HTTP_OK);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate data: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id)
    {
        try {
            $suratMasuk = DB::table('tbl_surat_masuk')->where('id_surat', $id)->first();
            
            if (!$suratMasuk) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], Response::HTTP_NOT_FOUND);
            }
            
            // Delete file if exists
            if ($suratMasuk->file && Storage::exists('public/file_surat/' . $suratMasuk->file)) {
                Storage::delete('public/file_surat/' . $suratMasuk->file);
            }
            
            DB::table('tbl_surat_masuk')->where('id_surat', $id)->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ], Response::HTTP_OK);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getDetail(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_suratmasuk' => 'required|exists:tbl_surat_masuk,id_surat'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            
            $id_surat = $request->id_suratmasuk;
            $data = DB::table('tbl_surat_masuk')
                ->leftJoin('login', 'tbl_surat_masuk.id_user', '=', 'login.id_user')
                ->select('tbl_surat_masuk.*', 'login.nama as user_nama')
                ->where('tbl_surat_masuk.id_surat', $id_surat)
                ->first();
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Detail data berhasil diambil'
            ], Response::HTTP_OK);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail data: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getNotification()
    {
        try {
            $count = DB::table('tbl_surat_masuk')
                ->where('disposisi', 'n')
                ->count();
            
            return response()->json([
                'success' => true,
                'data' => ['jumlah' => $count],
                'message' => 'Data notifikasi berhasil diambil'
            ], Response::HTTP_OK);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil notifikasi: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getListNotification()
    {
        try {
            $data = DB::table('tbl_surat_masuk')
                ->leftJoin('login', 'tbl_surat_masuk.id_user', '=', 'login.id_user')
                ->select('tbl_surat_masuk.*', 'login.nama as user_nama')
                ->where('disposisi', 'n')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'List notifikasi berhasil diambil'
            ], Response::HTTP_OK);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil list notifikasi: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function checkNoSurat(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_jenis_surat' => 'required|exists:jenis_surat,id_jenis'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            
            $id_jenis_surat = $request->id_jenis_surat;
            $jenisSurat = DB::table('jenis_surat')
                ->where('id_jenis', $id_jenis_surat)
                ->first();
            
            if ($jenisSurat && $jenisSurat->kode_surat != '') {
                $no_surat = $jenisSurat->kode_surat . '-' . $this->penomoranSuratMasuk();
                $keterangan = '';
            } else {
                $no_surat = $this->penomoranSuratMasuk();
                $keterangan = 'Kode untuk jenis surat ini belum di set sebelumnya.';
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'no_surat' => $no_surat,
                    'keterangan' => $keterangan
                ],
                'message' => 'Nomor surat berhasil digenerate'
            ], Response::HTTP_OK);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal generate nomor surat: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function validateForm(Request $request)
    {
        return Validator::make($request->all(), [
            'no_agenda' => 'required|string|max:100',
            'no_surat' => 'required|string|max:100',
            'asal_surat' => 'required|string|max:200',
            'isi' => 'required|string',
            'kode' => 'required|string|max:50',
            'tgl_surat' => 'required|date',
            'tgl_diterima' => 'required|date',
            'keterangan' => 'required|string|max:255',
            'file' => 'nullable|file',
            'indeks' => 'nullable|string|max:100'
        ]);
    }

    private function penomoranSuratMasuk()
    {
        // Implement your penomoran logic here
        $currentYear = date('Y');
        $currentMonth = date('m');
        
        $lastSurat = DB::table('tbl_surat_masuk')
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->orderBy('id_surat', 'desc')
            ->first();
            
        $sequence = $lastSurat ? intval(substr($lastSurat->no_surat, -4)) + 1 : 1;
        
        return $currentYear . $currentMonth . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}