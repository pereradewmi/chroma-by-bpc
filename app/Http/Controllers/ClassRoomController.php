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
        $classes = ClassRoom::orderBy('cID', 'asc')->paginate(10);
        return view('backend.classes.index', compact('classes'));
    }

    /**
     * Display the classes list for frontend
     */
    public function frontendIndex()
    {
        $classes = ClassRoom::orderBy('cID', 'asc')->get();
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
            'cVideo' => 'nullable|file|mimetypes:video/mp4,video/webm,video/quicktime,video/x-msvideo,video/x-matroska|max:51200',
            'is_update' => 'boolean',
            'class_id' => 'nullable|exists:classdetails,cID'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only(['cName', 'cDescription', 'classfee', 'admission_amount']);

        // Handle video upload
        if ($request->hasFile('cVideo')) {
            $video = $request->file('cVideo');

            // Create storage directory if it doesn't exist
            if (!Storage::exists('public/class-videos')) {
                Storage::makeDirectory('public/class-videos');
            }

            // Generate unique filename with timestamp
            $filename = time() . '_' . uniqid() . '.' . $video->getClientOriginalExtension();

            // Store video in storage/app/public/class-videos/
            $video->storeAs('public/class-videos', $filename);

            $data['cVideo'] = $filename;
        }

        if ($request->get('is_update') && $request->get('class_id')) {
            // Update existing class
            $class = ClassRoom::findOrFail($request->get('class_id'));

            // Delete old video if new video is uploaded
            if ($request->hasFile('cVideo') && $class->cVideo && Storage::exists('public/class-videos/' . $class->cVideo)) {
                Storage::delete('public/class-videos/' . $class->cVideo);
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

        // Delete video if it exists
        if ($class->cVideo && Storage::exists('public/class-videos/' . $class->cVideo)) {
            Storage::delete('public/class-videos/' . $class->cVideo);
        }

        $class->delete();

        return redirect()->route('classes.index')
            ->with('success', 'Class deleted successfully!');
    }
}