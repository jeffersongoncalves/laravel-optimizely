<?php

namespace JeffersonGoncalves\Optimizely\Resources;

class Audiences extends Resource
{
    /** @return array<string, mixed> */
    public function list(?int $projectId = null, int $page = 1, ?int $perPage = null): array
    {
        return $this->client->get('/audiences', $this->pagination($page, $perPage, [
            'project_id' => $this->projectId($projectId),
        ]));
    }

    /** @return array<string, mixed> */
    public function get(int $id): array
    {
        return $this->client->get("/audiences/{$id}");
    }
}
