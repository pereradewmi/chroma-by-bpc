<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    /**
     * Display the student registration form
     */
    public function index()
    {
        $students = Student::latest()->paginate(10);
        return view('backend.students.index', compact('students'));
    }

    /**
     * Show the form for creating/editing a student
     */
    public function form($id = null)
    {
        $student = $id ? Student::findOrFail($id) : new Student();
        $isEdit = !is_null($id);
        
        return view('backend.students.form', compact('student', 'isEdit'));
    }

    /**
     * Store or update student data based on flag
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fName' => 'required|string|max:255',
            'lName' => 'required|string|max:255',
            'Address' => 'required|string',
            'mobileNo' => 'required|string|max:20',
            'Age' => 'required|integer|min:1|max:100',
            'studentemail' => 'required|email|max:255',
            'studentpic' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'Active' => 'boolean',
            'is_update' => 'boolean',
            'student_id' => 'nullable|exists:studentdetails,AutoID'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only(['fName', 'lName', 'Address', 'mobileNo', 'Age', 'studentemail']);
        $data['Active'] = $request->has('Active') ? 1 : 0;

        // Handle image upload
        if ($request->hasFile('studentpic')) {
            $image = $request->file('studentpic');
            $imageName = time() . '_' . $image->getClientOriginalName();

            // Create directory if it doesn't exist
            $uploadPath = storage_path('app/public/students');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $image->move($uploadPath, $imageName);
            $data['studentpic'] = $imageName;
        }

        if ($request->get('is_update') && $request->get('student_id')) {
            // Update existing student
            $student = Student::findOrFail($request->get('student_id'));

            // Delete old image if new image is uploaded
            if (isset($data['studentpic']) && $student->studentpic && file_exists(storage_path('app/public/students/' . $student->studentpic))) {
                unlink(storage_path('app/public/students/' . $student->studentpic));
            }

            $student->update($data);
            $message = 'Student updated successfully!';
        } else {
            // Create new student
            $student = Student::create($data);
            $message = 'Student registered successfully!';
        }

        return redirect()->route('students.index')
            ->with('success', $message);
    }

    /**
     * Delete a student
     */
    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully!');
    }
}