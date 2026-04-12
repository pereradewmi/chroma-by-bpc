<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\ImageCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
    /**
     * Display frontend gallery images
     */
    public function frontendIndex(Request $request)
    {
        $selectedCategoryId = $request->query('category');

        $categories = ImageCategory::where('status', 1)
            ->orderBy('name')
            ->get();

        $imagesQuery = Image::where('status', 1)
            ->with('category');

        if (! empty($selectedCategoryId)) {
            $imagesQuery->where('c_id', (int) $selectedCategoryId);
        }

        $images = $imagesQuery->latest()->get();

        return view('frontend.gallery', compact('images', 'categories', 'selectedCategoryId'));
    }

    /**
     * Display all images (admin)
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));

        $images = Image::with('category')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('id', $search)
                        ->orWhereHas('category', function ($categoryQuery) use ($search) {
                            $categoryQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('backend.gallery.index', compact('images'));
    }

    /**
     * Show create/edit form for image
     */
    public function form($id = null)
    {
        $image = $id ? Image::findOrFail($id) : new Image;
        $isEdit = ! is_null($id);
        $categories = ImageCategory::where('status', 1)->orderBy('name')->get();

        return view('backend.gallery.form', compact('image', 'isEdit', 'categories'));
    }

    /**
     * Display a single image
     */
    public function show($id)
    {
        $image = Image::find($id);

        if (! $image) {
            return response()->json([
                'success' => false,
                'message' => 'Image not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $image,
        ]);
    }

    /**
     * Store a new image
     */
    public function store(Request $request)
    {
        $isUpdate = (bool) $request->get('is_update');

        $validator = Validator::make($request->all(), [
            'c_id' => 'required|exists:imagecategories,id',
            'status' => 'required|in:0,1',
            'image' => ($isUpdate ? 'nullable' : 'required').'|image|mimes:jpg,jpeg,png,webp,gif|max:5120',
            'is_update' => 'nullable|boolean',
            'image_id' => 'nullable|exists:images,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if ($isUpdate && $request->get('image_id')) {
            $image = Image::findOrFail($request->get('image_id'));
        } else {
            $image = new Image;
        }

        $data = [
            'c_id' => (int) $request->c_id,
            'status' => (int) $request->status,
        ];

        if ($request->hasFile('image')) {
            if ($isUpdate && $image->image_path && Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }

            $data['image_path'] = $request->file('image')->store('images', 'public');
        }

        $image->fill($data);
        $image->save();

        return redirect()->route('admin.images.index')->with('success', $isUpdate ? 'Image updated successfully' : 'Image uploaded successfully');
    }

    /**
     * Update an existing image
     */
    public function update(Request $request, $id)
    {
        $image = Image::find($id);

        if (! $image) {
            return response()->json([
                'success' => false,
                'message' => 'Image not found',
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

        if (! $image) {
            return redirect()->route('admin.images.index')
                ->with('error', 'Image not found');
        }

        $image->update(['status' => 0]);

        return redirect()->route('admin.images.index')
            ->with('success', 'Image status updated to inactive successfully');
    }
}
