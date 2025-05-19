<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Repositories\EventRepository;
use Illuminate\Http\Request;

class EventController extends Controller
{
    protected $eventRepository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function index(Request $request, $organizationSlug)
    {
        $organization = $request->user()->organization;
        return $this->eventRepository->getUpcomingEvents($organization->id);
    }

    public function store(Request $request, $organizationSlug)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'venue' => 'required|string|max:255',
            'date' => 'required|date',
            'price' => 'required|numeric|min:0',
            'max_attendees' => 'required|integer|min:1'
        ]);

        $data = $request->all();
        $data['organization_id'] = $request->user()->organization_id;
        
        return $this->eventRepository->create($data);
    }

    public function show(Request $request, $organizationSlug, Event $event)
    {
        if ($event->organization_id !== $request->user()->organization_id) {
            abort(403);
        }
        return $event->load('attendees');
    }

    public function update(Request $request, $organizationSlug, Event $event)
    {
        if ($event->organization_id !== $request->user()->organization_id) {
            abort(403);
        }

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'venue' => 'sometimes|string|max:255',
            'date' => 'sometimes|date',
            'price' => 'sometimes|numeric|min:0',
            'max_attendees' => 'sometimes|integer|min:1'
        ]);

        return $this->eventRepository->update($event->id, $request->all());
    }

    public function destroy(Request $request, $organizationSlug, Event $event)
    {
        if ($event->organization_id !== $request->user()->organization_id) {
            abort(403);
        }

        $this->eventRepository->delete($event->id);
        return response()->noContent();
    }
}