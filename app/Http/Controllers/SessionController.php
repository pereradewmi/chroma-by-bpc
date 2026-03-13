<?php

namespace App\Http\Controllers;

use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class SessionController extends Controller
{
    /**
     * Display the sessions list
     */
    public function index()
    {
        $sessions = Session::latest()->paginate(10);
        return view('backend.sessions.index', compact('sessions'));
    }

    /**
     * Show the form for creating/editing a session
     */
    public function form($id = null)
    {
        $session = $id ? Session::findOrFail($id) : new Session();
        $isEdit = !is_null($id);

        return view('backend.sessions.form', compact('session', 'isEdit'));
    }

    /**
     * Store or update session data based on flag
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sName' => 'required|string|max:255',
            'sDescription' => 'nullable|string',
            'sImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
            'is_update' => 'boolean',
            'session_id' => 'nullable|exists:sessiondetails,sID'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only(['sName', 'sDescription']);

        // Handle image upload
        if ($request->hasFile('sImage')) {
            $image = $request->file('sImage');

            // Create storage directory if it doesn't exist
            if (!Storage::exists('public/sessions')) {
                Storage::makeDirectory('public/sessions');
            }

            // Generate unique filename with timestamp
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            // Store image in storage/app/public/sessions/
            $path = $image->storeAs('public/sessions', $filename);

            $data['sImage'] = $filename;
        }

        if ($request->get('is_update') && $request->get('session_id')) {
            // Update existing session
            $session = Session::findOrFail($request->get('session_id'));

            // Delete old image if new image is uploaded
            if ($request->hasFile('sImage') && $session->sImage && Storage::exists('public/sessions/' . $session->sImage)) {
                Storage::delete('public/sessions/' . $session->sImage);
            }

            $session->update($data);
            $message = 'Session updated successfully!';
        } else {
            // Create new session
            $session = Session::create($data);
            $message = 'Session created successfully!';
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

        // Delete image if it exists
        if ($session->sImage && Storage::exists('public/sessions/' . $session->sImage)) {
            Storage::delete('public/sessions/' . $session->sImage);
        }

        $session->delete();

        return redirect()->route('sessions.index')
            ->with('success', 'Session deleted successfully!');
    }
}