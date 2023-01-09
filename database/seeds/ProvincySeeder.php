<?php

use App\City;
use App\Provincy;
use Illuminate\Database\Seeder;
use Kavist\RajaOngkir\Facades\RajaOngkir;

class ProvincySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $provincies = RajaOngkir::provinsi()->all();

        foreach ($provincies as $provincy) {
            $provincyResult = Provincy::create(['name' => $provincy['province']]);

            $cities = RajaOngkir::kota()->dariProvinsi($provincy['province_id'])->get();
            foreach ($cities as $city) {
                City::create([
                    'provincy_id' => $provincyResult['id'],
                    'name' => $city['city_name'],
                    'type' => $city['type'],
                    'postal_code' => $city['postal_code']
                ]);
            }
        }
    }
}
