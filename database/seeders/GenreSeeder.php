<?php

namespace Database\Seeders;

use App\Models\EventGenre;
use App\Models\Genre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
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
            Genre::factory()->create([
                'name' => $genre->name,
                'event_type_id' => 1,
            ]);
        }
    }
}
