<?php

namespace App\Http\Controllers;

use App\Models\Sppd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SppdController extends Controller
{ 

    public function getSppdData(Request $request)
    {
        $jenisSppdId = $request->input('jenisppd_id');
        $query = DB::table('sppd')
            ->select([
                'sppd.id as sppd_id',
                DB::raw("(SELECT GROUP_CONCAT(pegawai.nama) from pegawai where FIND_IN_SET(pegawai.nip, sppd.pengikut_nip) > 0) AS pengikut"),
                'sppd.pimpinan',
                'a.nama',
                'sppd.letter_code',
                'sppd.letter_subject',
                'sppd.letter_about',
                'sppd.letter_from',
                'sppd.letter_content',
                'sppd.letter_date',
                'sppd.code',
                'sppd.date',
                'sppd.bawahan',
                'sppd.atasan',
                'sppd.rate_travel',
                'sppd.pengikut_nip',
                'sppd.purpose',
                'sppd.transport',
                'sppd.place_from',
                'sppd.place_to',
                'sppd.length_journey',
                'sppd.date_go',
                'sppd.date_back',
                'sppd.government',
                'sppd.budget',
                'sppd.budget_from',
                'sppd.description',
                'sppd.result_date',
                'sppd.result',
                'sppd.result_username',
                'sppd.file',
                'sppd.jenis_surat_id',
                'sppd.file_update',
                'sppd.status',
                'sppd.username',
                'sppd.username_update',
                'sppd.datetime_insert',
                'sppd.datetime_update',
                'sppd.basic',
                'sppd.city',
                'sppd.rekening',
                'sppd.kabag',
                'sppd.kasubag',
                'sppd.pimpinan_spt',
                'sppd.kabag_spt',
                'sppd.kasubag_spt',
                'sppd.letter_code_spt'
            ])
            ->leftJoin('pegawai as a', 'a.nip', '=', 'sppd.bawahan')
            ->leftJoin('pegawai as b', 'b.nip', '=', 'sppd.pimpinan')
            ->leftJoin('jenis_surat as c', 'sppd.jenis_surat_id', '=', 'c.id_jenis');

        if ($jenisSppdId) {
            $query->where('sppd.jenis_surat_id', $jenisSppdId);
        }
 
        return datatables()->of($query)
            ->addColumn('action', function ($row) {
                return '
                <a href="#" data-id="' . $row->sppd_id . '" data-judul="' . strtoupper($row->sppd_id) . '" data-idp="' . $row->sppd_id . '" class="btn btn-info btn-xs delete" id="konfirmasi">
                    <i class="fa fa-print"></i> Print
                </a>
                <a href="" class="btn btn-success btn-xs edit">
                    <i class="fa fa-edit"></i> Update
                </a>
                <a href="#" class="btn btn-danger btn-xs delete" id="delete" data-id="' . $row->sppd_id . '">
                    <i class="fa fa-trash"></i> Delete
                </a>
            ';
            })
            ->rawColumns(['action'])
            ->toJson();
    }


    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'per_page' => 'sometimes|integer|min:1|max:100',
            'page' => 'sometimes|integer|min:1',
            'sort_by' => 'sometimes|string|in:id,letter_code,letter_date,date_go,date_back,status',
            'sort_order' => 'sometimes|string|in:asc,desc',
            'status' => 'sometimes|string',
            'search' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $query = Sppd::query();

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('letter_code', 'like', "%{$search}%")
                    ->orWhere('letter_subject', 'like', "%{$search}%")
                    ->orWhere('purpose', 'like', "%{$search}%")
                    ->orWhere('place_to', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->sort_by ?? 'id';
        $sortOrder = $request->sort_order ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->per_page ?? 10;
        $sppds = $query->paginate($perPage);

        return response()->json($sppds);
    }

    // Create new SPPD
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'letter_code' => 'required|string|unique:sppds,letter_code',
            'letter_subject' => 'required|string',
            'letter_about' => 'required|string',
            'letter_from' => 'required|string',
            'letter_content' => 'required|string',
            'letter_date' => 'required|date',
            'pimpinan' => 'required|string',
            'rate_travel' => 'required|numeric',
            'pengikut_nip' => 'required|array',
            'purpose' => 'required|string',
            'transport' => 'required|string',
            'place_from' => 'required|string',
            'place_to' => 'required|string',
            'date_go' => 'required|date',
            'date_back' => 'required|date|after_or_equal:date_go',
            'government' => 'required|string',
            'budget' => 'required|string',
            'budget_from' => 'required|string',
            'description' => 'required|string',
            'jenis_surat_id' => 'required|exists:jenis_surats,id',
            'basic' => 'nullable|string',
            'city' => 'nullable|string',
            'rekening' => 'nullable|string',
            'kabag' => 'nullable|string',
            'kasubag' => 'nullable|string',
            'pimpinan_spt' => 'nullable|string',
            'kabag_spt' => 'nullable|string',
            'kasubag_spt' => 'nullable|string',
            'letter_code_spt' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();
        $data['pengikut_nip'] = implode(',', $request->pengikut_nip);
        $data['code'] = $request->letter_code;
        $data['date'] = now()->format('Y-m-d');
        $data['username'] = $request->user()->username; // Assuming authenticated user

        $sppd = Sppd::create($data);

        return response()->json($sppd, 201);
    }

    // Get single SPPD
    public function show($id)
    {
        $sppd = Sppd::find($id);

        if (!$sppd) {
            return response()->json(['message' => 'SPPD not found'], 404);
        }

        return response()->json($sppd);
    }

    // Update SPPD
    public function update(Request $request, $id)
    {
        $sppd = Sppd::find($id);

        if (!$sppd) {
            return response()->json(['message' => 'SPPD not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'letter_code' => 'sometimes|string|unique:sppds,letter_code,' . $id,
            'letter_subject' => 'sometimes|string',
            'letter_about' => 'sometimes|string',
            'letter_from' => 'sometimes|string',
            'letter_content' => 'sometimes|string',
            'letter_date' => 'sometimes|date',
            'pimpinan' => 'sometimes|string',
            'rate_travel' => 'sometimes|numeric',
            'pengikut_nip' => 'sometimes|array',
            'purpose' => 'sometimes|string',
            'transport' => 'sometimes|string',
            'place_from' => 'sometimes|string',
            'place_to' => 'sometimes|string',
            'date_go' => 'sometimes|date',
            'date_back' => 'sometimes|date|after_or_equal:date_go',
            'government' => 'sometimes|string',
            'budget' => 'sometimes|string',
            'budget_from' => 'sometimes|string',
            'description' => 'sometimes|string',
            'jenis_surat_id' => 'sometimes|exists:jenis_surats,id',
            'basic' => 'nullable|string',
            'city' => 'nullable|string',
            'rekening' => 'nullable|string',
            'kabag' => 'nullable|string',
            'kasubag' => 'nullable|string',
            'pimpinan_spt' => 'nullable|string',
            'kabag_spt' => 'nullable|string',
            'kasubag_spt' => 'nullable|string',
            'letter_code_spt' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();

        if ($request->has('pengikut_nip')) {
            $data['pengikut_nip'] = implode(',', $request->pengikut_nip);
        }

        if ($request->has('letter_code')) {
            $data['code'] = $request->letter_code;
        }

        $data['username_update'] = $request->user()->username; // Assuming authenticated user
        $data['datetime_update'] = now();

        $sppd->update($data);

        return response()->json($sppd);
    }

    // Delete SPPD
    public function destroy($id)
    {
        $sppd = Sppd::find($id);

        if (!$sppd) {
            return response()->json(['message' => 'SPPD not found'], 404);
        }

        $sppd->delete();

        return response()->json(['message' => 'SPPD deleted successfully']);
    }

    // Get employees for selection (similar to selectPegawai in original)
    public function getEmployees(Request $request)
    {
        $query = DB::table('pegawai')->select('*');

        if ($request->has('atasan')) {
            $query->where('nip', '!=', $request->atasan);
        }

        $employees = $query->get();

        return response()->json($employees);
    }

    // Generate PDF (similar to cetakdta in original)
    public function generatePdf($id)
    {
        $sppd = Sppd::find($id);

        if (!$sppd) {
            return response()->json(['message' => 'SPPD not found'], 404);
        }

        // In a real implementation, you would generate a PDF here
        // For example using barryvdh/laravel-dompdf or laravel-snappy

        return response()->json([
            'message' => 'PDF generation would happen here',
            'data' => $sppd
        ]);
    }
}