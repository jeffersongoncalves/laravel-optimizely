<?php

use Illuminate\Http\Client\Response;
use JeffersonGoncalves\Optimizely\Exceptions\OptimizelyException;

function fakeOptimizelyResponse(int $status, array $body): Response
{
    return new Response(new GuzzleHttp\Psr7\Response($status, [], json_encode($body)));
}

it('builds the exception message from the response "message" field', function () {
    $exception = OptimizelyException::fromResponse(fakeOptimizelyResponse(401, ['message' => 'Invalid token']));

    expect($exception->getMessage())->toBe('Invalid token')
        ->and($exception->getCode())->toBe(401)
        ->and($exception->errorBody())->toBe(['message' => 'Invalid token']);
});

it('falls back to the "errors" list when "message" is missing', function () {
    $exception = OptimizelyException::fromResponse(fakeOptimizelyResponse(422, ['errors' => [['message' => 'name is required']]]));

    expect($exception->getMessage())->toBe('name is required');
});

it('falls back to a generic message when the body has no known error keys', function () {
    $exception = OptimizelyException::fromResponse(fakeOptimizelyResponse(500, []));

    expect($exception->getMessage())->toBe('Optimizely API error (HTTP 500).');
});
