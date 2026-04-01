<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeacherController extends Controller
{
    /**
     * Display the teacher registration form
     */
    public function index()
    {
        $teachers = Teacher::latest()->paginate(10);
        return view('backend.teachers.index', compact('teachers'));
    }

    /**
     * Show the form for creating/editing a teacher
     */
    public function form($id = null)
    {
        $teacher = $id ? Teacher::findOrFail($id) : new Teacher();
        $isEdit = !is_null($id);
        
        return view('backend.teachers.form', compact('teacher', 'isEdit'));
    }

    /**
     * Store or update teacher data based on flag
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tFName' => 'required|string|max:255',
            'tLName' => 'required|string|max:255',
            'tAddress' => 'required|string',
            'tMobileNo' => 'required|string|max:20',
            'teacher_type' => 'required|in:class_teacher,instructor',
            'Active' => 'boolean',
            'is_update' => 'boolean',
            'teacher_id' => 'nullable|exists:teacherdetails,T_ID'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only(['tFName', 'tLName', 'tAddress', 'tMobileNo', 'teacher_type']);
        $data['Active'] = $request->has('Active') ? 1 : 0;

        if ($request->get('is_update') && $request->get('teacher_id')) {
            // Update existing teacher
            $teacher = Teacher::findOrFail($request->get('teacher_id'));
            $teacher->update($data);
            $message = 'Teacher updated successfully!';
        } else {
            // Create new teacher
            $teacher = Teacher::create($data);
            $message = 'Teacher registered successfully!';
        }

        return redirect()->route('teachers.index')
            ->with('success', $message);
    }

    /**
     * Delete a teacher
     */
    public function destroy($id)
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->delete();

        return redirect()->route('teachers.index')
            ->with('success', 'Teacher deleted successfully!');
    }

    /**
     * Get all teachers for dropdown
     */
    public function getTeachersForDropdown()
    {
        return Teacher::active()->select('T_ID', 'tFName', 'tLName')->get();
    }
}