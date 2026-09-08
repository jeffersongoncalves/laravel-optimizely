<?php

namespace JeffersonGoncalves\Optimizely\Resources;

use InvalidArgumentException;

class Projects extends Resource
{
    /** @return array<string, mixed> */
    public function list(int $page = 1, ?int $perPage = null): array
    {
        return $this->client->get('/projects', $this->pagination($page, $perPage));
    }

    /** @return array<string, mixed> */
    public function get(int $id): array
    {
        return $this->client->get("/projects/{$id}");
    }

    /** @return array<string, mixed> */
    public function create(string $name, ?string $platform = null): array
    {
        if (trim($name) === '') {
            throw new InvalidArgumentException('The "name" argument must not be empty.');
        }

        return $this->client->post('/projects', array_filter([
            'name' => $name,
            'platform' => $platform,
        ], fn (mixed $value) => $value !== null));
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    public function update(int $id, array $attributes): array
    {
        return $this->client->patch("/projects/{$id}", $attributes);
    }
}
