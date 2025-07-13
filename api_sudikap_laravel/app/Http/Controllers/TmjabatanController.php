<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TmjabatanController extends Controller
{
    // GET /api/tmjabatan
    public function index(Request $request)
    {
        $query = DB::table('tmjabatan')
            ->select('id', 'title', 'description', 'stat', 'otherString');

        // Filtering
        if ($request->has('title')) {
            $query->where('title', 'like', '%'.$request->title.'%');
        }
        
        if ($request->has('stat')) {
            $query->where('stat', $request->stat);
        }

        // Searching (global search)
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                  ->orWhere('description', 'like', '%'.$search.'%')
                  ->orWhere('otherString', 'like', '%'.$search.'%');
            });
        }

        // Sorting
        $sortField = $request->get('sort_field', 'id');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortField, $sortOrder);

        // Paging
        $perPage = $request->get('per_page', 10);
        $data = $query->paginate($perPage);

        return response()->json([
            'data' => $data->items(),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ]
        ]);
    }

    // POST /api/tmjabatan
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:255',
            'stat' => 'nullable|string|max:100',
            'otherString' => 'nullable|string'
        ]);

        $id = DB::table('tmjabatan')->insertGetId($validated);

        return response()->json([
            'id' => $id,
            'message' => 'Data created successfully'
        ], 201);
    }

    // GET /api/tmjabatan/{id}
    public function show($id)
    {
        $data = DB::table('tmjabatan')
            ->select('id', 'title', 'description', 'stat', 'otherString')
            ->where('id', $id)
            ->first();

        if (!$data) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        return response()->json($data);
    }

    // PUT /api/tmjabatan/{id}
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:255',
            'stat' => 'nullable|string|max:100',
            'otherString' => 'nullable|string'
        ]);

        $affected = DB::table('tmjabatan')
            ->where('id', $id)
            ->update($validated);

        if ($affected === 0) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        return response()->json(['message' => 'Data updated successfully']);
    }

    // DELETE /api/tmjabatan/{id}
    public function destroy($id)
    {
        $affected = DB::table('tmjabatan')
            ->where('id', $id)
            ->delete();

        if ($affected === 0) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        return response()->json(['message' => 'Data deleted successfully']);
    }
}