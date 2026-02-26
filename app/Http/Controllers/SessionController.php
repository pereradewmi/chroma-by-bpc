<?php

namespace App\Http\Controllers;

use App\Models\Session;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SessionController extends Controller
{
    /**
     * Display the session registration form
     */
    public function index()
    {
        $sessions = Session::with('teacher')->latest()->paginate(10);
        return view('backend.sessions.index', compact('sessions'));
    }

    /**
     * Show the form for creating/editing a session
     */
    public function form($id = null)
    {
        $session = $id ? Session::findOrFail($id) : new Session();
        $teachers = Teacher::active()->select('T_ID', 'tFName', 'tLName')->get();
        $isEdit = !is_null($id);
        
        return view('backend.sessions.form', compact('session', 'teachers', 'isEdit'));
    }

    /**
     * Store or update session data based on flag
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session_name' => 'required|string|max:255',
            'teacher_id' => 'required|exists:teachers,id',
            'is_update' => 'boolean',
            'session_id' => 'nullable|exists:sessions,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only(['session_name', 'teacher_id']);

        if ($request->get('is_update') && $request->get('session_id')) {
            // Update existing session
            $session = Session::findOrFail($request->get('session_id'));
            $session->update($data);
            $message = 'Session updated successfully!';
        } else {
            // Create new session
            $session = Session::create($data);
            $message = 'Session registered successfully!';
        }

        return redirect()->route('sessions.index')
            ->with('success', $message);
    }

    /**
     * Delete a session
     */
    public function destroy($id)
    {
        $session = Session::findOrFail($id);
        $session->delete();

        return redirect()->route('sessions.index')
            ->with('success', 'Session deleted successfully!');
    }
}