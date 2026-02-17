<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClassRoomController extends Controller
{
    /**
     * Display the class registration form
     */
    public function index()
    {
        $classes = ClassRoom::with('teacher')->latest()->paginate(10);
        return view('backend.classes.index', compact('classes'));
    }

    /**
     * Show the form for creating/editing a class
     */
    public function form($id = null)
    {
        $class = $id ? ClassRoom::findOrFail($id) : new ClassRoom();
        $teachers = Teacher::select('id', 'firstname', 'lastname')->get();
        $isEdit = !is_null($id);
        
        return view('backend.classes.form', compact('class', 'teachers', 'isEdit'));
    }

    /**
     * Store or update class data based on flag
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_name' => 'required|string|max:255',
            'teacher_id' => 'required|exists:teachers,id',
            'is_update' => 'boolean',
            'class_id' => 'nullable|exists:class_rooms,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only(['class_name', 'teacher_id']);

        if ($request->get('is_update') && $request->get('class_id')) {
            // Update existing class
            $class = ClassRoom::findOrFail($request->get('class_id'));
            $class->update($data);
            $message = 'Class updated successfully!';
        } else {
            // Create new class
            $class = ClassRoom::create($data);
            $message = 'Class registered successfully!';
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
        $class->delete();

        return redirect()->route('classes.index')
            ->with('success', 'Class deleted successfully!');
    }
}