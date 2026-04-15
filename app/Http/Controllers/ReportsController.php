<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookingLog;
use App\Models\PaymentDetail;
use App\Models\InstructorPayment;
use App\Models\TeacherPayment;
use App\Models\ClassRoom;
use App\Models\Teacher;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Response;

class ReportsController extends Controller
{
    /**
     * Display the reports index page
     */
    public function index(Request $request)
    {
        $query = Booking::query();
        
        // Apply filters
        $filters = $this->applyFilters($query, $request);
        $search = trim((string) $request->get('search', ''));

        if ($search !== '') {
            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('bName', 'like', "%{$search}%")
                    ->orWhere('bEmail', 'like', "%{$search}%")
                    ->orWhere('bPhone', 'like', "%{$search}%")
                    ->orWhere('bTitle', 'like', "%{$search}%")
                    ->orWhere('bEvent_Category', 'like', "%{$search}%")
                    ->orWhere('booking_ID', 'like', "%{$search}%");
            });
        }
        
        // Get paginated results (10 per page)
        $bookings = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        
        // Get summary statistics
        $stats = $this->getStatistics($request);
        
        return view('backend.reports.index', compact('bookings', 'filters', 'stats'));
    }

    /**
     * Display aggregated user payment reports (students, instructors, teachers)
     */
    public function userPayments(Request $request)
    {
        $filters = [
            'class_id' => $request->get('class_id'),
            'teacher_id' => $request->get('teacher_id'),
            'student_id' => $request->get('student_id'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
            'search' => $request->get('search'),
        ];

        $dateFrom = $filters['date_from'] ? Carbon::parse($filters['date_from'])->startOfDay() : null;
        $dateTo = $filters['date_to'] ? Carbon::parse($filters['date_to'])->endOfDay() : null;

        // Student class payments
        $studentQuery = PaymentDetail::with(['student', 'classRoom']);
        if ($filters['class_id']) {
            $studentQuery->where('classID', $filters['class_id']);
        }
        if ($filters['student_id']) {
            $studentQuery->where('studentID', $filters['student_id']);
        }
        if ($dateFrom) {
            $studentQuery->where('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $studentQuery->where('created_at', '<=', $dateTo);
        }
        $studentPayments = $studentQuery->get()->map(function ($p) {
            return [
                'type' => 'Class Fee',
                'source' => 'class',
                'date' => $p->created_at,
                'month' => $p->month,
                'student' => optional($p->student)->fName . ' ' . optional($p->student)->lName,
                'teacher' => null,
                'class' => optional($p->classRoom)->cName,
                'amount' => optional($p->classRoom)->classfee ?? 0,
                'sessions_count' => null,
            ];
        });

        // Instructor payments
        $instructorQuery = InstructorPayment::with(['instructor', 'session']);
        if ($filters['teacher_id']) {
            $instructorQuery->where('instructor_id', $filters['teacher_id']);
        }
        if ($dateFrom) {
            $instructorQuery->where('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $instructorQuery->where('created_at', '<=', $dateTo);
        }
        $instructorPayments = $instructorQuery->get()->map(function ($p) {
            return [
                'type' => 'Instructor Session',
                'source' => 'instructor',
                'date' => $p->created_at,
                'month' => $p->month,
                'student' => null,
                'teacher' => optional($p->instructor)->tFName . ' ' . optional($p->instructor)->tLName,
                'class' => optional($p->session)->sName,
                'amount' => $p->amount,
                'sessions_count' => $p->sessions_count,
            ];
        });

        // Teacher payments
        $teacherQuery = TeacherPayment::with(['teacher']);
        if ($filters['teacher_id']) {
            $teacherQuery->where('teacher_id', $filters['teacher_id']);
        }
        if ($dateFrom) {
            $teacherQuery->where('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $teacherQuery->where('created_at', '<=', $dateTo);
        }
        $teacherPayments = $teacherQuery->get()->map(function ($p) {
            return [
                'type' => 'Teacher Payment',
                'source' => 'teacher',
                'date' => $p->created_at,
                'month' => $p->month,
                'student' => null,
                'teacher' => optional($p->teacher)->tFName . ' ' . optional($p->teacher)->tLName,
                'class' => null,
                'amount' => $p->amount,
                'sessions_count' => null,
            ];
        });

        $paymentsCollection = $studentPayments
            ->concat($instructorPayments)
            ->concat($teacherPayments)
            ->when(!empty($filters['search']), function ($collection) use ($filters) {
                $search = strtolower($filters['search']);

                return $collection->filter(function ($payment) use ($search) {
                    return str_contains(strtolower((string) ($payment['type'] ?? '')), $search)
                        || str_contains(strtolower((string) ($payment['student'] ?? '')), $search)
                        || str_contains(strtolower((string) ($payment['teacher'] ?? '')), $search)
                        || str_contains(strtolower((string) ($payment['class'] ?? '')), $search)
                        || str_contains(strtolower((string) ($payment['month'] ?? '')), $search);
                });
            })
            ->sortByDesc('date')
            ->values();

        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $pagedItems = $paymentsCollection->forPage($currentPage, $perPage)->values();

        $payments = new LengthAwarePaginator(
            $pagedItems,
            $paymentsCollection->count(),
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        // Dropdown options
        $classes = ClassRoom::orderBy('cName')->get();
        $teachers = Teacher::where('Active', '!=', 2)->orderBy('tFName')->get();
        $students = Student::where('Active', '!=', 2)->orderBy('fName')->get();

        return view('backend.reports.user-payments', compact('payments', 'filters', 'classes', 'teachers', 'students'));
    }

    /**
     * Download report as Excel/CSV
     */
    public function download(Request $request)
    {
        $query = Booking::query();
        $this->applyFilters($query, $request);
        
        $bookings = $query->orderBy('created_at', 'desc')->get();
        
        $format = $request->get('format', 'csv');
        
        if ($format === 'excel') {
            return $this->generateExcel($bookings, $request);
        }
        
        return $this->generateCSV($bookings, $request);
    }

    /**
     * Apply filters to the query
     */
    private function applyFilters($query, Request $request)
    {
        $filters = [
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
            'event_type' => $request->get('event_type'),
            'payment_status' => $request->get('payment_status'),
            'status' => $request->get('status'),
            'event_category' => $request->get('event_category'),
        ];

        // Date range filter
        if ($filters['date_from']) {
            $query->whereDate('booking_date', '>=', Carbon::parse($filters['date_from']));
        }
        
        if ($filters['date_to']) {
            $query->whereDate('booking_date', '<=', Carbon::parse($filters['date_to']));
        }

        // Event type filter
        if ($filters['event_type'] && $filters['event_type'] !== 'all') {
            $query->where('bEvent_type', $filters['event_type']);
        }

        // Payment status filter
        if ($filters['payment_status'] && $filters['payment_status'] !== 'all') {
            $query->where('bPayment_status', $filters['payment_status']);
        }

        // Booking status filter
        if ($filters['status'] && $filters['status'] !== 'all') {
            $query->where('bStatus', $filters['status']);
        }

        // Event category filter
        if ($filters['event_category'] && $filters['event_category'] !== 'all') {
            $query->where('bEvent_Category', $filters['event_category']);
        }

        return array_filter($filters); // Remove null values
    }

    /**
     * Get statistics for the filtered data
     */
    private function getStatistics(Request $request)
    {
        $query = Booking::query();
        $this->applyFilters($query, $request);
        
        $totalBookings = $query->count();
        $totalRevenue = $query->sum('bPrice');
        
        // Status breakdown
        $statusStats = [
            'pending' => (clone $query)->where('bStatus', Booking::STATUS_PENDING)->count(),
            'approved' => (clone $query)->where('bStatus', Booking::STATUS_APPROVED)->count(),
            'rejected' => (clone $query)->where('bStatus', Booking::STATUS_REJECTED)->count(),
        ];

        // Payment status breakdown
        $paymentStats = [
            'pending' => (clone $query)->where('bPayment_status', Booking::PAYMENT_PENDING)->count(),
            'paid' => (clone $query)->where('bPayment_status', Booking::PAYMENT_PAID)->count(),
            'refunded' => (clone $query)->where('bPayment_status', Booking::PAYMENT_REFUNDED)->count(),
        ];

        return [
            'total_bookings' => $totalBookings,
            'total_revenue' => $totalRevenue,
            'status' => $statusStats,
            'payments' => $paymentStats,
        ];
    }

    /**
     * Generate CSV download
     */
    private function generateCSV($bookings, Request $request)
    {
        $filename = 'bookings_report_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($bookings) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Booking ID',
                'Name',
                'Email',
                'Phone',
                'Booking Date',
                'Start Date/Time',
                'End Date/Time',
                'Title',
                'Description',
                'Event Type',
                'Event Category',
                'Status',
                'Payment Status',
                'Price',
                'Approved By',
                'Approved At',
                'Rejected By',
                'Rejected At',
                'Rejection Reason'
            ]);

            // CSV data
            foreach ($bookings as $booking) {
                fputcsv($file, [
                    $booking->booking_ID,
                    $booking->bName,
                    $booking->bEmail,
                    $booking->bPhone,
                    $booking->booking_date ? $booking->booking_date->format('Y-m-d') : '',
                    $booking->bStart_datetime ? $booking->bStart_datetime->format('Y-m-d H:i:s') : '',
                    $booking->bEnd_datetime ? $booking->bEnd_datetime->format('Y-m-d H:i:s') : '',
                    $booking->bTitle,
                    $booking->bDescription,
                    $booking->bEvent_type,
                    $booking->bEvent_Category,
                    ucfirst($booking->bStatus),
                    ucfirst($booking->bPayment_status),
                    $booking->bPrice,
                    $booking->bApproved_by,
                    $booking->bApproved_at ? $booking->bApproved_at->format('Y-m-d H:i:s') : '',
                    $booking->bReject_by,
                    $booking->bReject_at ? $booking->bReject_at->format('Y-m-d H:i:s') : '',
                    $booking->bRejection_reason
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Generate Excel download (using simple HTML table format)
     */
    private function generateExcel($bookings, Request $request)
    {
        $filename = 'bookings_report_' . Carbon::now()->format('Y-m-d_H-i-s') . '.xls';
        
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $content = '<table border="1">';
        
        // Excel headers
        $content .= '<tr>';
        $content .= '<th>Booking ID</th>';
        $content .= '<th>Name</th>';
        $content .= '<th>Email</th>';
        $content .= '<th>Phone</th>';
        $content .= '<th>Booking Date</th>';
        $content .= '<th>Start Date/Time</th>';
        $content .= '<th>End Date/Time</th>';
        $content .= '<th>Title</th>';
        $content .= '<th>Description</th>';
        $content .= '<th>Event Type</th>';
        $content .= '<th>Event Category</th>';
        $content .= '<th>Status</th>';
        $content .= '<th>Payment Status</th>';
        $content .= '<th>Price</th>';
        $content .= '<th>Approved By</th>';
        $content .= '<th>Approved At</th>';
        $content .= '<th>Rejected By</th>';
        $content .= '<th>Rejected At</th>';
        $content .= '<th>Rejection Reason</th>';
        $content .= '</tr>';

        // Excel data
        foreach ($bookings as $booking) {
            $content .= '<tr>';
            $content .= '<td>' . htmlspecialchars($booking->booking_ID) . '</td>';
            $content .= '<td>' . htmlspecialchars($booking->bName) . '</td>';
            $content .= '<td>' . htmlspecialchars($booking->bEmail) . '</td>';
            $content .= '<td>' . htmlspecialchars($booking->bPhone) . '</td>';
            $content .= '<td>' . ($booking->booking_date ? $booking->booking_date->format('Y-m-d') : '') . '</td>';
            $content .= '<td>' . ($booking->bStart_datetime ? $booking->bStart_datetime->format('Y-m-d H:i:s') : '') . '</td>';
            $content .= '<td>' . ($booking->bEnd_datetime ? $booking->bEnd_datetime->format('Y-m-d H:i:s') : '') . '</td>';
            $content .= '<td>' . htmlspecialchars($booking->bTitle) . '</td>';
            $content .= '<td>' . htmlspecialchars($booking->bDescription) . '</td>';
            $content .= '<td>' . htmlspecialchars($booking->bEvent_type) . '</td>';
            $content .= '<td>' . htmlspecialchars($booking->bEvent_Category) . '</td>';
            $content .= '<td>' . ucfirst($booking->bStatus) . '</td>';
            $content .= '<td>' . ucfirst($booking->bPayment_status) . '</td>';
            $content .= '<td>' . $booking->bPrice . '</td>';
            $content .= '<td>' . htmlspecialchars($booking->bApproved_by) . '</td>';
            $content .= '<td>' . ($booking->bApproved_at ? $booking->bApproved_at->format('Y-m-d H:i:s') : '') . '</td>';
            $content .= '<td>' . htmlspecialchars($booking->bReject_by) . '</td>';
            $content .= '<td>' . ($booking->bReject_at ? $booking->bReject_at->format('Y-m-d H:i:s') : '') . '</td>';
            $content .= '<td>' . htmlspecialchars($booking->bRejection_reason) . '</td>';
            $content .= '</tr>';
        }

        $content .= '</table>';

        return Response::make($content, 200, $headers);
    }

    /**
     * Get unique dropdown values for filters
     */
    public function getFilterOptions()
    {
        $eventTypes = Booking::distinct()->pluck('bEvent_type')->filter()->sort();
        $eventCategories = Booking::distinct()->pluck('bEvent_Category')->filter()->sort();
        
        return response()->json([
            'event_types' => $eventTypes,
            'event_categories' => $eventCategories,
            'statuses' => [
                Booking::STATUS_PENDING => 'Pending',
                Booking::STATUS_APPROVED => 'Approved',
                Booking::STATUS_REJECTED => 'Rejected'
            ],
            'payment_statuses' => [
                Booking::PAYMENT_PENDING => 'Pending',
                Booking::PAYMENT_PAID => 'Paid',
                Booking::PAYMENT_REFUNDED => 'Refunded'
            ]
        ]);
    }
}