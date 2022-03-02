<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Yaml\Yaml;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = resource_path('staStations.yaml');
        $yamlContents = collect(Yaml::parse(file_get_contents($path)));
        $toInsert = $yamlContents->map(function ($item) {
            return [
                'id' => $item['stationID'],
                'name' => $item['stationName']
            ];
        });

        DB::table('locations')->insert($toInsert->toArray());
    }
}
