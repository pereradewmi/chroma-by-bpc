<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    /**
     * Display the student registration form
     */
    public function frontendRegister()
    {
        $student = new Student();
        $isEdit = false;
        $classes = ClassRoom::orderBy('cName')->get();

        return view('frontend.register', compact('student', 'isEdit', 'classes'));
    }

    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));

        $students = Student::where('Active', '!=', 2)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('fName', 'like', "%{$search}%")
                        ->orWhere('lName', 'like', "%{$search}%")
                        ->orWhere('mobileNo', 'like', "%{$search}%")
                        ->orWhere('studentemail', 'like', "%{$search}%")
                        ->orWhere('guardian_name', 'like', "%{$search}%")
                        ->orWhere('AutoID', 'like', "%{$search}%");
                });
            })
            ->with('classes')
            ->latest()
            ->paginate(10)
            ->withQueryString();
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
            'guardian_name' => 'required|string|max:255',
            'guardian_phone' => 'required|string|max:20',
            'class_ids' => 'nullable|array',
            'class_ids.*' => 'exists:classdetails,cID',
            'Active' => 'boolean',
            'is_update' => 'boolean',
            'student_id' => 'nullable|exists:studentdetails,AutoID'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        [$student, $message] = $this->saveStudentData($request);

        return redirect()->route('students.index')
            ->with('success', $message);
    }

    /**
     * Store a public frontend student registration.
     */
    public function frontendStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fName' => 'required|string|max:255',
            'lName' => 'required|string|max:255',
            'Address' => 'required|string',
            'mobileNo' => 'required|string|max:20',
            'Age' => 'required|integer|min:1|max:100',
            'studentemail' => 'required|email|max:255',
            'studentpic' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'guardian_name' => 'required|string|max:255',
            'guardian_phone' => 'required|string|max:20',
            'class_ids' => 'nullable|array',
            'class_ids.*' => 'exists:classdetails,cID',
            'Active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $request->merge(['Active' => 0]);
        $this->saveStudentData($request, false, 0);

        return redirect()->route('frontend.register')
            ->with('success', 'Student registered successfully!');
    }

    /**
     * Delete a student
     */
    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->update(['Active' => 2]);

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully!');
    }

    /**
     * Update student status (1 = Active, 0 = Inactive, 2 = Deleted)
     */
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:0,1,2',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $student = Student::findOrFail($id);
        $student->update(['Active' => (int) $request->status]);

        return redirect()->route('students.index')->with('success', 'Student status updated successfully!');
    }

    private function saveStudentData(Request $request, bool $allowUpdate = true, ?int $forceActive = null): array
    {
        $data = $request->only(['fName', 'lName', 'Address', 'mobileNo', 'Age', 'studentemail', 'guardian_name', 'guardian_phone']);
        $data['Active'] = $forceActive ?? ($request->boolean('Active') ? 1 : 0);
        $data['class_ids'] = json_encode($request->get('class_ids', []));

        if ($request->hasFile('studentpic')) {
            $image = $request->file('studentpic');
            $imageName = time() . '_' . $image->getClientOriginalName();

            $uploadPath = storage_path('app/public/students');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $image->move($uploadPath, $imageName);
            $data['studentpic'] = $imageName;
        }

        if ($allowUpdate && $request->get('is_update') && $request->get('student_id')) {
            $student = Student::findOrFail($request->get('student_id'));

            if (isset($data['studentpic']) && $student->studentpic && file_exists(storage_path('app/public/students/' . $student->studentpic))) {
                unlink(storage_path('app/public/students/' . $student->studentpic));
            }

            $student->update($data);
            $message = 'Student updated successfully!';
        } else {
            $student = Student::create($data);
            $message = 'Student registered successfully!';
        }

        if (Schema::hasTable('student_classes')) {
            $student->classes()->sync($request->get('class_ids', []));
        }

        return [$student, $message];
    }
}