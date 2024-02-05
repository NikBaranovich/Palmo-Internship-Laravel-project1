<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            'Kyiv', 'Baranovka', 'Bakhmut', 'Bila Tserkva', 'Berdychiv', 'Berdiansk', 'Boryspil', 'Brovary', 'Bucha',
            'Varash', 'Vasylkiv', 'Vinnytsia', 'Vyshneve', 'Dnipro', 'Drohobych', 'Yevpatoria', 'Zhytomyr', 'Zaporizhzhia',
            'Ivano-Frankivsk', 'Izmail', 'Irpin', 'Kalinovka', 'Kalush', 'Kamianets-Podilskyi', 'Kerch', 'Kropyvnytskyi',
            'Kovel', 'Kolomyia', 'Kramatorsk', 'Kremenchuk', 'Kryvyi Rih', 'Lubny', 'Lutsk', 'Lviv', 'Mariupol',
            'Melitopol', 'Myrhorod', 'Mukachevo', 'Mykolaiv', 'Nikopol', 'Nova Kakhovka', 'Odesa', 'Pavlohrad',
            'Pokrov', 'Poltava', 'Rivne', 'Sevastopol', 'Severodonetsk', 'Simferopol', 'Stryi', 'Sumy', 'Ternopil',
            'Uzhhorod', 'Uman', 'Fastiv', 'Kharkiv', 'Kherson', 'Khmelnytskyi', 'Khust', 'Cherkasy', 'Chernihiv',
            'Chernivtsi', 'Yuzhny', 'Yalta',
        ];
        foreach ($cities as $city) {
            City::factory()->create([
                'name' => $city
            ]);
        }
    }
}
