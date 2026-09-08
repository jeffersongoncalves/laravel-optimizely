<?php

namespace JeffersonGoncalves\Optimizely\Resources;

use InvalidArgumentException;
use JeffersonGoncalves\Optimizely\OptimizelyClient;

abstract class Resource
{
    public function __construct(
        protected OptimizelyClient $client,
        protected ?int $projectId = null,
        protected int $defaultPerPage = 25,
    ) {}

    /**
     * Project id from the call, falling back to the configured default.
     */
    protected function projectId(?int $projectId = null): int
    {
        $resolved = $projectId ?? $this->projectId;

        if ($resolved === null) {
            throw new InvalidArgumentException('A project id is required. Pass one or set "optimizely.project_id".');
        }

        return $resolved;
    }

    /**
     * @param  array<string, mixed>  $extra
     * @return array<string, mixed>
     */
    protected function pagination(int $page = 1, ?int $perPage = null, array $extra = []): array
    {
        return array_filter(
            array_merge(['page' => $page, 'per_page' => $perPage ?? $this->defaultPerPage], $extra),
            fn (mixed $value) => $value !== null,
        );
    }
}
