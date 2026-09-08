<?php

namespace JeffersonGoncalves\Optimizely\Resources;

use InvalidArgumentException;

class Experiments extends Resource
{
    /** @return array<string, mixed> */
    public function list(?int $projectId = null, ?string $status = null, int $page = 1, ?int $perPage = null): array
    {
        return $this->client->get('/experiments', $this->pagination($page, $perPage, [
            'project_id' => $this->projectId($projectId),
            'status' => $status,
        ]));
    }

    /** @return array<string, mixed> */
    public function get(int $id): array
    {
        return $this->client->get("/experiments/{$id}");
    }

    /** @return array<string, mixed> */
    public function create(string $name, ?int $projectId = null, string $type = 'a/b', ?int $trafficAllocation = null): array
    {
        if (trim($name) === '') {
            throw new InvalidArgumentException('The "name" argument must not be empty.');
        }

        return $this->client->post('/experiments', array_filter([
            'project_id' => $this->projectId($projectId),
            'name' => $name,
            'type' => $type,
            'status' => 'not_started',
            'traffic_allocation' => $trafficAllocation,
        ], fn (mixed $value) => $value !== null));
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    public function update(int $id, array $attributes): array
    {
        return $this->client->patch("/experiments/{$id}", $attributes);
    }

    /** @return array<string, mixed> */
    public function results(int $id, ?string $startTime = null, ?string $endTime = null): array
    {
        return $this->client->get("/experiments/{$id}/results", array_filter([
            'start_time' => $startTime,
            'end_time' => $endTime,
        ], fn (mixed $value) => $value !== null));
    }

    /** @return array<string, mixed> */
    public function archive(int $id): array
    {
        return $this->update($id, ['status' => 'archived']);
    }
}
