<?php

namespace App\Jobs;

use App\Models\OmdbRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * @method static void dispatch(array $results)
 */
class ProcessOmdbResults implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param  array  $results
     * @return void
     */
    public function __construct(protected array $results)
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
        $storedRecords = OmdbRecord::findMany(
            collect($this->results)->pluck('imdbID'),
        );

        foreach ($this->results as $result) {
            if (! $storedRecords->firstWhere('id', $result['imdbID'])) {
                SaveOmdbRecord::dispatch($result);
            }
        }
    }
}
