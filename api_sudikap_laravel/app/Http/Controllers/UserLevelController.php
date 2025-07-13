<?php

namespace App\Http\Controllers;

use App\Models\user_level;
use Illuminate\Http\Request;
use JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserLevelController extends Controller
{

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getUserdata()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            return response()->json(['username' => $user], 200);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['error' => 'Token is invalid'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['error' => 'Token has expired'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['error' => 'Token is absent or invalid'], 401);
        }
    }

    public function index()
    {
        $data = user_level::select(
            'user_level.id_level as id',
            'user_level.level',
            'user_level.created_on',
            'user_level.updated_on',
            'created_by_user.nama_lengkap as created_by',
            'updated_by_user.nama_lengkap as updated_by'
        )->leftJoin('users as created_by_user', 'user_level.created_by', '=', 'created_by_user.id_user')
            ->leftJoin('users as updated_by_user', 'user_level.updated_by', '=', 'updated_by_user.id_user')
            ->orderBy('user_level.id_level', 'desc')
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $level = new user_level;
        $level->level = $this->request->level;
        $level->created_by = $this->request->user_id;
        $level->created_on = date('Y-m-d H:i:s');
        $level->save();
        return response()->json([
            'messages' => "berhasil",
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\user_level  $user_level
     * @return \Illuminate\Http\Response
     */
    public function show(user_level $user_level)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\user_level  $user_level
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $level = user_level::where('id_level', $id)->first();
        return response()->json($level);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\user_level  $user_level
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        try {
            $level = user_level::where('id_level', $id)->update([
                'level' => $this->request->level,
                'updated_on' => date('Y-m-d H:i:s'),
                'updated_by' => $this->request->user_id,
            ]);
            return response()->json([
                'messages' => "berhasil",
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'messages' => $th->getMessage(),
            ]);

        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\user_level  $user_level
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $level = user_level::where('id_level', $id);
            $level->delete();
            return response()->json([
                'messages' => "berhasil",
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'messages' => $th->getMessage(),
            ]);

        }
    }
}
