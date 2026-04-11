<?php

namespace App\Http\Controllers;

use App\Models\TeacherPayment;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeacherPaymentController extends Controller
{
    /**
     * Display the teacher payment details management page
     */
    public function index()
    {
        $payments = TeacherPayment::with(['teacher'])
            ->latest()
            ->paginate(15);

        return view('backend.payments.teacher-index', compact('payments'));
    }

    /**
     * Show the teacher payment form
     */
    public function form()
    {
        $teachers = Teacher::where('teacherType', 'class_teacher')
            ->where('Active', 1)
            ->orderBy('tFName')
            ->get();
        $months = [
            '01' => 'January',
            '02' => 'February',
            '03' => 'March',
            '04' => 'April',
            '05' => 'May',
            '06' => 'June',
            '07' => 'July',
            '08' => 'August',
            '09' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December'
        ];

        return view('backend.payments.teacher-form', compact('teachers', 'months'));
    }

    /**
     * Store teacher payment
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'teacher_id' => 'required|exists:teacherdetails,T_ID',
            'amount' => 'required|numeric|min:0',
            'month' => 'required|string|size:2'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check if payment already exists
        $existingPayment = TeacherPayment::where('teacher_id', $request->teacher_id)
            ->where('month', $request->month)
            ->first();

        if ($existingPayment) {
            return redirect()->back()
                ->withErrors(['month' => 'Payment for this teacher and month already exists!'])
                ->withInput();
        }

        TeacherPayment::create([
            'teacher_id' => $request->teacher_id,
            'amount' => $request->amount,
            'month' => $request->month
        ]);

        return redirect()->route('teacher-payments.index')
            ->with('success', 'Teacher payment recorded successfully!');
    }

    /**
     * Delete a teacher payment record
     */
    public function destroy($id)
    {
        $payment = TeacherPayment::findOrFail($id);
        $payment->delete();

        return redirect()->route('teacher-payments.index')
            ->with('success', 'Teacher payment record deleted successfully!');
    }
}
