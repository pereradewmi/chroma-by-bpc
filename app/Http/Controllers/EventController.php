<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Display the events list for backend
     */
    public function index()
    {
        $events = Event::latest()->paginate(10);
        return view('backend.events.index', compact('events'));
    }

    /**
     * Display the events list for frontend
     */
    public function frontendIndex()
    {
        $events = Event::where('status', 1)->latest('dateFrom')->get();
        $latestEvent = Event::where('status', 1)->latest('dateFrom')->first();
        return view('frontend.events', compact('events', 'latestEvent'));
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
            'dateFrom' => 'required|date',
            'dateTo' => 'required|date|after_or_equal:dateFrom',
            'status' => 'nullable|in:0,1',
            'is_update' => 'boolean',
            'event_id' => 'nullable|exists:eventdetails,eID'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only(['eName', 'eDescription', 'dateFrom', 'dateTo']);
        $data['status'] = $request->get('status', 1); // Default to 1 if not provided

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
     * Show event details for frontend
     */
    public function frontendShow($id)
    {
        $event = Event::where('status', 1)->findOrFail($id);
        $latestEvent = Event::where('status', 1)->latest('dateFrom')->first();
        return view('frontend.event-details', compact('event', 'latestEvent'));
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

    /**
     * Update event status (1 = Active, 0 = Inactive, 2 = Deleted)
     */
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:0,1,2',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $event = Event::findOrFail($id);
        $event->update(['status' => (int) $request->status]);

        return redirect()->route('events.index')->with('success', 'Event status updated successfully!');
    }
}
