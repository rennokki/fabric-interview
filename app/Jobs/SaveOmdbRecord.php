<?php

namespace App\Jobs;

use App\Models\OmdbRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * @method static void dispatch(array $result)
 */
class SaveOmdbRecord implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param  array  $result
     * @return void
     */
    public function __construct(protected array $result)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Unnecessary tap but i did it for the show.
        // Taylor loves it too: https://medium.com/@taylorotwell/tap-tap-tap-1fc6fc1f93a6

        tap(
            value: OmdbRecord::create([
                'id' => $this->result['imdbID'],
                'title' => $this->result['Title'],
                'year' => $this->result['Year'],
                'type' => $this->result['Type'],
            ]),
            callback: function (OmdbRecord $record) {
                $posterUrl = $this->result['Poster'] ?? null;

                if ($posterUrl && $posterUrl !== 'N/A') {
                    $record->posters()->create([
                        'url' => $posterUrl,
                    ]);
                }
            },
        );
    }
}
