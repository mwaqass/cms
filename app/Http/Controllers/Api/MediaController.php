<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Media::with('user')->orderBy('created_at', 'desc');
        
        // Filter by type
        if ($request->has('type')) {
            switch ($request->type) {
                case 'images':
                    $query->images();
                    break;
                case 'videos':
                    $query->videos();
                    break;
                case 'documents':
                    $query->documents();
                    break;
            }
        }
        
        $media = $query->paginate(20);
        return response()->json($media);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
            'alt_text' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'caption' => 'nullable|string|max:255',
            'is_public' => 'boolean',
        ]);

        $file = $request->file('file');
        $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('media', $filename, 'public');

        $media = Media::create([
            'name' => $file->getClientOriginalName(),
            'filename' => $filename,
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'alt_text' => $request->alt_text,
            'description' => $request->description,
            'caption' => $request->caption,
            'is_public' => $request->get('is_public', true),
        ]);

        return response()->json($media, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Media $media)
    {
        return response()->json($media->load('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Media $media)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'alt_text' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'caption' => 'nullable|string|max:255',
            'is_public' => 'boolean',
        ]);

        $media->update([
            'name' => $request->name ?? $media->name,
            'alt_text' => $request->alt_text,
            'description' => $request->description,
            'caption' => $request->caption,
            'is_public' => $request->get('is_public', $media->is_public),
        ]);

        return response()->json($media);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Media $media)
    {
        // Delete the actual file
        Storage::disk('public')->delete($media->path);
        
        // Delete the database record
        $media->delete();
        
        return response()->json(['message' => 'Media deleted successfully']);
    }
}
