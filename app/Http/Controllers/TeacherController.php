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
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));
        $activeFilter = (string) $request->get('active', '1');

        $teachers = Teacher::where('Active', '!=', 2)
            ->where('Active', (int) $activeFilter)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('tFName', 'like', "%{$search}%")
                        ->orWhere('tLName', 'like', "%{$search}%")
                        ->orWhere('tMobileNo', 'like', "%{$search}%")
                        ->orWhere('teacherType', 'like', "%{$search}%")
                        ->orWhere('T_ID', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();
        return view('backend.teachers.index', compact('teachers', 'activeFilter'));
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
            'teacherType' => 'required|in:class_teacher,instructor',
            'Active' => 'boolean',
            'is_update' => 'boolean',
            'teacher_id' => 'nullable|exists:teacherdetails,T_ID'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only(['tFName', 'tLName', 'tAddress', 'tMobileNo', 'teacherType']);
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
        $teacher->update(['Active' => 2]);

        return redirect()->route('teachers.index')
            ->with('success', 'Teacher deleted successfully!');
    }

    /**
     * Update teacher status (1 = Active, 0 = Inactive, 2 = Deleted)
     */
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:0,1,2',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $teacher = Teacher::findOrFail($id);
        $teacher->update(['Active' => (int) $request->status]);

        return redirect()->route('teachers.index')->with('success', 'Teacher status updated successfully!');
    }

    /**
     * Get all teachers for dropdown
     */
    public function getTeachersForDropdown()
    {
        return Teacher::where('Active', 1)->select('T_ID', 'tFName', 'tLName')->get();
    }
}