<?php

namespace App\Services;

use GuzzleHttp\RequestOptions;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

/**
 * @mixin \Illuminate\Http\Client\PendingRequest
 */
class OmdbClient
{
    public function __construct(
        protected string $baseUrl,
        protected string $apiKey,
        protected ?PendingRequest $client = null,
    ) {
        $this->client = Http::baseUrl($baseUrl)
            ->withOptions([
                RequestOptions::QUERY => [
                    'apikey' => $apiKey,
                ],
            ]);
    }

    /**
     * Search a movie.
     *
     * @param  string  $query
     * @return \Illuminate\Http\Client\Response
     */
    public function search(string $query)
    {
        return $this->client->get('/', ['s' => $query]);
    }

    /**
     * Proxy the call to the underlying client.
     *
     * @param  string  $method
     * @param  array  $params
     * @return mixed
     */
    public function __call($method, $params)
    {
        return $this->client->{$method}(...$params);
    }
}
