<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\Optimizely\Exceptions\OptimizelyException;
use JeffersonGoncalves\Optimizely\Facades\Optimizely;

it('lists projects with default pagination and a bearer token', function () {
    Http::fake(['*/projects*' => Http::response([['id' => 1, 'name' => 'Web']])]);

    $result = Optimizely::projects()->list();

    expect($result[0]['name'])->toBe('Web');

    Http::assertSent(function ($request) {
        return str_contains((string) $request->url(), '/v2/projects')
            && str_contains((string) $request->url(), 'page=1')
            && str_contains((string) $request->url(), 'per_page=25')
            && $request->hasHeader('Authorization', 'Bearer test-api-key');
    });
});

it('gets a single project', function () {
    Http::fake(['*/projects/7' => Http::response(['id' => 7])]);

    expect(Optimizely::projects()->get(7)['id'])->toBe(7);
});

it('creates a project with an optional platform', function () {
    Http::fake(['*/projects' => Http::response(['id' => 9], 201)]);

    Optimizely::projects()->create('New Project', 'web');

    Http::assertSent(fn ($request) => $request->method() === 'POST'
        && $request['name'] === 'New Project'
        && $request['platform'] === 'web');
});

it('rejects an empty project name', function () {
    Optimizely::projects()->create('  ');
})->throws(InvalidArgumentException::class);

it('lists experiments using the configured project id and a status filter', function () {
    Http::fake(['*/experiments*' => Http::response([['id' => 1]])]);

    Optimizely::experiments()->list(status: 'running');

    Http::assertSent(function ($request) {
        $url = (string) $request->url();

        return str_contains($url, '/v2/experiments')
            && str_contains($url, 'project_id=1234')
            && str_contains($url, 'status=running');
    });
});

it('creates an experiment as not_started', function () {
    Http::fake(['*/experiments' => Http::response(['id' => 5], 201)]);

    Optimizely::experiments()->create('Homepage CTA', trafficAllocation: 5000);

    Http::assertSent(fn ($request) => $request->method() === 'POST'
        && $request['project_id'] === 1234
        && $request['name'] === 'Homepage CTA'
        && $request['type'] === 'a/b'
        && $request['status'] === 'not_started'
        && $request['traffic_allocation'] === 5000);
});

it('archives an experiment through a patch', function () {
    Http::fake(['*/experiments/5' => Http::response(['id' => 5, 'status' => 'archived'])]);

    Optimizely::experiments()->archive(5);

    Http::assertSent(fn ($request) => $request->method() === 'PATCH' && $request['status'] === 'archived');
});

it('fetches experiment results within a time window', function () {
    Http::fake(['*/experiments/5/results*' => Http::response(['metrics' => []])]);

    Optimizely::experiments()->results(5, '2026-01-01', '2026-01-31');

    Http::assertSent(function ($request) {
        $url = (string) $request->url();

        return str_contains($url, 'start_time=2026-01-01') && str_contains($url, 'end_time=2026-01-31');
    });
});

it('lists campaigns, audiences, events and pages scoped to a project', function (string $resource, string $path) {
    Http::fake(["*/{$path}*" => Http::response([])]);

    Optimizely::{$resource}()->list(4321);

    Http::assertSent(fn ($request) => str_contains((string) $request->url(), "/v2/{$path}")
        && str_contains((string) $request->url(), 'project_id=4321'));
})->with([
    ['campaigns', 'campaigns'],
    ['audiences', 'audiences'],
    ['events', 'events'],
    ['pages', 'pages'],
]);

it('fetches campaign results', function () {
    Http::fake(['*/campaigns/3/results' => Http::response(['metrics' => []])]);

    Optimizely::campaigns()->results(3);

    Http::assertSent(fn ($request) => str_contains((string) $request->url(), '/campaigns/3/results'));
});

it('requires a project id when none is configured', function () {
    config()->set('optimizely.project_id', null);
    app()->forgetInstance(JeffersonGoncalves\Optimizely\Optimizely::class);

    Optimizely::clearResolvedInstances();
    Optimizely::events()->list();
})->throws(InvalidArgumentException::class);

it('throws an OptimizelyException on a failed request', function () {
    Http::fake(['*/projects*' => Http::response(['message' => 'Invalid token'], 401)]);

    Optimizely::projects()->list();
})->throws(OptimizelyException::class, 'Invalid token');
