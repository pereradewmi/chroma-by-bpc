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
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));

        $sessions = Session::where('status', '!=', 2)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('sName', 'like', "%{$search}%")
                        ->orWhere('sDescription', 'like', "%{$search}%")
                        ->orWhere('sID', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();
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
            'status' => 'nullable|in:0,1,2',
            'is_update' => 'boolean',
            'session_id' => 'nullable|exists:sessiondetails,sID'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only(['sName', 'sDescription']);
        $data['status'] = (int) $request->get('status', 1);

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

        $session->update(['status' => 2]);

        return redirect()->route('sessions.index')
            ->with('success', 'Session deleted successfully!');
    }

    /**
     * Update session status (1 = Active, 0 = Inactive, 2 = Deleted)
     */
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:0,1,2',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $session = Session::findOrFail($id);
        $session->update(['status' => (int) $request->status]);

        return redirect()->route('sessions.index')->with('success', 'Session status updated successfully!');
    }
}