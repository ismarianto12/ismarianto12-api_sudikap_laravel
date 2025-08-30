<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileManagerController extends Controller
{
    // Get files and folders
    public function index(Request $request)
    {
        $path = $request->get('path', '/');
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');

        $files = [];
        $directories = [];

        $storageFiles = Storage::files($path);
        $storageDirectories = Storage::directories($path);

        foreach ($storageFiles as $filePath) {
            $files[] = [
                'name'      => basename($filePath),
                'path'      => $filePath,
                'type'      => 'file',
                'size'      => Storage::size($filePath),
                'modified'  => Storage::lastModified($filePath),
                'extension' => pathinfo($filePath, PATHINFO_EXTENSION),
            ];
        }

        foreach ($storageDirectories as $dirPath) {
            $directories[] = [
                'name'      => basename($dirPath),
                'path'      => $dirPath,
                'type'      => 'folder',
                'size'      => 0,
                'modified'  => Storage::lastModified($dirPath),
                'extension' => '',
            ];
        }

        $items = array_merge($directories, $files);

        usort($items, function ($a, $b) use ($sortBy, $sortOrder) {
            $compare = 0;

            if ($sortBy === 'name') {
                $compare = strcasecmp($a['name'], $b['name']);
            } elseif ($sortBy === 'size') {
                $compare = $a['size'] <=> $b['size'];
            } elseif ($sortBy === 'modified') {
                $compare = $a['modified'] <=> $b['modified'];
            }

            return $sortOrder === 'desc' ? -$compare : $compare;
        });

        return response()->json([
            'path' => $path,
            'items' => $items
        ]);
    }

    // Create new folder
    public function createFolder(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
            'name' => 'required|string'
        ]);

        $fullPath = $request->path . '/' . $request->name;

        if (Storage::exists($fullPath)) {
            return response()->json(['error' => 'Folder already exists'], 400);
        }

        if (Storage::makeDirectory($fullPath)) {
            return response()->json(['message' => 'Folder created successfully']);
        }

        return response()->json(['error' => 'Failed to create folder'], 500);
    }

    // Upload file
    public function upload(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
            'file' => 'required|file'
        ]);

        $file = $request->file('file');
        $path = $request->path;

        if ($file->storePublicly($path)) {
            return response()->json(['message' => 'File uploaded successfully']);
        }

        return response()->json(['error' => 'Failed to upload file'], 500);
    }

    // Copy file/folder
    public function copy(Request $request)
    {
        $request->validate([
            'from' => 'required|string',
            'to' => 'required|string'
        ]);

        $from = $request->from;
        $to = $request->to;

        if (!Storage::exists($from)) {
            return response()->json(['error' => 'Source does not exist'], 404);
        }

        if (Storage::exists($to)) {
            return response()->json(['error' => 'Destination already exists'], 400);
        }

        if (Storage::copy($from, $to)) {
            return response()->json(['message' => 'Copied successfully']);
        }

        return response()->json(['error' => 'Failed to copy'], 500);
    }

    // Get file content for preview
    public function preview(Request $request)
    {
        $request->validate([
            'path' => 'required|string'
        ]);

        $path = $request->path;

        if (!Storage::exists($path)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        $mimeType = Storage::mimeType($path);
        $content = base64_encode(Storage::get($path));

        return response()->json([
            'mimeType' => $mimeType,
            'content' => $content
        ]);
    }

    // Download file
    public function download(Request $request)
    {
        $request->validate([
            'path' => 'required|string'
        ]);

        $path = $request->path;

        if (!Storage::exists($path)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        return Storage::download($path);
    }
}
