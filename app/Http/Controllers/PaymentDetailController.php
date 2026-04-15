<?php

namespace App\Http\Controllers;

use App\Models\PaymentDetail;
use App\Models\Student;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\PaymentNotificationMail;
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentDetailController extends Controller
{
    /**
     * Display the payment details management page
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));

        $payments = PaymentDetail::with(['student', 'classRoom'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('paymentID', 'like', "%{$search}%")
                        ->orWhere('month', 'like', "%{$search}%")
                        ->orWhereHas('student', function ($studentQuery) use ($search) {
                            $studentQuery->where('fName', 'like', "%{$search}%")
                                ->orWhere('lName', 'like', "%{$search}%");
                        })
                        ->orWhereHas('classRoom', function ($classQuery) use ($search) {
                            $classQuery->where('cName', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
			->paginate(10)
            ->withQueryString();

        return view('backend.payments.index', compact('payments'));
    }

    /**
     * Show the payment form
     */
    public function form()
    {
        $classes = ClassRoom::orderBy('cName')->get();
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
        $paymentTypes = [
            'class_fee' => 'Class Fee',
            'admission' => 'Admission'
        ];

        return view('backend.payments.form', compact('classes', 'months', 'paymentTypes'));
    }

    /**
     * Search for students by name or ID
     */
    public function searchStudent(Request $request)
    {
        $query = $request->get('query');

        if (empty($query)) {
            return response()->json([]);
        }

        $students = Student::where('fName', 'LIKE', "%{$query}%")
            ->orWhere('lName', 'LIKE', "%{$query}%")
            ->orWhere('AutoID', '=', $query)
            ->where('Active', 1)
            ->limit(10)
            ->get()
            ->map(function($student) {
                return [
                    'id' => $student->AutoID,
                    'name' => $student->fName . ' ' . $student->lName,
                    'mobile' => $student->mobileNo,
                    'email' => $student->studentemail ?? 'N/A',
                    'age' => $student->Age,
                    'address' => $student->Address
                ];
            });

        return response()->json($students);
    }

    /**
     * Get student details by ID
     */
    public function getStudentDetails($id)
    {
        $student = Student::findOrFail($id);

        return response()->json([
            'id' => $student->AutoID,
            'name' => $student->fName . ' ' . $student->lName,
            'mobile' => $student->mobileNo,
            'email' => $student->studentemail ?? 'N/A',
            'age' => $student->Age,
            'address' => $student->Address
        ]);
    }

    /**
     * Store payment details (first step - confirmation)
     */
    public function confirm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'studentID' => 'required|exists:studentdetails,AutoID',
            'classID' => 'required|exists:classdetails,cID',
            'month' => 'required|string|size:2'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if payment already exists for this student, class and month
        $existingPayment = PaymentDetail::where('studentID', $request->studentID)
            ->where('classID', $request->classID)
            ->where('month', $request->month)
            ->first();

        if ($existingPayment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment for this student, class and month already exists!'
            ], 422);
        }

        $student = Student::findOrFail($request->studentID);
        $class = ClassRoom::findOrFail($request->classID);

        $months = [
            '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
            '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August',
            '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'student' => [
                    'id' => $student->AutoID,
                    'name' => $student->fName . ' ' . $student->lName,
                    'mobile' => $student->mobileNo,
                    'email' => $student->studentemail ?? 'N/A'
                ],
                'class' => [
                    'id' => $class->cID,
                    'name' => $class->cName,
                    'fee' => $class->classfee ?? 0
                ],
                'month' => [
                    'code' => $request->month,
                    'name' => $months[$request->month] ?? 'Unknown'
                ]
            ]
        ]);
    }

    /**
     * Store payment details (second step - final confirmation)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'studentID' => 'required|exists:studentdetails,AutoID',
            'classID' => 'required|exists:classdetails,cID',
            'month' => 'required|string|size:2',
            'payment_type' => 'nullable|in:class_fee,admission'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Double check if payment already exists
        $existingPayment = PaymentDetail::where('studentID', $request->studentID)
            ->where('classID', $request->classID)
            ->where('month', $request->month)
            ->where('payment_type', $request->payment_type ?? 'class_fee')
            ->first();

        if ($existingPayment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment for this student, class and month already exists!'
            ], 422);
        }

        // Create the payment record
        $payment = PaymentDetail::create([
            'studentID' => $request->studentID,
            'classID' => $request->classID,
            'month' => $request->month,
            'payment_type' => $request->payment_type ?? 'class_fee'
        ]);

        // Get student and class information for email
        $student = Student::findOrFail($request->studentID);
        $classRoom = ClassRoom::findOrFail($request->classID);

        // Send payment notification email if student has email
        if ($student->studentemail) {
            try {
                Mail::to($student->studentemail)->send(
                    new PaymentNotificationMail($student, $classRoom, $request->month)
                );
            } catch (\Exception $e) {
                // Log the error but don't fail the payment creation
                \Log::error('Failed to send payment notification email: ' . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment recorded successfully!',
            'payment_id' => $payment->paymentID
        ]);
    }

    /**
     * Show printable receipt for a student payment
     */
    public function receipt($id)
    {
        $payment = PaymentDetail::with(['student', 'classRoom'])->findOrFail($id);

        if (request()->query('download') == 1) {
            $pdf = Pdf::loadView('backend.payments.student-receipt', compact('payment'));

            $fileName = 'student-receipt-' . ($payment->paymentID ?? $payment->id) . '.pdf';

            return $pdf->download($fileName);
        }

        return view('backend.payments.student-receipt', compact('payment'));
    }

    /**
     * Delete a payment record
     */
    public function destroy($id)
    {
        $payment = PaymentDetail::findOrFail($id);
        $payment->delete();

        return redirect()->route('payments.index')
            ->with('success', 'Payment record deleted successfully!');
    }
}