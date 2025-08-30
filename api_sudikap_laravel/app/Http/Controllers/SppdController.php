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
        $jenisSppdId = $request->input('jenisppd_id') ? $request->input('jenisppd_id') : '';
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
            // 'status' => 'sometimes|string',
            // 'search' => 'sometimes|string',
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

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Mapping data dari request ke kolom tabel
            $data = [
                'pimpinan' => $request->pimpinan ?? null,
                'nip_pejabat' => $request->nip_pejabat ?? null,
                'nip_leader' => $request->nip_leader ?? null,
                'letter_code' => $request->letter_code ?? null,
                'letter_subject' => $request->letter_subject ?? null,
                'letter_about' => $request->letter_about ?? null,
                'letter_from' => $request->letter_from ?? null,
                'letter_content' => $request->letter_content ?? null,
                'letter_date' => $request->letter_date ?? null,
                'code' => $request->letter_code ?? null, // Menggunakan letter_code sebagai code
                'date' => now()->format('Y-m-d H:i:s'),
                'bawahan' => $request->bawahan ?? null,
                'atasan' => $request->atasan ?? null,
                'rate_travel' => $request->rateTravel ?? $request->rate_travel ?? null,
                'pengikut_nip' => is_array($request->pengikut) ? implode(',', $request->pengikut) : $request->pengikut,
                'purpose' => $request->travelPurpose ?? $request->purpose ?? null,
                'transport' => $request->transport ?? null,
                'place_from' => $request->departurePlace ?? $request->place_from ?? null,
                'place_to' => $request->destination ?? $request->place_to ?? null,
                'length_journey' => $request->duration ?? $request->length_journey ?? null,
                'date_go' => $request->departureDate ?? $request->date_go ?? null,
                'date_back' => $request->returnDate ?? $request->date_back ?? null,
                'government' => $request->government ?? null,
                'budget' => $request->budgetItem ?? $request->budget ?? null,
                'budget_from' => $request->budget_from ?? null,
                'description' => $request->notes ?? $request->description ?? null,
                'result_date' => $request->result_date ?? null,
                'result' => $request->result ?? null,
                'result_username' => $request->result_username ?? null,
                'file' => $request->file ?? null,
                'jenis_surat_id' => $request->category ?? $request->jenis_surat_id ?? null,
                'file_update' => $request->file_update ?? null,
                'status' => '0', // Default status: 0 (diinput)
                'username' => auth()->user()->username ?? null,
                'username_update' => $request->username_update ?? null,
                'datetime_insert' => now()->format('Y-m-d H:i:s'),
                'datetime_update' => $request->datetime_update ?? null,
                'basic' => $request->travelBasis ?? $request->basic ?? null,
                'city' => $request->originCity ?? $request->city ?? null,
                'rekening' => $request->rekening ?? null,
                'kabag' => $request->kabag ?? null,
                'kasubag' => $request->kasubag ?? null,
                'pimpinan_spt' => $request->pimpinan_spt ?? null,
                'kabag_spt' => $request->kabag_spt ?? null,
                'kasubag_spt' => $request->kasubag_spt ?? null,
                'letter_code_spt' => $request->letter_code_spt ?? null
            ];

            // Insert and get the ID
            $id = DB::table('sppd')->insertGetId($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'SPPD berhasil dibuat',
                'data' => ['id' => $id]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan SPPD',
                'error' => $e->getMessage()
            ], 500);
        }
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
            // 'letter_code' => 'sometimes|string|unique:sppds,letter_code,' . $id,
            'letter_subject' => 'sometimes|string',
            'letter_about' => 'sometimes|string',
            'letter_from' => 'sometimes|string',
            'letter_content' => 'sometimes|string',
            'letter_date' => 'sometimes|date',
            'pimpinan' => 'sometimes|string',
            'rate_travel' => 'sometimes|numeric',
            'pengikut_nip' => 'sometimes|array',
            'purpose' => 'sometimes|string',
            // 'transport' => 'sometimes|string',
            'place_from' => 'sometimes|string',
            'place_to' => 'sometimes|string',
            'date_go' => 'sometimes|date',
            'date_back' => 'sometimes|date|after_or_equal:date_go',
            'government' => 'sometimes|string',
            // 'budget' => 'sometimes|string',
            // 'budget_from' => 'sometimes|string',
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

    public function generatePdf($id)
    {
        $sppd = Sppd::find($id);

        if (!$sppd) {
            return response()->json(['message' => 'SPPD not found'], 404);
        }

        return response()->json([
            'message' => 'PDF generation would happen here',
            'data' => $sppd
        ]);
    }

    function listPengikut()
    {
        $data = DB::table("pegawai")
            ->select('nip', 'id', 'nama')
            // ->where('status', 'Aktif')
            ->get();
        return response()->json(['data' => $data, 'message' => 'success']);

    }
    function listAtasan()
    {
        $data = DB::table("pegawai")
            ->select('nip', 'id', 'nama')
            ->get();
        return response()->json(['data' => $data, 'message' => 'success']);

    }
    function instansiAnggaran()
    {
        $data = DB::table("pegawai")
            ->select('nip', 'nama')
            ->get();
        return response()->json($data);

    }

    function listPad()
    {
        $data = DB::table("sikd_satker")
            ->get();
        return response()->json(['data' => $data, 'message' => 'success']);



    }

    function transportation()
    {
        $data = DB::table("transportation")
            ->get();
        return response()->json(['data' => $data, 'message' => 'success']);

    }


    function rekeningAnggaran()
    {
        $data = DB::table("anggaran_perjalanan")
            ->select("kode_anggaran as code", "nama_kegiatan as name", "id")
            ->get();

        return response()->json([
            'data' => $data,
            'message' => 'success'
        ]);



    }

}