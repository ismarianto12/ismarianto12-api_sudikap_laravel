<?php

namespace App\Http\Controllers;

use App\Models\struktur as Struktur;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class StrukturController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function index()
    {
        $data = struktur::select(
            'struktur_korporasi.id',
            'struktur_korporasi.nama',
            'struktur_korporasi.jabatan',
            'struktur_korporasi.bagian',
            'struktur_korporasi.riwayat_en',
            'struktur_korporasi.riwayat_idn',
            'struktur_korporasi.picture',
            'struktur_korporasi.updated_at',
            'created_by_user.nama_lengkap as created_by',
            'updated_by_user.nama_lengkap as updated_by',
            DB::raw('DATE_FORMAT(struktur_korporasi.created_on,"%d-%M-%Y %H:%m") as created_on'),
            DB::raw('DATE_FORMAT(struktur_korporasi.updated_on,"%d-%M-%Y %H:%m") as updated_on'),

        )
            ->leftJoin('users as created_by_user', 'struktur_korporasi.created_by', '=', 'created_by_user.id_user')
            ->leftJoin('users as updated_by_user', 'struktur_korporasi.updated_by', '=', 'updated_by_user.id_user')
            ->orderBy('struktur_korporasi.id', 'desc')
            ->get();
        return response()->json($data);
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
        try {

            $date = Carbon::now()->format('y-m-d h:i:s');
            $gambar = $this->request->file('picture');
            $filename = 'halaman_Struktur' . rand() . '.' . $gambar->getClientOriginalExtension();
            $gambar->move('./struktur/', $filename);
            $data = new Struktur();
            $data->nama = $this->request->nama;
            $data->jabatan = $this->request->jabatan;
            $data->bagian = $this->request->bagian ? $this->request->bagian : '';
            $data->riwayat_en = $this->request->riwayat_en ? $this->request->riwayat_en : '';
            $data->riwayat_idn = $this->request->riwayat_idn ? $this->request->riwayat_idn : '';
            $data->picture = $filename;
            $data->created_by = $this->request->user_id;
            $data->created_on = date('Y-m-d H:i:s');

            $data->save();
            return response()->json([
                'status' => 'ok', 'messages' => 'databerhasil di tambahkan',
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error event insert data',
                'errorcode' => 'error code' . $th,
            ], 500);
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
     * @param  \App\Models\Struktur  $Struktur
     * @return \Illuminate\Http\Response
     */
    public function show(Struktur $Struktur)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Struktur  $Struktur
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
        $data = Struktur::where('id', $id)->first();
        return response()->json($data);
    }
    public function update($id)
    {
        try {
            $date = Carbon::now()->format('y-m-d h:i:s');
            $gambar = $this->request->file('picture');
            $data = Struktur::find($id);
            if ($this->request->file('picture')) {
                $filename = 'halaman_Struktur' . rand() . '.' . $gambar->getClientOriginalExtension();
                @unlink('./struktur/' . $data->picture);
                $gambar->move('./struktur/', $filename);
            } else {
                $filename = $data->picture;
            }
            $data->nama = $this->request->nama;
            $data->jabatan = $this->request->jabatan;
            $data->bagian = $this->request->bagian;
            $data->riwayat_en = $this->request->riwayat_en ? $this->request->riwayat_en : '';
            $data->riwayat_idn = $this->request->riwayat_idn ? $this->request->riwayat_idn : '';
            $data->picture = $filename;
            $data->updated_on = date('Y-m-d H:i:s');
            $data->updated_by = $this->request->user_id;

            $data->save();
            return response()->json([
                'status' => 'ok', 'messages' => 'databerhasil di updata',
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'Gagal proses insert data',
                'errorcode' => 'error code' . $th,
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Struktur  $Struktur
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $data = Struktur::find($id);
            // @unlink(public_path() . '/struktur', $data->picture);
            $data->delete();
            return response()->json([
                'status' => 'ok',
                'messages' => 'berhasil',
            ]);
        } catch (\Struktur $th) {
            return response()->json([
                'status' => 'gagal',
                'messages' => $th,
            ],500);
        }
    }
}
