<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // $this->call([
        //     StatesTableSeeder::class,
        //     CitiesTableSeeder::class,
        // ]);

        /* \App\Models\User::create([
            'name' => 'German Middi',
            'email' => 'g@gmail.com',
            'password' => bcrypt('Inicio123')
        ]);  */

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

        \App\Models\Setting::create([
            'module' => 'BOOKING',
            'key' => 'cant_days_booking',
            'value' => '5',
            'description' => 'Cantidad de dias que se ofrecerán como opciones para el usuario'
        ]);

        \App\Models\Setting::create([
            'module' => 'BOOKING',
            'key' => 'hora_limit_booking',
            'value' => '18:00',
            'description' => 'Horario a partir del cual no se otorgan turnos para la fecha actual'
        ]);

        \App\Models\Setting::create([
            'module' => 'BOOKING',
            'key' => 'day_limit_booking',
            'value' => '2022-10-13',
            'description' => 'Fecha limite hasta cuando se otorgarán turno | si esta vacio no posee limite'
        ]);

        \App\Models\BookingStatus::create([
            'status' => 'AGENDADO',
        ]);
        
    }
}
