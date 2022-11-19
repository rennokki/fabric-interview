<?php

namespace Tests\Feature;

use App\Jobs\SaveOmdbRecord;
use App\Models\OmdbRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class OmdbQueryTest extends TestCase
{
    use RefreshDatabase;

    public function test_query_for_movies()
    {
        Http::fake([
            'omdb.test/*' => Http::response([
                'Search' => $movies = $this->generateMovies(),
                'totalResults' => "3",
                'Response' => 'True',
            ]),
        ]);

        $this->post(route('api.omdb.query'), ['q' => 'Matri'])
            ->assertExactJson([
                'status' => 'success',
                'data' => $movies,
            ]);

        $this->assertEquals(count($movies), OmdbRecord::count());

        $records = OmdbRecord::all();

        foreach ($movies as $index => $movie) {
            /** @var OmdbRecord $record */
            $record = $records[$index];

            $this->assertEquals($record->id, $movie['imdbID']);
            $this->assertEquals($record->title, $movie['Title']);
            $this->assertEquals($record->year, $movie['Year']);
            $this->assertEquals($record->type, $movie['Type']);

            $this->assertNotNull(
                $poster = $record->posters->first()
            );

            $this->assertEquals($movie['Poster'], $poster->url);
        }

        // Re-calling the endpoint again should not call for save anymore.
        Queue::fake([
            SaveOmdbRecord::class,
        ]);

        DB::listen(function ($query) {
            if (! str_contains($query->sql, 'select')) {
                $this->fail(sprintf('Unexpected insert: %', $query->sql));
            }
        });

        $this->post(route('api.omdb.query'), ['q' => 'Matri'])
            ->assertExactJson([
                'status' => 'success',
                'data' => $movies,
            ]);

        Queue::assertNothingPushed();
    }

    public function test_fail_to_query_movie()
    {
        Queue::fake();

        Http::fake([
            'omdb.test/*' => Http::response([
                'Response' => 'False',
                'Error' => 'Movie not found!',
            ]),
        ]);

        $this->post(route('api.omdb.query'), ['q' => 'omd!'])
            ->assertExactJson([
                'status' => 'error',
                'message' => 'Movie not found!',
                'data' => [],
            ]);

        Queue::assertNothingPushed();
    }

    public function test_fail_to_query_movie_without_error_message()
    {
        Queue::fake();

        Http::fake([
            'omdb.test/*' => Http::response([
                'Success' => 'False',
                'ErrorMessage' => 'Movie not found!',
                'Announcement' => 'We updated the API format overnight. :)',
                'Changes' => [
                    ['description' => 'The "Error" key is now "ErrorMessage".'],
                    ['description' => 'The "Response" key is now "Success".'],
                ],
            ]),
        ]);

        $this->post(route('api.omdb.query'), ['q' => 'omfdb!'])
            ->assertJson([
                'status' => 'error',
                'message' => 'Unexpected error.',
                'data' => [],
            ]);

        Queue::assertNothingPushed();
    }
}
