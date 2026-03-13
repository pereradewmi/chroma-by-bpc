<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Display the events list
     */
    public function index()
    {
        $events = Event::latest()->paginate(10);
        return view('backend.events.index', compact('events'));
    }

    /**
     * Show the form for creating/editing an event
     */
    public function form($id = null)
    {
        $event = $id ? Event::findOrFail($id) : new Event();
        $isEdit = !is_null($id);

        return view('backend.events.form', compact('event', 'isEdit'));
    }

    /**
     * Store or update event data based on flag
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'eName' => 'required|string|max:255',
            'eDescription' => 'nullable|string',
            'eImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
            'is_update' => 'boolean',
            'event_id' => 'nullable|exists:eventdetails,eID'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only(['eName', 'eDescription']);

        // Handle image upload
        if ($request->hasFile('eImage')) {
            $image = $request->file('eImage');

            // Create storage directory if it doesn't exist
            if (!Storage::exists('public/events')) {
                Storage::makeDirectory('public/events');
            }

            // Generate unique filename with timestamp
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            // Store image in storage/app/public/events/
            $path = $image->storeAs('public/events', $filename);

            $data['eImage'] = $filename;
        }

        if ($request->get('is_update') && $request->get('event_id')) {
            // Update existing event
            $event = Event::findOrFail($request->get('event_id'));

            // Delete old image if new image is uploaded
            if ($request->hasFile('eImage') && $event->eImage && Storage::exists('public/events/' . $event->eImage)) {
                Storage::delete('public/events/' . $event->eImage);
            }

            $event->update($data);
            $message = 'Event updated successfully!';
        } else {
            // Create new event
            $event = Event::create($data);
            $message = 'Event created successfully!';
        }

        return redirect()->route('events.index')
            ->with('success', $message);
    }

    /**
     * Delete an event
     */
    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        // Delete image if it exists
        if ($event->eImage && Storage::exists('public/events/' . $event->eImage)) {
            Storage::delete('public/events/' . $event->eImage);
        }

        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Event deleted successfully!');
    }
}
