<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Attendee;
use Illuminate\Http\Request;

class AttendeeController extends Controller
{
    public function index(Request $request, $organizationSlug, Event $event)
    {
        if ($event->organization_id !== $request->user()->organization_id) {
            abort(403);
        }
        return $event->attendees;
    }

    public function store(Request $request, $organizationSlug, Event $event)
    {
        if ($event->organization_id !== $request->user()->organization_id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20'
        ]);

        if ($event->attendees()->count() >= $event->max_attendees) {
            return response()->json(['message' => 'Event is full'], 422);
        }

        return $event->attendees()->create($request->all());
    }

    public function destroy(Request $request, $organizationSlug, Event $event, Attendee $attendee)
    {
        if ($event->organization_id !== $request->user()->organization_id) {
            abort(403);
        }

        if ($attendee->event_id !== $event->id) {
            abort(404);
        }

        $attendee->delete();
        return response()->noContent();
    }
}