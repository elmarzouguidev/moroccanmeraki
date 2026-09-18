<?php

namespace Database\Seeders;

use App\Models\Tools\City;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CitySeeder extends Seeder
{
    /**
     * Approximate city-center coordinates used only as a fallback when
     * provider text matching cannot resolve the city name directly.
     *
     * @var array<int, array<string, mixed>>
     */
    private const CITIES = [
        ['name' => 'Casablanca', 'country_id' => 1, 'latitude' => 33.5731104, 'longitude' => -7.5898434],
        ['name' => 'Rabat', 'country_id' => 1, 'latitude' => 34.0208820, 'longitude' => -6.8416500],
        ['name' => 'Marrakech', 'country_id' => 1, 'latitude' => 31.6294723, 'longitude' => -7.9810845],
        ['name' => 'Fes', 'country_id' => 1, 'latitude' => 34.0331300, 'longitude' => -5.0002800],
        ['name' => 'Tangier', 'country_id' => 1, 'latitude' => 35.7594650, 'longitude' => -5.8339540],
        ['name' => 'Agadir', 'country_id' => 1, 'latitude' => 30.4277550, 'longitude' => -9.5981070],
        ['name' => 'Meknes', 'country_id' => 1, 'latitude' => 33.8935200, 'longitude' => -5.5472700],
        ['name' => 'Oujda', 'country_id' => 1, 'latitude' => 34.6813900, 'longitude' => -1.9085800],
        ['name' => 'Kenitra', 'country_id' => 1, 'latitude' => 34.2610100, 'longitude' => -6.5802000],
        ['name' => 'Tetouan', 'country_id' => 1, 'latitude' => 35.5888995, 'longitude' => -5.3625516],
        ['name' => 'Safi', 'country_id' => 1, 'latitude' => 32.2993900, 'longitude' => -9.2371800],
        ['name' => 'El Jadida', 'country_id' => 1, 'latitude' => 33.2335200, 'longitude' => -8.5007100],
        ['name' => 'Nador', 'country_id' => 1, 'latitude' => 35.1681300, 'longitude' => -2.9286600],
        ['name' => 'Laayoune', 'country_id' => 1, 'latitude' => 27.1536110, 'longitude' => -13.2033330],
        ['name' => 'Dakhla', 'country_id' => 1, 'latitude' => 23.6847700, 'longitude' => -15.9579800],
        ['name' => 'Errachidia', 'country_id' => 1, 'latitude' => 31.9314000, 'longitude' => -4.4240000],
        ['name' => 'Ouarzazate', 'country_id' => 1, 'latitude' => 30.9335000, 'longitude' => -6.9370000],
        ['name' => 'Zagora', 'country_id' => 1, 'latitude' => 30.3324000, 'longitude' => -5.8384000],
        ['name' => 'Taroudant', 'country_id' => 1, 'latitude' => 30.4702800, 'longitude' => -8.8769500],
        ['name' => 'Beni Mellal', 'country_id' => 1, 'latitude' => 32.3372500, 'longitude' => -6.3498300],
        ['name' => 'Khouribga', 'country_id' => 1, 'latitude' => 32.8860230, 'longitude' => -6.9208650],
        ['name' => 'Settat', 'country_id' => 1, 'latitude' => 33.0010300, 'longitude' => -7.6166200],
        ['name' => 'Berkane', 'country_id' => 1, 'latitude' => 34.9217800, 'longitude' => -2.3189500],
        ['name' => 'Larache', 'country_id' => 1, 'latitude' => 35.1932100, 'longitude' => -6.1557200],
        ['name' => 'Ksar El Kebir', 'country_id' => 1, 'latitude' => 35.0004400, 'longitude' => -5.9037800],
        ['name' => 'Sidi Kacem', 'country_id' => 1, 'latitude' => 34.2214900, 'longitude' => -5.7077500],
        ['name' => 'Taza', 'country_id' => 1, 'latitude' => 34.2132500, 'longitude' => -4.0106200],
        ['name' => 'Inezgane', 'country_id' => 1, 'latitude' => 30.3560200, 'longitude' => -9.5363900],
        ['name' => 'Khemisset', 'country_id' => 1, 'latitude' => 33.8240400, 'longitude' => -6.0662700],
        ['name' => 'Berrechid', 'country_id' => 1, 'latitude' => 33.2655300, 'longitude' => -7.5875400],
        ['name' => 'Asilah', 'country_id' => 1, 'latitude' => 35.4652200, 'longitude' => -6.0341500],
        ['name' => 'Chefchaouen', 'country_id' => 1, 'latitude' => 35.1687800, 'longitude' => -5.2636000],
        ['name' => 'Oued Zem', 'country_id' => 1, 'latitude' => 32.8627000, 'longitude' => -6.5735900],
        ['name' => 'Sefrou', 'country_id' => 1, 'latitude' => 33.8305200, 'longitude' => -4.8286800],
        ['name' => 'Midelt', 'country_id' => 1, 'latitude' => 32.6828000, 'longitude' => -4.7365700],
        ['name' => 'Azrou', 'country_id' => 1, 'latitude' => 33.4344300, 'longitude' => -5.2212600],
        ['name' => 'Tiznit', 'country_id' => 1, 'latitude' => 29.6974200, 'longitude' => -9.7316200],
        ['name' => 'Youssoufia', 'country_id' => 1, 'latitude' => 32.2463400, 'longitude' => -8.5294100],
        ['name' => 'Guelmim', 'country_id' => 1, 'latitude' => 28.9869600, 'longitude' => -10.0573800],
        ['name' => 'Tiflet', 'country_id' => 1, 'latitude' => 33.8946900, 'longitude' => -6.3064900],
        ['name' => 'Sidi Slimane', 'country_id' => 1, 'latitude' => 34.2647900, 'longitude' => -5.9259800],
        ['name' => 'Fnideq', 'country_id' => 1, 'latitude' => 35.8490600, 'longitude' => -5.3585800],
        ['name' => 'Bouskoura', 'country_id' => 1, 'latitude' => 33.4497600, 'longitude' => -7.6523900],
        ['name' => 'Temara', 'country_id' => 1, 'latitude' => 33.9286600, 'longitude' => -6.9065600],
        ['name' => 'Sale', 'country_id' => 1, 'latitude' => 34.0531000, 'longitude' => -6.7984600],
        ['name' => 'Skhirate', 'country_id' => 1, 'latitude' => 33.8496400, 'longitude' => -7.0315000],
        ['name' => 'Imintanoute', 'country_id' => 1, 'latitude' => 31.1796700, 'longitude' => -8.8622400],
        ['name' => 'Kelaat Sraghna', 'country_id' => 1, 'latitude' => 32.0541600, 'longitude' => -7.4083000],
        ['name' => 'Oulad Teima', 'country_id' => 1, 'latitude' => 30.3946700, 'longitude' => -9.2089700],
        ['name' => 'Ben Guerir', 'country_id' => 1, 'latitude' => 32.2359400, 'longitude' => -7.9549500],
        ['name' => 'Tafraout', 'country_id' => 1, 'latitude' => 29.7244900, 'longitude' => -8.9747000],
        ['name' => 'Demnate', 'country_id' => 1, 'latitude' => 31.7347000, 'longitude' => -7.0050500],
        ['name' => 'Benslimane', 'country_id' => 1, 'latitude' => 33.6189700, 'longitude' => -7.1188000],
        ['name' => 'Taounate', 'country_id' => 1, 'latitude' => 34.5366100, 'longitude' => -4.6400900],
        ['name' => 'Azemmour', 'country_id' => 1, 'latitude' => 33.2895200, 'longitude' => -8.3418200],
        ['name' => 'Oulad Ayad', 'country_id' => 1, 'latitude' => 32.2014000, 'longitude' => -6.7778000],
        ['name' => 'Zaio', 'country_id' => 1, 'latitude' => 34.9428200, 'longitude' => -2.7329000],
        ['name' => 'Targuist', 'country_id' => 1, 'latitude' => 34.9363200, 'longitude' => -4.3185600],
        ['name' => 'Jorf Lasfar', 'country_id' => 1, 'latitude' => 33.1173100, 'longitude' => -8.6378300],
        ['name' => 'Ben Slimane', 'country_id' => 1, 'latitude' => 33.6189700, 'longitude' => -7.1188000],
        ['name' => 'Tiztoutine', 'country_id' => 1, 'latitude' => 34.9715900, 'longitude' => -3.1527300],
        ['name' => 'Asni', 'country_id' => 1, 'latitude' => 31.2488000, 'longitude' => -8.2715000],
        ['name' => 'Tafraoute', 'country_id' => 1, 'latitude' => 29.7244900, 'longitude' => -8.9747000],
        ['name' => 'Imilchil', 'country_id' => 1, 'latitude' => 32.1531400, 'longitude' => -5.6234300],
        ['name' => 'Aït Benhaddou', 'country_id' => 1, 'latitude' => 31.0470000, 'longitude' => -7.1299000],
        ['name' => 'Merzouga', 'country_id' => 1, 'latitude' => 31.0994200, 'longitude' => -4.0120200],
        ['name' => 'Ifrane', 'country_id' => 1, 'latitude' => 33.5228000, 'longitude' => -5.1106000],
        ['name' => 'Azilal', 'country_id' => 1, 'latitude' => 31.9615600, 'longitude' => -6.5710900],
        ['name' => 'Khénifra', 'country_id' => 1, 'latitude' => 32.9357500, 'longitude' => -5.6616700],
    ];

    public function run(): void
    {
        foreach (self::CITIES as $city) {
            City::query()->updateOrCreate(
                ['name' => $city['name']],
                [
                    'country_id' => $city['country_id'],
                    'slug' => Str::slug($city['name']),
                    'latitude' => $city['latitude'],
                    'longitude' => $city['longitude'],
                ]
            );
        }
    }
}
