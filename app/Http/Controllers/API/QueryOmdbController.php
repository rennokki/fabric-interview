<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessOmdbResults;
use App\Services\OmdbClient;
use Illuminate\Http\Request;

class QueryOmdbController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Services\OmdbClient  $client
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, OmdbClient $client)
    {
        $request->validate([
            'q' => ['required', 'string', 'max:255'],
        ]);

        $response = $client->search($request->q);
        $payload = $response->json();

        $success = $response->successful()
            && ($payload['Response'] ?? 'False') === 'True'
            && ($payload['Search'] ?? []);

        if (! $success) {
            return response()->json([
                'status' => 'error',
                'message' => $payload['Error'] ?? 'Unexpected error.',
                'data' => $payload['Search'] ?? [],
            ]);
        }

        ProcessOmdbResults::dispatch($payload['Search']);

        return response()->json([
            'status' => 'success',
            'data' => $payload['Search'] ?? [],
        ]);
    }
}
