<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use WithFaker;

    protected function generateMovies($movies = 10)
    {
        return Collection::times($movies, function ($time) {
            return [
                'Title' => "The {$this->faker->name()}'s Movie",
                'Year' => mt_rand(1995, 2020),
                'imdbID' => $this->faker->uuid(),
                'Type' => mt_rand(0, 1) ? 'movie' : 'game',
                'Poster' => $this->faker->imageUrl(),
            ];
        })->toArray();
    }
}
