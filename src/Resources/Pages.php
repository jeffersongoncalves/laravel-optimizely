<?php

namespace JeffersonGoncalves\Optimizely\Resources;

class Pages extends Resource
{
    /** @return array<string, mixed> */
    public function list(?int $projectId = null, int $page = 1, ?int $perPage = null): array
    {
        return $this->client->get('/pages', $this->pagination($page, $perPage, [
            'project_id' => $this->projectId($projectId),
        ]));
    }
}
