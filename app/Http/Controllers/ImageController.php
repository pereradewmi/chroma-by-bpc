<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
    /**
     * Display frontend gallery images
     */
    public function frontendIndex()
    {
        $images = Image::where('status', 1)
            // ->orderBy('sort_order')
            ->latest()
            ->get();

        return view('frontend.gallery', compact('images'));
    }

    /**
     * Display all images (admin)
     */
    public function index()
    {
        $images = Image::latest()->paginate(12);
        // return response()->json($images);

         return view('backend.gallery.index', compact('images'));
    }

    /**
     * Display a single image
     */
    public function show($id)
    {
        $image = Image::find($id);

        if (!$image) {
            return response()->json([
                'success' => false,
                'message' => 'Image not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $image
        ]);
    }

    /**
     * Store a new image
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'title' => 'nullable|string|max:255',
            // 'description' => 'nullable|string|max:1000',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp,gif|max:5120',
            // 'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $path = $request->file('image')->store('images', 'public');

        $image = Image::create([
            // 'title' => $request->title,
            // 'description' => $request->description,
            'image_path' => $path,
            'status' => 1,
            // 'sort_order' => (int) $request->get('sort_order', 0),
        ]);
        
         return redirect()->route('admin.images.index')->with('success', 'Image uploaded successfully');
        // return response()->json([
        //     'success' => true,
        //     'message' => 'Image uploaded successfully',
        //     'data' => $image,
        // ], 201);
    }

    /**
     * Update an existing image
     */
    public function update(Request $request, $id)
    {
        $image = Image::find($id);

        if (!$image) {
            return response()->json([
                'success' => false,
                'message' => 'Image not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            // 'title' => 'nullable|string|max:255',
            // 'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:5120',
            'status' => 'nullable|boolean',
            // 'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $request->only(['status']);

        if ($request->hasFile('image')) {
            if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }

            $data['image_path'] = $request->file('image')->store('images', 'public');
        }

        $image->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Image updated successfully',
            'data' => $image->fresh(),
        ]);
    }

    /**
     * Delete an image
     */
    public function destroy($id)
    {
        $image = Image::find($id);

        if (!$image) {
            return response()->json([
                'success' => false,
                'message' => 'Image not found'
            ], 404);
        }

        $image->update(['status' => 0]);

        return response()->json([
            'success' => true,
            'message' => 'Image status updated to inactive successfully'
        ]);
    }
}
