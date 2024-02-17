<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Genre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Event::factory(2)->create();

        for ($i = 1; $i < 5; $i++) {
            $client = new \GuzzleHttp\Client();

            $response = $client->request('GET', config('endpoints.themoviedb.base_url') . "discover/movie?include_adult=false&include_video=false&language=en-US&page={$i}&sort_by=popularity.desc", [
                'headers' => [
                    'Authorization' => config('endpoints.themoviedb.token'),
                    'accept' => 'application/json',
                ],
            ]);
            $events = json_decode($response->getBody())->results;

            foreach ($events as $event) {
                $eventId = $event->id;
                $response = $client->request('GET', config('endpoints.themoviedb.base_url') . "movie/{$eventId}/videos?language=en-US", [
                    'headers' => [
                        'Authorization' => config('endpoints.themoviedb.token'),
                        'accept' => 'application/json',
                    ],
                ]);
                $trailer = null;
                $videos = json_decode($response->getBody())->results;
                if ($videos) {
                    $trailerPos = array_search('Trailer', array_column($videos, 'type'));
                    if ($trailerPos !== false) {
                        $trailer = $videos[$trailerPos];
                    }
                }

                $backdropUrl = "https://image.tmdb.org/t/p/w1280{$event->backdrop_path}";
                $backdropData = file_get_contents($backdropUrl);
                $backdropName = '/' . Str::uuid() . '.jpg';

                Storage::disk('public')->put('events/backdrops' . $backdropName, $backdropData);

                $posterUrl = "https://image.tmdb.org/t/p/w1280{$event->poster_path}";
                $posterData = file_get_contents($posterUrl);
                $posterName = '/' . Str::uuid() . '.jpg';

                Storage::disk('public')->put('events/posters' . $posterName, $posterData);

                $trailerUrl = $trailer ? "https://www.youtube.com/watch?v={$trailer->key}" : null;
                $createdEvent = Event::factory()->create([
                    'title' => $event->title,
                    'overview' => $event->overview,
                    'trailer_url' => $trailerUrl,
                    'poster_path' => $posterName,
                    'backdrop_path' => $backdropName,
                    'event_type_id' => 1,
                    'release_date' => $event->release_date
                ]);

                $response = $client->request('GET', config('endpoints.themoviedb.base_url') . 'genre/movie/list?language=en', [
                    'headers' => [
                        'Authorization' => config('endpoints.themoviedb.token'),
                        'accept' => 'application/json',
                    ],
                ]);


                $genres = json_decode($response->getBody())->genres;
                $genresArray = [];
                foreach ($event->genre_ids as $genre) {
                    $genrePos = array_search($genre, array_column($genres, 'id'));
                    $genreModel = Genre::where('name', $genres[$genrePos]->name)->first();
                    $genresArray[] = $genreModel->id;
                }
                $createdEvent->genres()->sync($genresArray);
            }
        }
    }
}
