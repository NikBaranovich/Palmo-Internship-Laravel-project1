<?php

namespace Database\Seeders;

use App\Models\EventGenre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventGenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $client = new \GuzzleHttp\Client();

        $response = $client->request('GET', config('endpoints.themoviedb.base_url').'genre/movie/list?language=en', [
            'headers' => [
                'Authorization' => config('endpoints.themoviedb.token'),
                'accept' => 'application/json',
            ],
        ]);


        $genres = json_decode($response->getBody())->genres;

        foreach ($genres as $genre) {
            EventGenre::factory()->create([
                'name' => $genre->name,
                'event_type_id' => 1,
            ]);
        }
    }
}
