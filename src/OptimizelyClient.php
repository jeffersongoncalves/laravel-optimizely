<?php

namespace JeffersonGoncalves\Optimizely;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\Optimizely\Exceptions\OptimizelyException;

/**
 * Thin wrapper around Laravel's Http client for the Optimizely REST API v2.
 *
 * Optimizely authenticates with a Bearer personal access token on every
 * request, so all verbs funnel through this one place.
 *
 * ponytail: Http::retry() covers transient-failure retries if ever needed —
 * no custom retry/backoff layer built here speculatively.
 */
class OptimizelyClient
{
    public function __construct(
        protected string $apiKey,
        protected string $baseUrl = 'https://api.optimizely.com/v2',
    ) {}

    /** @param array<string, mixed> $query */
    public function get(string $path, array $query = []): array
    {
        return $this->send('get', $path, $query);
    }

    /** @param array<string, mixed> $body */
    public function post(string $path, array $body = []): array
    {
        return $this->send('post', $path, $body);
    }

    /** @param array<string, mixed> $body */
    public function patch(string $path, array $body = []): array
    {
        return $this->send('patch', $path, $body);
    }

    public function delete(string $path): array
    {
        return $this->send('delete', $path);
    }

    /** @param array<string, mixed> $data */
    protected function send(string $method, string $path, array $data = []): array
    {
        /** @var Response $response */
        $response = $this->request()->{$method}($path, $data);

        if ($response->failed()) {
            throw OptimizelyException::fromResponse($response);
        }

        return (array) ($response->json() ?? []);
    }

    protected function request(): PendingRequest
    {
        return Http::withToken($this->apiKey)
            ->acceptJson()
            ->asJson()
            ->baseUrl($this->baseUrl);
    }
}
