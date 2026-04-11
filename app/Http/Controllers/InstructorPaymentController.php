<?php

namespace App\Http\Controllers;

use App\Models\InstructorPayment;
use App\Models\Teacher;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InstructorPaymentController extends Controller
{
    /**
     * Display the instructor payment details management page
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));

        $payments = InstructorPayment::with(['instructor'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('paymentID', 'like', "%{$search}%")
                        ->orWhere('month', 'like', "%{$search}%")
                        ->orWhereHas('instructor', function ($instructorQuery) use ($search) {
                            $instructorQuery->where('tFName', 'like', "%{$search}%")
                                ->orWhere('tLName', 'like', "%{$search}%");
                        })
                        ->orWhereHas('session', function ($sessionQuery) use ($search) {
                            $sessionQuery->where('sName', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('backend.payments.instructor-index', compact('payments'));
    }

    /**
     * Show the instructor payment form
     */
    public function form()
    {
        $instructors = Teacher::where('teacherType', 'instructor')
            ->where('Active', 1)
            ->orderBy('tFName')
            ->get();
        $sessions = Session::where('status', 1)
            ->orderBy('sName')
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

        return view('backend.payments.instructor-form', compact('instructors', 'months', 'sessions'));
    }

    /**
     * Store instructor payment
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'instructor_id' => 'required|exists:teacherdetails,T_ID',
            'session_id' => 'required|exists:sessiondetails,sID',
            'amount' => 'required|numeric|min:0',
            'month' => 'required|string|size:2',
            'sessions_count' => 'required|integer|min:1',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check if payment already exists
        $existingPayment = InstructorPayment::where('instructor_id', $request->instructor_id)
            ->where('session_id', $request->session_id)
            ->where('month', $request->month)
            ->first();

        if ($existingPayment) {
            return redirect()->back()
                ->withErrors(['month' => 'Payment for this instructor and month already exists!'])
                ->withInput();
        }

        InstructorPayment::create([
            'instructor_id' => $request->instructor_id,
            'session_id' => $request->session_id,
            'amount' => $request->amount,
            'month' => $request->month,
            'sessions_count' => $request->sessions_count,
            'description' => $request->description
        ]);

        return redirect()->route('instructor-payments.index')
            ->with('success', 'Instructor payment recorded successfully!');
    }

    /**
     * Show printable receipt for an instructor payment
     */
    public function receipt($id)
    {
        $payment = InstructorPayment::with(['instructor', 'session'])->findOrFail($id);

        return view('backend.payments.instructor-receipt', compact('payment'));
    }

    /**
     * Delete an instructor payment record
     */
    public function destroy($id)
    {
        $payment = InstructorPayment::findOrFail($id);
        $payment->delete();

        return redirect()->route('instructor-payments.index')
            ->with('success', 'Instructor payment record deleted successfully!');
    }
}
