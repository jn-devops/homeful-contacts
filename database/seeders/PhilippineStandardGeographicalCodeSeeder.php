<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
//use DB;

class PhilippineStandardGeographicalCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(!DB::connection('address-sqlite')->table('philippine_regions')->count()) {
            DB::connection('address-sqlite')->unprepared(file_get_contents(__DIR__ . '/sql/philippine_regions.sql'));
        }
        if(!DB::connection('address-sqlite')->table('philippine_provinces')->count()) {
            DB::connection('address-sqlite')->unprepared(file_get_contents(__DIR__ . '/sql/philippine_provinces.sql'));
        }
        if(!DB::connection('address-sqlite')->table('philippine_cities')->count()) {
            DB::connection('address-sqlite')->unprepared(file_get_contents(__DIR__ . '/sql/philippine_cities.sql'));
        }
        if(!DB::connection('address-sqlite')->table('philippine_barangays')->count()) {
            DB::connection('address-sqlite')->unprepared(file_get_contents(__DIR__ . '/sql/philippine_barangays112324.sql'));
        }
    }
}
