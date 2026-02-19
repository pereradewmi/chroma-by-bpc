<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\SessionController;

Route::get('/', function () {
    return view('frontend.index');
})->name('home');

// Dashboard route
Route::get('/dashboard', function () {
    return view('backend.dashboard');
})->name('dashboard');

// Profile routes (placeholder - you can implement these later)
Route::get('/profile', function () {
    return redirect()->route('dashboard');
})->name('profile.edit');

// Logout route  
Route::post('/logout', function () {
    auth()->logout();
    return redirect('/');
})->name('logout');

// Placeholder routes for template navigation (redirect to dashboard)
Route::get('/user', function () { return redirect()->route('dashboard'); })->name('user.index');
Route::get('/icons', function () { return redirect()->route('dashboard'); })->name('icons');  
Route::get('/map', function () { return redirect()->route('dashboard'); })->name('map');
Route::get('/table', function () { return redirect()->route('dashboard'); })->name('table');
Route::get('/login', function () { return redirect()->route('dashboard'); })->name('login');
Route::get('/register', function () { return redirect()->route('dashboard'); })->name('register');

// Frontend routes
Route::get('/events', function () {
    return view('frontend.events');
})->name('frontend.events');

Route::get('/frontend-classes', function () {
    return view('frontend.classes');
})->name('frontend.classes');

// Student routes
Route::prefix('students')->name('students.')->group(function () {
    Route::get('/', [StudentController::class, 'index'])->name('index');
    Route::get('/form/{id?}', [StudentController::class, 'form'])->name('form');
    Route::post('/store', [StudentController::class, 'store'])->name('store');
    Route::delete('/{id}', [StudentController::class, 'destroy'])->name('destroy');
});

// Teacher routes
Route::prefix('teachers')->name('teachers.')->group(function () {
    Route::get('/', [TeacherController::class, 'index'])->name('index');
    Route::get('/form/{id?}', [TeacherController::class, 'form'])->name('form');
    Route::post('/store', [TeacherController::class, 'store'])->name('store');
    Route::delete('/{id}', [TeacherController::class, 'destroy'])->name('destroy');
    Route::get('/dropdown', [TeacherController::class, 'getTeachersForDropdown'])->name('dropdown');
});

// Class routes
Route::prefix('classes')->name('classes.')->group(function () {
    Route::get('/', [ClassRoomController::class, 'index'])->name('index');
    Route::get('/form/{id?}', [ClassRoomController::class, 'form'])->name('form');
    Route::post('/store', [ClassRoomController::class, 'store'])->name('store');
    Route::delete('/{id}', [ClassRoomController::class, 'destroy'])->name('destroy');
});

// Session routes
Route::prefix('sessions')->name('sessions.')->group(function () {
    Route::get('/', [SessionController::class, 'index'])->name('index');
    Route::get('/form/{id?}', [SessionController::class, 'form'])->name('form');
    Route::post('/store', [SessionController::class, 'store'])->name('store');
    Route::delete('/{id}', [SessionController::class, 'destroy'])->name('destroy');
});