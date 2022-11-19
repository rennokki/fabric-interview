<?php

namespace Tests\Feature;

use App\Services\OmdbClient;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OmdbClientTest extends TestCase
{
    public function test_query_movie()
    {
        Http::fake([
            'omdb.test/*' => Http::response([
                'Search' => $this->generateMovies(),
                'totalResults' => '3',
                'Response' => 'True',
            ]),
        ]);

        /** @var OmdbClient&PendingRequest $client */
        $client = $this->app->make(OmdbClient::class);

        $client->search('Matrix But Spacey')->json();

        Http::assertSent(function (Request $request) {
            $this->assertEquals(
                'https://omdb.test/?apikey=test&s=Matrix%20But%20Spacey',
                $request->url(),
            );

            return true;
        });
    }
}
