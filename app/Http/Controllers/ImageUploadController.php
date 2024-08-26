<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;


class ImageUploadController extends Controller
{
    public function store(Request $request)
{
    // Check if the request has files
    if (!$request->hasFile('images')) {
        \Log::error('No files detected.');
        return response()->json(['error' => 'No files detected.'], 400);
    }

    // Validate the files
    $request->validate([
        'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    \Log::info('Files validated.');

    $imagePaths = [];
    foreach ($request->file('images') as $image) {
        if ($image->isValid()) {
            $imageName = time() . '-' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('uploads', $imageName, 'public');

            $imagePaths[] = [
                'file_name' => $imageName,
                'file_path' => '/storage/' . $imagePath,
            ];

            \Log::info('Image stored: ' . $imageName);
        }
    }

    // Save image paths to the database
    Image::insert($imagePaths);
    \Log::info('Images saved to the database.');

    return response()->json(['success' => 'Images uploaded successfully.']);
}


    public function index()
    {
        $images = Image::all();
        return view('image-upload', compact('images'));
    }
}
