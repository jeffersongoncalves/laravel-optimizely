<?php

namespace JeffersonGoncalves\Optimizely\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \JeffersonGoncalves\Optimizely\Optimizely
 *
 * @method static \JeffersonGoncalves\Optimizely\Resources\Projects projects()
 * @method static \JeffersonGoncalves\Optimizely\Resources\Experiments experiments()
 * @method static \JeffersonGoncalves\Optimizely\Resources\Campaigns campaigns()
 * @method static \JeffersonGoncalves\Optimizely\Resources\Audiences audiences()
 * @method static \JeffersonGoncalves\Optimizely\Resources\Events events()
 * @method static \JeffersonGoncalves\Optimizely\Resources\Pages pages()
 */
class Optimizely extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \JeffersonGoncalves\Optimizely\Optimizely::class;
    }
}
