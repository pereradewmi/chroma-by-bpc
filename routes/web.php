<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ImageCategoryController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\PaymentDetailController;
use App\Models\Event;
use App\Models\Session;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\PaymentDetail;
use App\Models\TeacherPayment;
use Carbon\Carbon;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    $latestEvents = Event::where('status', 1)
        ->orderBy('dateFrom', 'desc')
        ->take(1)
        ->get();

    return view('frontend.index', compact('latestEvents'));
})->name('home');

// Authentication routes
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Protected routes (require authentication)
Route::middleware('check.login')->group(function () {
    // Dashboard route
    Route::get('/admin/dashboard', function () {
        $currentMonth = Carbon::now()->format('m');

        // Students who haven't paid for the current month
        $paidStudentIds = PaymentDetail::where('month', $currentMonth)
            ->pluck('studentID')
            ->unique();

        $unpaidStudents = Student::where('Active', 1)
            ->whereHas('classes')
            ->when($paidStudentIds->isNotEmpty(), function ($query) use ($paidStudentIds) {
                $query->whereNotIn('AutoID', $paidStudentIds);
            })
            ->orderBy('fName')
            ->orderBy('lName')
            ->take(10)
            ->get();

        // Teachers (class teachers) who haven't been paid for the current month
        $paidTeacherIds = TeacherPayment::where('month', $currentMonth)
            ->pluck('teacher_id')
            ->unique();

        $unpaidTeachers = Teacher::where('Active', 1)
            ->where('teacherType', 'class_teacher')
            ->when($paidTeacherIds->isNotEmpty(), function ($query) use ($paidTeacherIds) {
                $query->whereNotIn('T_ID', $paidTeacherIds);
            })
            ->orderBy('tFName')
            ->orderBy('tLName')
            ->take(10)
            ->get();

        return view('backend.dashboard', compact('unpaidStudents', 'unpaidTeachers'));
    })->name('dashboard');

    // Profile routes
    Route::get('/admin/profile', function () {
        return redirect()->route('dashboard');
    })->name('profile.edit');
});

// Frontend routes
Route::get('/Events', [EventController::class, 'frontendIndex'])->name('frontend.events');

Route::get('/Classes', [ClassRoomController::class, 'frontendIndex'])->name('frontend.classes');

Route::get('/Sessions', function () {
    $featuredSession = Session::where('status', 1)->inRandomOrder()->first();

    $topStoriesSessions = Session::where('status', 1)->inRandomOrder()
        ->when($featuredSession, function ($query) use ($featuredSession) {
            $query->where('sID', '!=', $featuredSession->sID);
        })
        ->take(3)
        ->get();

    $newsPostSessions = Session::where('status', 1)->inRandomOrder()->get();

    return view('frontend.sessions', compact('featuredSession', 'topStoriesSessions', 'newsPostSessions'));
})->name('frontend.sessions');

Route::get('/EventDetails/{id}', [EventController::class, 'frontendShow'])->name('frontend.event-details');

Route::get('/Gallery', [ImageController::class, 'frontendIndex'])->name('frontend.gallery');

Route::get('/ContactUs', function () {
    return view('frontend.contact');
})->name('frontend.contact');
Route::post('/ContactUs', [FrontendController::class, 'sendContactMail'])->name('frontend.contact.send');

Route::get('/Registration', [StudentController::class, 'frontendRegister'])->name('frontend.register');
Route::post('/Registration', [StudentController::class, 'frontendStore'])->name('frontend.register.store');

// Frontend Calendar routes (public access - no authentication required)
Route::prefix('Appointment')->name('Appointment.')->group(function () {
    Route::get('/', [BookingController::class, 'frontendIndex'])->name('index');
    Route::get('/bookings', [BookingController::class, 'getBookings'])->name('bookings');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/stats', [BookingController::class, 'getStats'])->name('stats');
});

// Student routes
Route::middleware('check.login')->prefix('admin/students')->name('students.')->group(function () {
    Route::get('/', [StudentController::class, 'index'])->name('index');
    Route::get('/form/{id?}', [StudentController::class, 'form'])->name('form');
    Route::post('/store', [StudentController::class, 'store'])->name('store');
    Route::post('/{id}/status', [StudentController::class, 'updateStatus'])->name('status');
    Route::delete('/{id}', [StudentController::class, 'destroy'])->name('destroy');
});

// Teacher routes
Route::middleware('check.login')->prefix('admin/teachers')->name('teachers.')->group(function () {
    Route::get('/', [TeacherController::class, 'index'])->name('index');
    Route::get('/form/{id?}', [TeacherController::class, 'form'])->name('form');
    Route::post('/store', [TeacherController::class, 'store'])->name('store');
    Route::post('/{id}/status', [TeacherController::class, 'updateStatus'])->name('status');
    Route::delete('/{id}', [TeacherController::class, 'destroy'])->name('destroy');
    Route::get('/dropdown', [TeacherController::class, 'getTeachersForDropdown'])->name('dropdown');
});

// Class routes
Route::middleware('check.login')->prefix('admin/classes')->name('classes.')->group(function () {
    Route::get('/', [ClassRoomController::class, 'index'])->name('index');
    Route::get('/form/{id?}', [ClassRoomController::class, 'form'])->name('form');
    Route::post('/store', [ClassRoomController::class, 'store'])->name('store');
    Route::post('/{id}/status', [ClassRoomController::class, 'updateStatus'])->name('status');
    Route::delete('/{id}', [ClassRoomController::class, 'destroy'])->name('destroy');
});

// Session routes
Route::middleware('check.login')->prefix('admin/sessions')->name('sessions.')->group(function () {
    Route::get('/', [SessionController::class, 'index'])->name('index');
    Route::get('/form/{id?}', [SessionController::class, 'form'])->name('form');
    Route::post('/store', [SessionController::class, 'store'])->name('store');
    Route::post('/{id}/status', [SessionController::class, 'updateStatus'])->name('status');
    Route::delete('/{id}', [SessionController::class, 'destroy'])->name('destroy');
});

// Event routes
Route::middleware('check.login')->prefix('admin/events')->name('events.')->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('index');
    Route::get('/form/{id?}', [EventController::class, 'form'])->name('form');
    Route::post('/store', [EventController::class, 'store'])->name('store');
    Route::post('/{id}/status', [EventController::class, 'updateStatus'])->name('status');
    Route::delete('/{id}', [EventController::class, 'destroy'])->name('destroy');
});

// Payment routes
Route::middleware('check.login')->prefix('admin/payments')->name('payments.')->group(function () {
    Route::get('/', [PaymentDetailController::class, 'index'])->name('index');
    Route::get('/form', [PaymentDetailController::class, 'form'])->name('form');
    Route::get('/search-student', [PaymentDetailController::class, 'searchStudent'])->name('search-student');
    Route::get('/student-details/{id}', [PaymentDetailController::class, 'getStudentDetails'])->name('student-details');
    Route::post('/confirm', [PaymentDetailController::class, 'confirm'])->name('confirm');
    Route::post('/store', [PaymentDetailController::class, 'store'])->name('store');
    Route::get('/{id}/receipt', [PaymentDetailController::class, 'receipt'])->name('receipt');
    Route::delete('/{id}', [PaymentDetailController::class, 'destroy'])->name('destroy');
});

// Teacher Payment routes
Route::middleware('check.login')->prefix('admin/teacher-payments')->name('teacher-payments.')->group(function () {
    Route::get('/', [App\Http\Controllers\TeacherPaymentController::class, 'index'])->name('index');
    Route::get('/form', [App\Http\Controllers\TeacherPaymentController::class, 'form'])->name('form');
    Route::post('/store', [App\Http\Controllers\TeacherPaymentController::class, 'store'])->name('store');
    Route::get('/{id}/receipt', [App\Http\Controllers\TeacherPaymentController::class, 'receipt'])->name('receipt');
    Route::delete('/{id}', [App\Http\Controllers\TeacherPaymentController::class, 'destroy'])->name('destroy');
});

// Instructor Payment routes
Route::middleware('check.login')->prefix('admin/instructor-payments')->name('instructor-payments.')->group(function () {
    Route::get('/', [App\Http\Controllers\InstructorPaymentController::class, 'index'])->name('index');
    Route::get('/form', [App\Http\Controllers\InstructorPaymentController::class, 'form'])->name('form');
    Route::post('/store', [App\Http\Controllers\InstructorPaymentController::class, 'store'])->name('store');
    Route::get('/{id}/receipt', [App\Http\Controllers\InstructorPaymentController::class, 'receipt'])->name('receipt');
    Route::delete('/{id}', [App\Http\Controllers\InstructorPaymentController::class, 'destroy'])->name('destroy');
});

// Backend Calendar routes
Route::middleware('check.login')->prefix('admin/calendar')->name('admin.calendar.')->group(function () {
    Route::get('/', [BookingController::class, 'backendIndex'])->name('index');
    Route::get('/bookings', [BookingController::class, 'getBookings'])->name('bookings');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('bookings.show');
    Route::put('/bookings/{id}/status', [BookingController::class, 'updateStatus'])->name('bookings.status');
    Route::delete('/bookings/{id}', [BookingController::class, 'destroy'])->name('bookings.destroy');
    Route::get('/stats', [BookingController::class, 'getStats'])->name('stats');
    
    // Admin-specific booking management
    Route::post('/bookings/{id}/approve', [BookingController::class, 'approveBooking'])->name('bookings.approve');
    Route::post('/bookings/{id}/reject', [BookingController::class, 'rejectBooking'])->name('bookings.reject');
    Route::put('/bookings/{id}/update', [BookingController::class, 'updateBooking'])->name('bookings.update');
    Route::put('/bookings/{id}/visibility', [BookingController::class, 'updatePubPrivateStatus'])->name('bookings.visibility');
    Route::get('/bookings/{id}/logs', [BookingController::class, 'getBookingLogs'])->name('bookings.logs');
});

// Backend booking management
Route::middleware('check.login')->prefix('admin/bookings')->name('admin.bookings.')->group(function () {
    Route::get('/', function () {
        return view('backend.bookings.index');
    })->name('index');
});

Route::middleware('check.login')->prefix('admin/images')->name('admin.images.')->group(function () {
    Route::get('/', [ImageController::class, 'index'])->name('index');
    Route::get('/form/{id?}', [ImageController::class, 'form'])->name('form');
    Route::post('/store', [ImageController::class, 'store'])->name('store');
    Route::get('/{id}', [ImageController::class, 'show'])->name('show');
    Route::put('/{id}', [ImageController::class, 'update'])->name('update');
    Route::delete('/{id}', [ImageController::class, 'destroy'])->name('destroy');
});

Route::middleware('check.login')->prefix('admin/image-categories')->name('admin.image-categories.')->group(function () {
    Route::get('/', [ImageCategoryController::class, 'index'])->name('index');
    Route::get('/form/{id?}', [ImageCategoryController::class, 'form'])->name('form');
    Route::post('/store', [ImageCategoryController::class, 'store'])->name('store');
    Route::delete('/{id}', [ImageCategoryController::class, 'destroy'])->name('destroy');
});

// Reports routes
Route::middleware('check.login')->prefix('admin/reports')->name('reports.')->group(function () {
    Route::get('/', [ReportsController::class, 'index'])->name('index');
    Route::get('/download', [ReportsController::class, 'download'])->name('download');
    Route::get('/filter-options', [ReportsController::class, 'getFilterOptions'])->name('filter-options');
    Route::get('/user-payments', [ReportsController::class, 'userPayments'])->name('user-payments');
});

