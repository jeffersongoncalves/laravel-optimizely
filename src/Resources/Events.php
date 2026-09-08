<?php

namespace JeffersonGoncalves\Optimizely\Resources;

class Events extends Resource
{
    /** @return array<string, mixed> */
    public function list(?int $projectId = null, int $page = 1, ?int $perPage = null): array
    {
        return $this->client->get('/events', $this->pagination($page, $perPage, [
            'project_id' => $this->projectId($projectId),
        ]));
    }
}
