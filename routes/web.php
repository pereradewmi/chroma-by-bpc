<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\BookingController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('frontend.index');
})->name('home');

// Authentication routes
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Protected routes (require authentication)
Route::middleware('check.login')->group(function () {
    // Dashboard route
    Route::get('/dashboard', function () {
        return view('backend.dashboard');
    })->name('dashboard');

    // Profile routes
    Route::get('/profile', function () {
        return redirect()->route('dashboard');
    })->name('profile.edit');
});

// Frontend routes
Route::get('/Events', function () {
    return view('frontend.events');
})->name('frontend.events');

Route::get('/Classes', function () {
    return view('frontend.classes');
})->name('frontend.classes');

Route::get('/Sessions', function () {
    return view('frontend.sessions');
})->name('frontend.sessions');

Route::get('/EventDetails', function () {
    return view('frontend.event-details');
})->name('frontend.event-details');

Route::get('/Gallery', function () {
    return view('frontend.gallery');
})->name('frontend.gallery');

Route::get('/ContactUs', function () {
    return view('frontend.contact');
})->name('frontend.contact');

Route::get('/Registration', function () {
    return view('frontend.register');
})->name('frontend.register');

// Student routes
Route::middleware('check.login')->prefix('students')->name('students.')->group(function () {
    Route::get('/', [StudentController::class, 'index'])->name('index');
    Route::get('/form/{id?}', [StudentController::class, 'form'])->name('form');
    Route::post('/store', [StudentController::class, 'store'])->name('store');
    Route::delete('/{id}', [StudentController::class, 'destroy'])->name('destroy');
});

// Teacher routes
Route::middleware('check.login')->prefix('teachers')->name('teachers.')->group(function () {
    Route::get('/', [TeacherController::class, 'index'])->name('index');
    Route::get('/form/{id?}', [TeacherController::class, 'form'])->name('form');
    Route::post('/store', [TeacherController::class, 'store'])->name('store');
    Route::delete('/{id}', [TeacherController::class, 'destroy'])->name('destroy');
    Route::get('/dropdown', [TeacherController::class, 'getTeachersForDropdown'])->name('dropdown');
});

// Class routes
Route::middleware('check.login')->prefix('classes')->name('classes.')->group(function () {
    Route::get('/', [ClassRoomController::class, 'index'])->name('index');
    Route::get('/form/{id?}', [ClassRoomController::class, 'form'])->name('form');
    Route::post('/store', [ClassRoomController::class, 'store'])->name('store');
    Route::delete('/{id}', [ClassRoomController::class, 'destroy'])->name('destroy');
});

// Session routes
Route::middleware('check.login')->prefix('sessions')->name('sessions.')->group(function () {
    Route::get('/', [SessionController::class, 'index'])->name('index');
    Route::get('/form/{id?}', [SessionController::class, 'form'])->name('form');
    Route::post('/store', [SessionController::class, 'store'])->name('store');
    Route::delete('/{id}', [SessionController::class, 'destroy'])->name('destroy');
});

// Frontend Calendar routes
Route::middleware('check.login')->prefix('calendar')->name('calendar.')->group(function () {
    Route::get('/', [BookingController::class, 'frontendIndex'])->name('index');
    Route::get('/bookings', [BookingController::class, 'getBookings'])->name('bookings');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('bookings.show');
    Route::put('/bookings/{id}/status', [BookingController::class, 'updateStatus'])->name('bookings.status');
    Route::delete('/bookings/{id}', [BookingController::class, 'destroy'])->name('bookings.destroy');
    Route::get('/stats', [BookingController::class, 'getStats'])->name('stats');
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
    Route::get('/bookings/{id}/logs', [BookingController::class, 'getBookingLogs'])->name('bookings.logs');
});

// Backend booking management
Route::middleware('check.login')->prefix('admin/bookings')->name('admin.bookings.')->group(function () {
    Route::get('/', function () {
        return view('backend.bookings.index');
    })->name('index');
});