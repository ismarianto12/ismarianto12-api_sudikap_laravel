<?php

namespace App\Http\Controllers;

use App\Models\post;
use App\Models\Promosi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PromosiController extends Controller
{

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function index()
    {
        $tanggalSekarang = date('Y-m-d');
        $data = Promosi::
            select(
            'promo.id',
            'promo.titleId',
            'promo.seotitle',
            'promo.titleEn',
            'promo.deskripsiId',
            'promo.deskripsiEn',
            'promo.filethumnaild',
            'promo.imagepopup',
            'promo.imageheader',
            'promo.document1',
            'promo.document2',
            'promo.linkvideo',
            'promo.created_on',
            'promo.updated_on',
            'promo.linkpromo1',
            'promo.linkpromo2',
            'promo.masaberlaku',
            'created_by_user.nama_lengkap as created_by',
            'updated_by_user.nama_lengkap as updated_by',
            'promo.active',
        )
            ->orderBy('id', 'desc')
            ->leftJoin('users as created_by_user', 'promo.created_by', '=', 'created_by_user.id_user')
            ->leftJoin('users as updated_by_user', 'promo.updated_by', '=', 'updated_by_user.id_user')
            ->orderBy('promo.id', 'desc')
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
    }
    private function getYouTubeVideoId($url)
    {
        // Parsing URL
        $urlParts = parse_url($url);
        $queryString = isset($urlParts['query']) ? $urlParts['query'] : '';
        parse_str($queryString, $queryParameters);
        $videoId = isset($queryParameters['v']) ? $queryParameters['v'] : '';

        return $videoId;
    }

    public function store(Request $request)
    {
        try {
            $dataseotitle = $this->createSeoUrl($this->request->titleID);
            $checkPromo = Promosi::where('seotitle', $dataseotitle)->get()->count();
            if ($checkPromo > 0) {
                return response()->json([
                    'messages' => 'data promosi sudah tersedia.',
                ], 400);
            }
            $date = Carbon::now()->format('y-m-d h:i:s');
            $gambar = $this->request->file('filethumnaild');
            if ($gambar) {
                $ffilethumnaild = 'popupfile' . rand() . '.' . $gambar->getClientOriginalExtension();
                $gambar->move('./public/promosi/filethumnaild', $ffilethumnaild);
            }
            $fimagepopup = $this->request->file('imagepopup');
            if ($fimagepopup) {
                $imagepopup = 'popupfile' . rand() . '.' . $fimagepopup->getClientOriginalExtension();
                $fimagepopup->move('./public/promosi/imagepopup', $imagepopup);
            }
            $imgaeheader = $this->request->file('imageheader');
            if ($imgaeheader) {
                $fimageheader = 'popupfile' . rand() . '.' . $imgaeheader->getClientOriginalExtension();
                $imgaeheader->move('./public/promosi/imageheader', $fimageheader);
            }

            $document1 = $this->request->file('document1');
            if ($document1) {
                $fdocument1 = 'artikel_file' . rand() . '.' . $document1->getClientOriginalExtension();
                $document1->move('./public/promosi/document1', $fdocument1);
            } else {
                $fdocument1 = '';
            }
            $document2 = $this->request->file('document2');
            if ($document2) {
                $fdocument2 = 'documtn_dua' . rand() . '.' . $document2->getClientOriginalExtension();
                $document2->move('./public/promosi/document2', $fdocument2);
            } else {
                $fdocument2 = '';
            }

            $document3 = $this->request->file('document3');
            if ($document3) {
                $fdocument3 = 'documtn_tiga' . rand() . '.' . $document3->getClientOriginalExtension();
                $document3->move('./public/promosi/document3', $fdocument3);
            } else {
                $fdocument3 = '';
            }

            $document4 = $this->request->file('document4');
            if ($document4) {
                $fdocument4 = 'documtn_tiga' . rand() . '.' . $document4->getClientOriginalExtension();
                $document4->move('./public/promosi/document4', $fdocument4);
            } else {
                $fdocument4 = '';
            }

            $document5 = $this->request->file('document5');
            if ($document5) {
                $fdocument5 = 'documtn_lima' . rand() . '.' . $document5->getClientOriginalExtension();
                $document5->move('./public/promosi/document5', $fdocument5);
            } else {
                $fdocument5 = '';
            }

            $document6 = $this->request->file('document6');
            if ($document6) {
                $fdocument6 = 'documen_enam' . rand() . '.' . $document6->getClientOriginalExtension();
                $document6->move('./public/promosi/document6', $fdocument6);
            } else {
                $fdocument6 = '';
            }

            $data = new Promosi;
            $data->titleId = $this->request->titleID;
            $data->titleEn = $this->request->titleEn;
            $data->deskripsiId = $this->request->deskripsiId;
            $data->deskripsiEn = $this->request->deskripsiEn;
            $data->seotitle = $dataseotitle;
            $data->filethumnaild = $ffilethumnaild;
            $data->imagepopup = $fimagepopup;
            $data->imageheader = $fimageheader;

            $data->document1 = $fdocument1 ? $fdocument1 : '';
            $data->document2 = $fdocument2 ? $fdocument2 : '';
            $data->document3 = $fdocument3 ? $fdocument3 : '';
            $data->document4 = $fdocument4 ? $fdocument4 : '';
            $data->document5 = $fdocument5 ? $fdocument5 : '';
            $data->document6 = $fdocument6 ? $fdocument6 : '';

            $data->masaberlaku = $this->request->masaberlaku;

            $data->pdari = $this->request->pdari;
            $data->psampai = $this->request->psampai;

            $data->textdocument1 = isset($this->request->textdocument1) && $this->request->textdocument1 !== 'null' && $this->request->textdocument1 !== 'undefined' ? $this->request->textdocument1 : '';
            $data->textdocument2 = isset($this->request->textdocument2) && $this->request->textdocument2 !== 'null' && $this->request->textdocument2 !== 'undefined' ? $this->request->textdocument2 : '';
            $data->textdocument3 = isset($this->request->textdocument3) && $this->request->textdocument3 !== 'null' && $this->request->textdocument3 !== 'undefined' ? $this->request->textdocument3 : '';
            $data->textdocument4 = isset($this->request->textdocument4) && $this->request->textdocument4 !== 'null' && $this->request->textdocument4 !== 'undefined' ? $this->request->textdocument4 : '';
            $data->textdocument5 = isset($this->request->textdocument5) && $this->request->textdocument5 !== 'null' && $this->request->textdocument5 !== 'undefined' ? $this->request->textdocument5 : '';
            $data->textdocument6 = isset($this->request->textdocument6) && $this->request->textdocument6 !== 'null' && $this->request->textdocument6 !== 'undefined' ? $this->request->textdocument6 : '';

            $data->linkpromo1 = $this->request->linkpromo1 ? $this->request->linkpromo1 : '';
            $data->linkpromo2 = $this->request->linkpromo2 ? $this->request->linkpromo2 : '';

            $data->linkvideo = $this->getYouTubeVideoId($this->request->linkvideo);
            $data->created_at = $date;
            $data->updated_at = $date;
            $data->created_by = $this->request->user_id;
            $data->created_on = date('Y-m-d H:i:s');

            $data->save();

            return response()->json([
                'status' => 'ok', 'messages' => 'databerhasil di tambahkan',
            ]);

        } catch (Promosi $th) {
            return response()->json([
                'status' => 'error event insert data',
                'errorcode' => 'error code' . $th,
            ], 400);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Promosi::find($id);
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Promosi::find($id);
        return response()->json($data);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\post  $post
     * @return \Illuminate\Http\Response
     */
    public function createSeoUrl($string)
    {
        $string = strtolower($string); // Convert to lowercase
        $string = preg_replace('/[^a-z0-9\-]/', '-', $string); // Replace non-alphanumeric characters with hyphens
        $string = preg_replace('/-+/', '-', $string); // Replace multiple hyphens with a single hyphen
        $string = trim($string, '-');
        return $string;
    }
    public function update($id)
    {
        try {

            $data = Promosi::find($id);
            $date = Carbon::now()->format('y-m-d h:i:s');
            $filethumnaild = $this->request->file('filethumnaild');
            if ($this->request->file('filethumnaild')) {
                @unlink(public_path('public/promosi/filethumnaild/', $data->filethumnaild));
                $ffilethumnaild = 'artikel_file' . rand() . '.' . $filethumnaild->getClientOriginalExtension();
                $filethumnaild->move('./public/promosi/filethumnaild', $ffilethumnaild);
            } else {
                $ffilethumnaild = $data->filethumnaild;
            }
            if ($this->request->file('imagepopup')) {
                $gambar = $this->request->file('imagepopup');
                @unlink(public_path('public/promosi/imagepopup/', $data->imagepopup));
                $fimagepopup = 'artikel_file' . rand() . '.' . $gambar->getClientOriginalExtension();
                $gambar->move('./public/promosi/imagepopup', $fimagepopup);
            } else {
                $fimagepopup = $data->imagepopup;
            }
            if ($this->request->file('imageheader')) {
                $gambar = $this->request->file('imageheader');
                @unlink(public_path('public/promosi/imageheader/', $data->imageheader));
                $fimageheader = 'artikel_file' . rand() . '.' . $gambar->getClientOriginalExtension();
                $gambar->move('./public/promosi/imageheader', $fimageheader);
            } else {
                $fimageheader = $data->imageheader;

            }
            if ($this->request->file('document1')) {
                $gambar = $this->request->file('document1');

                @unlink(public_path('public/promosi/document1/', $data->document1));
                $fdocument1 = 'artikel_file' . rand() . '.' . $gambar->getClientOriginalExtension();
                $gambar->move('./public/promosi/document1', $fdocument1);
            } else {
                $fdocument1 = $data->document1;
            }
            if ($this->request->file('document2')) {
                $gambar = $this->request->file('document2');
                @unlink(public_path('public/promosi/document2/', $data->document2));
                $fdocument2 = 'artikel_file' . rand() . '.' . $gambar->getClientOriginalExtension();
                $gambar->move('./public/promosi/document2', $fdocument2);
            } else {
                $fdocument2 = $data->document2;
            }

            if ($this->request->file('document3')) {
                $gambar = $this->request->file('document3');
                @unlink(public_path('public/promosi/document3/', $data->document3));
                $fdocument3 = 'artikel_file' . rand() . '.' . $gambar->getClientOriginalExtension();
                $gambar->move('./public/promosi/document3', $fdocument3);
            } else {
                $fdocument3 = $data->document3;
            }

            if ($this->request->file('document4')) {
                $gambar = $this->request->file('document4');
                @unlink(public_path('public/promosi/document4/', $data->document4));
                $fdocument4 = 'artikel_file' . rand() . '.' . $gambar->getClientOriginalExtension();
                $gambar->move('./public/promosi/document4', $fdocument4);
            } else {
                $fdocument4 = $data->document4;
            }

            if ($this->request->file('document5')) {
                $gambar = $this->request->file('document5');
                @unlink(public_path('public/promosi/document5/', $data->document5));
                $fdocument5 = 'artikel_file' . rand() . '.' . $gambar->getClientOriginalExtension();
                $gambar->move('./public/promosi/document5', $fdocument5);
            } else {
                $fdocument5 = $data->document5;
            }

            if ($this->request->file('document6')) {
                $gambar = $this->request->file('document6');
                @unlink(public_path('public/promosi/document6/', $data->document6));
                $fdocument6 = 'artikel_file' . rand() . '.' . $gambar->getClientOriginalExtension();
                $gambar->move('./public/promosi/document6', $fdocument6);
            } else {
                $fdocument6 = $data->document6;
            }

            $data->titleId = $this->request->titleID;
            $data->titleEn = $this->request->titleEn;
            $data->deskripsiId = $this->request->deskripsiId;
            $data->deskripsiEn = $this->request->deskripsiEn;
            $data->seotitle = $this->createSeoUrl($this->request->titleID);
            $data->filethumnaild = $ffilethumnaild;
            $data->imagepopup = $fimagepopup;
            $data->imageheader = $fimageheader;
            $data->linkvideo = $this->getYouTubeVideoId($this->request->linkvideo);
            $data->masaberlaku = $this->request->masaberlaku;

            $data->pdari = $this->request->pdari;
            $data->psampai = $this->request->psampai;

            $data->document1 = $fdocument1 ? $fdocument1 : '';
            $data->document2 = $fdocument2 ? $fdocument2 : '';
            $data->document3 = $fdocument3 ? $fdocument3 : '';
            $data->document4 = $fdocument4 ? $fdocument4 : '';
            $data->document5 = $fdocument5 ? $fdocument5 : '';
            $data->document6 = $fdocument6 ? $fdocument6 : '';

            $data->textdocument1 = isset($this->request->textdocument1) && $this->request->textdocument1 !== 'null' && $this->request->textdocument1 !== 'undefined' ? $this->request->textdocument1 : '';
            $data->textdocument2 = isset($this->request->textdocument2) && $this->request->textdocument2 !== 'null' && $this->request->textdocument2 !== 'undefined' ? $this->request->textdocument2 : '';
            $data->textdocument3 = isset($this->request->textdocument3) && $this->request->textdocument3 !== 'null' && $this->request->textdocument3 !== 'undefined' ? $this->request->textdocument3 : '';
            $data->textdocument4 = isset($this->request->textdocument4) && $this->request->textdocument4 !== 'null' && $this->request->textdocument4 !== 'undefined' ? $this->request->textdocument4 : '';
            $data->textdocument5 = isset($this->request->textdocument5) && $this->request->textdocument5 !== 'null' && $this->request->textdocument5 !== 'undefined' ? $this->request->textdocument5 : '';
            $data->textdocument6 = isset($this->request->textdocument6) && $this->request->textdocument6 !== 'null' && $this->request->textdocument6 !== 'undefined' ? $this->request->textdocument6 : '';

            $data->created_at = $date;
            $data->updated_at = $date;
            $data->updated_on = date('Y-m-d H:i:s');
            $data->updated_by = $this->request->user_id;

            $data->save();

            return response()->json([
                'status' => 'ok', 'messages' => 'databerhasil di edit',
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error event insert data',
                'errorcode' => 'error code' . $th,
            ], 400);
        }

    }
    public function action()
    {
        $action = $this->request->action == 'Y' ? 1 : 2;
        $id_promo = $this->request->id_promo;

        Promosi::where('id', $id_promo)->update([
            'active' => $action,
        ]);
        return response()->json([
            'messages' => 'date berhasil di update',
        ]);

    }
    public function destroy($id)
    {
        try {
            Promosi::where('id', $id)->delete();
            return response()->json(['messages' => 'data berhasil dihapus']);
        } catch (\Throwable $th) {
            return response()->json(['messages' => $th], 400);
        }
    }
}
