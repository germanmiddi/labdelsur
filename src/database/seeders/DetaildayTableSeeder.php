<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class DetaildayTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\DetailDay::create([
            'num_day' => '1',
            'description' => 'Lunes',
            'cant_orders' => '10'
        ]);

        \App\Models\DetailDay::create([
            'num_day' => '2',
            'description' => 'Martes',
            'cant_orders' => '10'
        ]);

        \App\Models\DetailDay::create([
            'num_day' => '3',
            'description' => 'Miercoles',
            'cant_orders' => '10'
        ]);

        \App\Models\DetailDay::create([
            'num_day' => '4',
            'description' => 'Jueves',
            'cant_orders' => '10'
        ]);

        \App\Models\DetailDay::create([
            'num_day' => '5',
            'description' => 'Viernes',
            'cant_orders' => '10'
        ]);

        \App\Models\DetailDay::create([
            'num_day' => '6',
            'description' => 'Sabado',
            'cant_orders' => '0'
        ]);

        \App\Models\DetailDay::create([
            'num_day' => '0',
            'description' => 'Domingo',
            'cant_orders' => '0'
        ]);

    }
}
