<?php

namespace JeffersonGoncalves\Optimizely;

use JeffersonGoncalves\Optimizely\Resources\Audiences;
use JeffersonGoncalves\Optimizely\Resources\Campaigns;
use JeffersonGoncalves\Optimizely\Resources\Events;
use JeffersonGoncalves\Optimizely\Resources\Experiments;
use JeffersonGoncalves\Optimizely\Resources\Pages;
use JeffersonGoncalves\Optimizely\Resources\Projects;

/**
 * Entry point exposing one resource per Optimizely REST API v2 group.
 */
class Optimizely
{
    protected OptimizelyClient $client;

    public function __construct(
        string $apiKey,
        string $baseUrl = 'https://api.optimizely.com/v2',
        protected ?int $projectId = null,
        protected int $defaultPerPage = 25,
    ) {
        $this->client = new OptimizelyClient($apiKey, $baseUrl);
    }

    public function projects(): Projects
    {
        return new Projects($this->client, $this->projectId, $this->defaultPerPage);
    }

    public function experiments(): Experiments
    {
        return new Experiments($this->client, $this->projectId, $this->defaultPerPage);
    }

    public function campaigns(): Campaigns
    {
        return new Campaigns($this->client, $this->projectId, $this->defaultPerPage);
    }

    public function audiences(): Audiences
    {
        return new Audiences($this->client, $this->projectId, $this->defaultPerPage);
    }

    public function events(): Events
    {
        return new Events($this->client, $this->projectId, $this->defaultPerPage);
    }

    public function pages(): Pages
    {
        return new Pages($this->client, $this->projectId, $this->defaultPerPage);
    }
}
