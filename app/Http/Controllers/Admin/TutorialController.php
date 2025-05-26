<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tutorial;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TutorialController extends Controller
{
    public function index()
{
    return view('Admin.Tutorial'); 
}

public function list()
{
    return response()->json(Tutorial::all());
}

   public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'type' => 'required|in:url,upload',
        'video_url' => 'nullable|string|max:1024',
        'file' => 'nullable|file|mimes:mp4,webm,mov,avi|max:102400', // 20 Mo max
    ]);

    if ($request->type === 'upload' && $request->hasFile('file')) {
        $path = $request->file('file')->store('tutorials', 'public');
        $video_url = '/storage/' . $path;
    } else {
        $video_url = $request->video_url;
    }

    return Tutorial::create([
        'title' => $request->title,
        'video_url' => $video_url,
        'type' => $request->type
    ]);
}

    public function update(Request $request, Tutorial $tutorial)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'type' => 'required|in:url,upload',
        'video_url' => 'nullable|string|max:1024',
        'file' => 'nullable|file|mimes:mp4,webm,mov,avi|max:102400',
    ]);

    if ($request->type === 'upload' && $request->hasFile('file')) {
        $path = $request->file('file')->store('tutorials', 'public');
        $video_url = '/storage/' . $path;
    } else {
        $video_url = $request->video_url;
    }

    $tutorial->update([
        'title' => $request->title,
        'type' => $request->type,
        'video_url' => $video_url
    ]);

    return response()->json(['message' => 'Tutoriel mis à jour']);
}


    public function destroy(Tutorial $tutorial)
    {
        $tutorial->delete();
        return response()->json(['message' => 'Tutoriel supprimé']);
    }


    public function publicList()
{
    return response()->json(
        Tutorial::select('title', 'video_url')
        ->whereNotNull('video_url')
        ->get()
    );
}

}

