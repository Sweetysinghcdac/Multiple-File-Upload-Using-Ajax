<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use Illuminate\Support\Facades\Storage; // Import the Storage facad

class ImageUploadController extends Controller
{
    public function index()
    {
        $images = Image::all();
        return view('image-upload', compact('images'));
    }

    public function store(Request $request)
    {
        if (!$request->hasFile('images')) {
            \Log::error('No files detected.');
            return response()->json(['error' => 'No files detected.'], 400);
        }

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

        Image::insert($imagePaths);
        \Log::info('Images saved to the database.');

        return response()->json(['success' => 'Images uploaded successfully.']);
    }

    public function edit($id)
    {
        $image = Image::find($id);
        if ($image) {
            return response()->json($image);
        }
        return response()->json(['error' => 'Image not found'], 404);
    }
    

    public function update(Request $request, $id)
    {
        $image = Image::find($id);
        if (!$image) {
            return response()->json(['error' => 'Image not found'], 404);
        }
    
        if ($request->hasFile('croppedImage')) {
            $file = $request->file('croppedImage');
            $imageName = time() . '-' . $image->file_name;  // Optionally rename the image
            $imagePath = 'uploads/' . $imageName;
    
            // Save the cropped image
            Storage::disk('public')->put($imagePath, file_get_contents($file));
    
            // Delete old file
            if (file_exists(public_path($image->file_path))) {
                unlink(public_path($image->file_path));
            }
    
            // Update the database
            $image->file_name = $imageName;
            $image->file_path = '/storage/' . $imagePath;
            $image->save();
    
            return response()->json(['success' => 'Image updated successfully.']);
        }
    
        return response()->json(['error' => 'No image file found'], 400);
    }
    
    

    public function destroy($id)
    {
        $image = Image::find($id);
        if ($image) {
            if (file_exists(public_path($image->file_path))) {
                unlink(public_path($image->file_path));
            }
            $image->delete();
            return response()->json(['success' => 'Image deleted successfully.']);
        }
        return response()->json(['error' => 'Image not found'], 404);
    }
}
