<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ClassRoomController extends Controller
{
    /**
     * Display the classes list for backend
     */
    public function index()
    {
        $classes = ClassRoom::latest()->paginate(10);
        return view('backend.classes.index', compact('classes'));
    }

    /**
     * Display the classes list for frontend
     */
    public function frontendIndex()
    {
        $classes = ClassRoom::latest()->get();
        return view('frontend.classes', compact('classes'));
    }

    /**
     * Show the form for creating/editing a class
     */
    public function form($id = null)
    {
        $class = $id ? ClassRoom::findOrFail($id) : new ClassRoom();
        $isEdit = !is_null($id);

        return view('backend.classes.form', compact('class', 'isEdit'));
    }

    /**
     * Store or update class data based on flag
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cName' => 'required|string|max:255',
            'cDescription' => 'nullable|string',
            'classfee' => 'required|numeric|min:0',
            'admission_amount' => 'nullable|numeric|min:0',
            'cImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
            'is_update' => 'boolean',
            'class_id' => 'nullable|exists:classdetails,cID'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only(['cName', 'cDescription', 'classfee', 'admission_amount']);

        // Handle image upload
        if ($request->hasFile('cImage')) {
            $image = $request->file('cImage');

            // Create storage directory if it doesn't exist
            if (!Storage::exists('public/classes')) {
                Storage::makeDirectory('public/classes');
            }

            // Generate unique filename with timestamp
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            // Store image in storage/app/public/classes/
            $path = $image->storeAs('public/classes', $filename);

            $data['cImage'] = $filename;
        }

        if ($request->get('is_update') && $request->get('class_id')) {
            // Update existing class
            $class = ClassRoom::findOrFail($request->get('class_id'));

            // Delete old image if new image is uploaded
            if ($request->hasFile('cImage') && $class->cImage && Storage::exists('public/classes/' . $class->cImage)) {
                Storage::delete('public/classes/' . $class->cImage);
            }

            $class->update($data);
            $message = 'Class updated successfully!';
        } else {
            // Create new class
            $class = ClassRoom::create($data);
            $message = 'Class created successfully!';
        }

        return redirect()->route('classes.index')
            ->with('success', $message);
    }

    /**
     * Delete a class
     */
    public function destroy($id)
    {
        $class = ClassRoom::findOrFail($id);

        // Delete image if it exists
        if ($class->cImage && Storage::exists('public/classes/' . $class->cImage)) {
            Storage::delete('public/classes/' . $class->cImage);
        }

        $class->delete();

        return redirect()->route('classes.index')
            ->with('success', 'Class deleted successfully!');
    }
}