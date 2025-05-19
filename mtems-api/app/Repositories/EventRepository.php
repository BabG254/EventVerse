<?php

namespace App\Repositories;

use App\Models\Event;

class EventRepository extends BaseRepository
{
    public function __construct(Event $model)
    {
        parent::__construct($model);
    }

    public function getUpcomingEvents($organizationId)
    {
        return $this->model
            ->where('organization_id', $organizationId)
            ->where('date', '>', now())
            ->orderBy('date')
            ->get();
    }
}