<?php
namespace App\Http\Controllers;

use App\Models\pages;
use App\Models\post;
use App\Models\Promosi;
use App\Models\struktur;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;
use Validator;

class HomeController extends Controller
{

    public function __construct(Request $request)
    {
        $this->request = $request;

    }
    public function index()
    {
        return response()->json(['api' => 'v1']);
    }
    public function artikel()
    {
        $data = post::get();
        return response()->json($data);
    }

    private function removeCharacter($parmater)
    {
        $paramater = str_replace($parmater, ' ', '-');
        $data = ucfirst($paramater);
        return $data;
    }

    private function filterPenghargaan($perPage, $tahun, $limit)
    {
        $query = DB::table('penghargaan')
            ->select('id', 'namapenghargaan', 'kategori', 'diberikanoleh', 'lokasi', 'tahun', 'file', 'updated_at', 'user_id');

        if ($tahun) {
            $query->whereYear('tahun', $tahun);
        }

        if ($limit) {
            $query->limit($limit);
        }

        $penghargaan = $query->paginate($perPage);
        return response()->json($penghargaan);
    }

    public function penghargaan(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $tahun = $request->input('tahun');
        $limit = $request->input('limit');

        $penghargaan = $this->filterPenghargaan($perPage, $tahun, $limit);

        return $penghargaan;
    }

    public function randomPromo()
    {
        $data = Promosi::select('*')->orderBy('id', 'desc')->limit(4)->get();
        return response()->json($data);
    }

    public function filterPromo(Request $request)
    {
        $perPage = $request->input('per_page', 9);
        $tahun = $request->input('tahun', '');
        $limit = $request->input('limit', '');
        $sort = $request->input('sort', 'desc');
        $tanggalSekarang = date('Y-m-d');

        $query = DB::table('promo')
            ->select(DB::raw('REPLACE(promo.titleID,"-"," ") as formatted_title'), 'id', 'titleID', 'seotitle', 'titleEn', 'deskripsiId', 'deskripsiEn', 'filethumnaild', 'imagepopup', 'imageheader', 'document1', 'document2', 'linkvideo', 'created_at',
                'updated_at',
                'linkpromo2',
                'linkpromo2'
            )
            ->whereDate('promo.masaberlaku', '>', $tanggalSekarang)
            ->whereNotNull('titleEn');

        if (!empty($tahun)) {
            $query->whereYear('created_at', $tahun);
        }

        if (!empty($sort)) {
            $query->orderBy('id', $sort);
        }

        if (!empty($limit)) {
            $query->limit($limit);
        }

        $posts = $query->paginate($perPage);

        return response()->json($posts);
    }

    public function filterVideo(Request $request)
    {
        $perPage = $request->input('per_page', 9);
        $tahun = $request->input('tahun', '');
        $limit = $request->input('limit', '');
        $sort = $request->input('sort', 'desc');

        $query = DB::table('video')
            ->select(
                'video.id_video',
                'video.title',
                'video.date',
                'video.url',
                'video.desc',
                'video.link',
                'video.description',
                'video.headline',
                'video.created_at',
                'video.user_id',
                'video.updated_at'
            );
        // ->whereNotNull('titleEn');

        if (!empty($tahun)) {
            $query->whereYear('created_at', $tahun);
        }

        if (!empty($sort)) {
            $query->orderBy('id_video', $sort);
        }

        if (!empty($limit)) {
            $query->limit($limit);
        }

        $posts = $query->paginate($perPage);

        return response()->json($posts);
    }

    public function filterPosts(Request $request)
    {
        $perPage = $request->input('per_page', 9);
        $tahun = $request->input('tahun', '');
        $limit = $request->input('limit', '');

        $dari = $this->request->dari;
        $sampai = $this->request->sampai;

        $sort = $request->sort;
        $parameter_api = $request->parameter_api;
        $query = DB::table('post')
            ->select(DB::raw('REPLACE(post.title,"-"," ") as formatted_title'), 'post.id_post', 'post.id_category', 'post.stockcode', 'post.title', 'post.judul', 'post.content', 'post.isi', 'post.seotitle', 'post.tags', 'post.tag', DB::raw('DATE_FORMAT(post.date,"%Y-%M-%d") as date'), 'post.time', 'post.date', 'post.editor', 'post.protect', 'post.active', 'post.headline', 'post.picture', 'post.hits', 'post.new_version', 'category.title as category_title', 'post.created_on')
            ->join('category', 'post.title', '=', 'category.id_category', 'left')
            ->where('post.active', 'Y')
            ->whereNotNull('post.title');

        if (!empty($dari) && !empty($sampai)) {
            $query->whereBetween(DB::raw('DATE(post.date)'), [$dari, $sampai]);
        } elseif (!empty($dari)) {
            $query->where(DB::raw('DATE(post.date)'), '>=', $dari);
        } elseif (!empty($sampai)) {
            $query->where(DB::raw('DATE(post.date)'), '<=', $sampai);
        }
        if ($parameter_api == 'press_release') {
            $query->where('post.id_category', 36);
        } else {
            $query->where('post.id_category', 35);
        }
        if (!empty($tahun)) {
            $query->whereYear('post.date', $tahun);
        }
        if ($sort) {
            $query->orderBy('post.id_post', $sort);
        }
        if (!empty($limit)) {
            $query->limit($limit);
        }
        $posts = $query->paginate($perPage);
        return response()->json($posts);
    }
    public function filterNewGalery(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'per_page' => 'integer|min:1|max:100',
                'year' => 'nullable|date_format:Y',
                'limit' => 'nullable|integer|min:1|max:100',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 400);
        }
        $perPage = $validatedData['per_page'] ?? 8;
        $tahun = $validatedData['year'] ?? '';
        $limit = $validatedData['limit'] ?? '';

        $query = DB::table('events')
            ->select(
                'events.title',
                'events.headline',
                'events.published',
                'events.active',
                'events.images',
                'events.images_desc'
            );

        if ($tahun != '') {
            $query->whereYear('events.created_at', $tahun);
        }
        if (!empty($limit)) {
            $query->limit($limit);
        }

        $data = $query->paginate($perPage);
        return response()->json($data);
    }

    public function filterGalery(Request $request)
    {
        $perPage = $request->input('per_page', 8); // Default to 10 per page, change as needed
        $tahun = $request->input('year', ''); // Filter by tahun
        $limit = $request->input('limit', ''); // Limit the results
        $query = DB::table('galery')
            ->select(
                'galery.id',
                'galery.title',
                'galery.deskripsiId',
                'galery.deskripsiEn',
                'galery.id_album',
                'galery.gambar',
                DB::raw('DATE_FORMAT(post.created_on,"%d-%M-%Y %H:%m") as created_at'),
                'album.title AS album_title',
                'album.seotitle AS album_seotitle',
                'album.active AS album_active'
            )
            ->leftJoin('album', 'galery.id_album', '=', 'album.id_album'); // Left join with the album table

        if ($tahun != '') {
            $query->whereYear('galery.created_at', $tahun);
        }

        if (!empty($limit)) {
            $query->limit($limit);
        }

        $galeries = $query->paginate($perPage);
        return response()->json($galeries);
    }

    public function filterEvent(Request $request)
    {
        $perPage = $request->input('per_page', 8); // Default to 10 per page, change as needed
        $tahun = $request->input('year', ''); // Filter by tahun
        $limit = $request->input('limit', ''); // Limit the results
        $query = DB::table('events')
            ->select(
                'events.title',
                'events.headline',
                DB::raw('DATE_FORMAT(events.published,"%d-%M-%Y %H:%m") as published'),
                'events.active',
                'events.images',
                'events.images_desc',
                'events.new_version'
            );
        if ($tahun != '') {
            $query->whereYear('events.published', $tahun);
        }

        if (!empty($limit)) {
            $query->limit($limit);
        }
        $query->orderBy('events.id', 'desc');
        $galeries = $query->paginate($perPage);
        return response()->json($galeries);
    }

    public function filterPostsBycat(Request $request)
    {
        $perPage = $request->input('per_page', 9); // Default to 10 per page, change as needed
        $category = $request->input('category', ''); // Filter by tahun
        $limit = $request->input('limit', ''); // Limit the results

        $search = $this->request->input('q');
        $date = $this->request->input('date');
        $from = $this->request->from;
        $dateto = $this->request->dateto;

        $query = DB::table('post')
            ->select(DB::raw('REPLACE(post.title,"-"," ") as formatted_title'), 'post.id_post', 'post.id_category', 'post.stockcode', 'post.title', 'post.judul', 'post.content', 'post.isi', 'post.seotitle', 'post.tags', 'post.tag', DB::raw('DATE_FORMAT(post.date,"%Y-%M-%d") as date'), 'post.time', 'post.editor', 'post.protect', 'post.active', 'post.headline', 'post.picture', 'post.hits', 'post.new_version', 'category.title')
        // ->whereNotIn('post.id_category', [13, 14, 24, 26])
            ->join('category', 'post.id_category', '=', 'category.id_category', 'left')
        // ->where('post.active', 1)
            ->where('category.seotitle', $category);
        // ->whereNotNull('post.title');

        if (!empty($from) && !empty($dateto)) {
            $query->whereBetween(DB::raw('DATE(post.created_on)'), [$from, $dateto]);
        } elseif (!empty($from)) {
            $query->where(DB::raw('DATE(post.created_on)'), '>=', $from);
        } elseif (!empty($dateto)) {
            $query->where(DB::raw('DATE(post.created_on)'), '<=', $dateto);
        }
        if (!empty($tahun)) {
            $query->whereYear('post.date', $tahun);
        }
        if (!empty($limit)) {
            $query->limit($limit);
        }
        if ($search) {
            $query->where('post.judul', 'like', '%' . $search . '%');
        }
        if ($date) {
            $query->where('date', $date);
        }
        $query->orderBy('post.id_post', 'desc');
        $posts = $query->paginate($perPage);
        return response()->json($posts);
    }
    public function jadwalEdukasi(Request $request)
    {
        $perPage = $request->input('per_page', 9);
        $param = [
            'Konvensional' => 1,
            'Syariah' => 2,
            'Eksternal' => 3,
        ];
        $category = $request->category;
        $limit = $request->input('limit', '');
        $tahun = $request->input('tahun', '');
        $query = DB::table('jadwal')
            ->select('id', 'topic', 'descriptionId', 'descriptionEn', 'type_edukasi', 'link_registrasi', 'image',
                DB::raw('DATE_FORMAT(jadwal.created_on,"%d-%M-%Y %H:%m") as created_at'),
                'venue', 'updated_at', 'user_id')
            ->where('type_edukasi', $category);
        if (!empty($tahun)) {
            $query->whereYear('created_at', $tahun); // Adjust this based on your actual column name
        }

        if (!empty($limit)) {
            $query->limit($limit);
        }
        $query->orderBy('id', 'desc');
        $posts = $query->paginate($perPage);

        return response()->json($posts);
    }

    public function searchPost(Request $request)
    {

        $rules = array(
            'q' => 'required',
        );
        $messages = array(
            'q.required' => 'query pencarian wajib.',
        );
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();
            return response()->json($errors);
        }
        $perPage = $request->input('per_page', 9);
        $query = $request->input('q', '');
        $limit = $request->input('limit', '');

        $query = Post::select(
            'post.id_post', 'post.id_category', 'post.stockcode', 'post.title', 'post.judul', 'post.content', 'post.isi', 'post.seotitle', 'post.tags', 'post.tag', 'post.time', 'post.editor', 'post.protect', 'post.active', 'post.headline', 'post.picture', 'post.hits', 'post.new_version', 'category.title',
            DB::raw('DATE_FORMAT(post.date,"%d-%M-%Y %H:%m") as date'),
            DB::raw('REPLACE(post.title,"-"," ") as formatted_title')

        )
            ->join('category', 'post.id_category', '=', 'category.id_category', 'left')
            ->where('post.judul', 'like', '%' . $query . '%')
            ->where('post.title', 'like', '%' . $query . '%')
            ->where('post.isi', 'like', '%' . $query . '%')
            ->whereIn('category.id_category', [
                35,
                36,
            ]);
        if (!empty($tahun)) {
            $query->whereYear('post.date', $tahun);
        }
        if (!empty($limit)) {
            $query->limit($limit);
        }
        $posts = $query->paginate($perPage);
        return response()->json($posts);
    }

    public function newsupdate()
    {
        $query = Post::select('*')
            ->where('id_category', '36')
            ->orderBy('id_post', 'desc')
            ->limit(3)
            ->get();
        return response()->json($query);

    }

    //

    public function currency()
    {
        $path = public_path() . '/dataxmls/currencies.xml';

        if (File::exists($path)) {
            $xmlContent = File::get($path);

            return $xmlContent;
        } else {
            return response()->json(['message' => 'File not found'], 404);
        }
    }

    public function promoshow($id)
    {
        try {
            $data = Promosi::where('seotitle', $id)->get();
            return response()->json($data);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function manajemen()
    {
        $data = Struktur::get();
        return response()->json($data);

    }
    public function getCategoryris()
    {
        $data = DB::table('category')
            ->whereIn('seotitle', [
                'mncs-daily-scope-wave',
                'mncs-morning-navigator',
                'market-focus',
                'company-update',
                'mncs-fixed-income',
            ])
            ->get();
        return response()->json($data);
    }

    public function currentPromosi()
    {
        try {
            $dataPromo = Promosi::where('imagepopup', '!=', '')->where('imagepopup', '!=', null)->orderBy('id', 'desc')->limit(1)->get();
            return response()->json($dataPromo, 200);
        } catch (\Throwable $th) {
            return response()->json(['messages' => $th->getMessage()], 200);
        }
    }

    public function upload()
    {
        try {

            $date = Carbon::now()->format('Y-m-d H:i:s');
            foreach ($this->request->file('files') as $file) {
                $extension = $file->getClientOriginalExtension();

                $filename = 'galeryfile' . uniqid() . '.' . $extension;
                $file->move(public_path('files/uploads'), $filename); // Use public_path() to specify the correct directory
            }
            return response()->json([
                'berhasil simpan gambar',
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'gagal upload gambar' . $th->getMesage(),
            ], 400);
        }

    }

    private function humanFilesize($bytes, $decimals = 2)
    {
        $size = ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
    }
    public function loopingFile($directory)
    {
        $result = [];
        $files = scandir($directory);
        foreach ($files as $file) {
            // Melewati "." dan ".."
            if ($file == '.' || $file == '..') {
                continue;
            }

            // Baca informasi file
            $filePath = $directory . $file;
            $fileInfo = pathinfo($filePath);
            $fileSize = filesize($filePath);
            $fileType = mime_content_type($filePath);
            $fileModifiedTime = date('m/d/Y g:i A', filemtime($filePath));

            // Menyusun hasil dalam format yang diinginkan
            $result[] = [
                'file' => $file,
                'name' => $fileInfo['basename'],
                'type' => $fileType,
                'thumb' => strtolower($fileInfo['filename']) . '.' . (isset($fileInfo['extension']) ? $fileInfo['extension'] : ''),
                'changed' => $fileModifiedTime,
                'size' => $this->humanFilesize($fileSize),
                'isImage' => strpos($fileType, 'image') !== false,
            ];
        }

        return $result;
    }
    public function paramdashboard($id)
    {
        switch ($id) {
            case 'artikel':
                $data = post::get()->count();
                return $data;
                break;
            case 'halaman':
                $data = pages::get()->count();
                return $data;
                break;
            case 'promo':
                $data = Promosi::get()->count();
                return $data;
                break;
            case 'riset':
                $data = Post::whereNotIn('id_category', [35, 36])->count();
                return $data;
                break;

            case 'kunjungan':
                $data = Post::whereNotIn('id_category', [35, 36])->count();
                return $data;
                break;
            default:
                return null;
                break;
        }
    }
    public function filemanager()
    {

        $loopingFile = $this->loopingFile('./files/uploads/');

        $action = $this->request->action; //  fileRemove
        $path = $this->request->path; // files/upload
        $name = $this->request->name; // galeryfile657685b13c3da.jpeg
        $source = $this->request->source; //  default
        if ($action == 'fileRemove') {
            @unlink('./files/uploads/' . $name);
        }
        try {

            $jsonParse = [
                "success" => true,
                "time" => date("Y-m-d H:i:s"),
                "data" => [
                    "sources" => [[
                        "name" => "default",
                        "baseurl" => "https://wwwdev.mncsekuritas.id:30443/assetweb/",
                        "path" => "files/uploads/",
                        "files" => $loopingFile,
                    ],
                    ],
                    "code" => 220,
                ],
            ];
            return response()->json($jsonParse);
        } catch (\Throwable $th) {
            return response()->json(['messages' => $th->getMessage()]);
        }
    }
}
