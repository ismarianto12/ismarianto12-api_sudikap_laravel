<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function index(Request $request)
    {

        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search', '');
        $sortBy = $request->input('sort_by', 'id_user');
        $sortOrder = $request->input('sort_order', 'asc');

        // Build the query
        $query = DB::table('login');
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Apply sorting
        $query->orderBy($sortBy, $sortOrder);
        $users = $query->paginate($perPage, ['*'], 'page', $page);

        // Format response
        return response()->json([
            'data' => $users->items(),
            'meta' => [
                'current_page' => $users->currentPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
                'last_page' => $users->lastPage(),
                'from' => $users->firstItem(),
                'to' => $users->lastItem(),
            ],
            'links' => [
                'first' => $users->url(1),
                'last' => $users->url($users->lastPage()),
                'prev' => $users->previousPageUrl(),
                'next' => $users->nextPageUrl(),
            ],
        ]);
    }

    public function edit($id)
    {

        $data = DB::table('login')
            ->select('id_user', 'username', 'nama', 'level', 'foto', 'email', 'active')
            ->where('id_user', $id)
            ->first();
        return response()->json(['data' => $data]);
    }

    // Create a new user (POST)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:50|unique:login',
            'password' => 'required|string|min:6',
            'nama' => 'required|string|max:30',
            'level' => 'required|in:admin,user,staff',
            'foto' => 'required|string|max:50',
            'email' => 'required|email|max:50|unique:login',
            'active' => 'required|in:y,n',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only(['username', 'nama', 'level', 'foto', 'email', 'active']);
        $data['password'] = Hash::make($request->password);
        $data['log'] = now()->toDateTimeString();
        $data['statuslogin'] = 0;

        $id = DB::table('login')->insertGetId($data);

        $user = DB::table('login')->where('id_user', $id)->first();

        return response()->json($user, 201);
    }

    // Show a specific user (GET)
    public function show($id)
    {
        $user = DB::table('login')->where('id_user', $id)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user);
    }

    // Update a user (PUT/PATCH)
    public function update(Request $request, $id)
    {
        $user = DB::table('login')->where('id_user', $id)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'username' => 'sometimes|string|max:50|unique:login,username,' . $id . ',id_user',
            'password' => 'sometimes|string|min:6',
            'nama' => 'sometimes|string|max:30',
            'level' => 'sometimes|in:admin,user,staff',
            'foto' => 'sometimes|string|max:50',
            'email' => 'sometimes|email|max:50|unique:login,email,' . $id . ',id_user',
            'active' => 'sometimes|in:y,n',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only(['username', 'nama', 'level', 'foto', 'email', 'active']);

        if ($request->has('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $data['log'] = now()->toDateTimeString();

        DB::table('login')
            ->where('id_user', $id)
            ->update($data);

        $updatedUser = DB::table('login')->where('id_user', $id)->first();

        return response()->json($updatedUser);
    }

    // Delete a user (DELETE)
    public function destroy($id)
    {
        $user = DB::table('login')->where('id_user', $id)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        DB::table('login')->where('id_user', $id)->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}