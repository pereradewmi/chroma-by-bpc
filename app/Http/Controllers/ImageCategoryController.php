<?php

namespace App\Http\Controllers;

use App\Models\ImageCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ImageCategoryController extends Controller
{
    /**
     * Display image categories list
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));

        $categories = ImageCategory::where('status', '!=', 2)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('id', 'like', "%{$search}%");
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('backend.image-categories.index', compact('categories'));
    }

    /**
     * Show create/edit form
     */
    public function form($id = null)
    {
        $category = $id ? ImageCategory::findOrFail($id) : new ImageCategory();
        $isEdit = !is_null($id);

        return view('backend.image-categories.form', compact('category', 'isEdit'));
    }

    /**
     * Store or update image category
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'status' => 'required|in:0,1,2',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_update' => 'nullable|boolean',
            'category_id' => 'nullable|exists:imagecategories,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = [
            'name' => $request->name,
            'status' => (int) $request->status,
        ];

        // Handle background image upload
        if ($request->hasFile('background_image')) {
            $file = $request->file('background_image');
            $path = $file->store('categories', 'public');
            $data['background_image'] = $path;
        }

        if ($request->get('is_update') && $request->get('category_id')) {
            $category = ImageCategory::findOrFail($request->get('category_id'));
            
            // Delete old image if new one is uploaded
            if ($request->hasFile('background_image') && $category->background_image) {
                Storage::disk('public')->delete($category->background_image);
            }
            
            $category->update($data);
            $message = 'Image category updated successfully!';
        } else {
            if (!isset($data['status'])) {
                $data['status'] = 1;
            }

            ImageCategory::create($data);
            $message = 'Image category created successfully!';
        }

        return redirect()->route('admin.image-categories.index')
            ->with('success', $message);
    }

    /**
     * Soft delete by setting status = 2
     */
    public function destroy($id)
    {
        $category = ImageCategory::findOrFail($id);
        $category->update(['status' => 2]);

        return redirect()->route('admin.image-categories.index')
            ->with('success', 'Image category deleted successfully!');
    }
}
