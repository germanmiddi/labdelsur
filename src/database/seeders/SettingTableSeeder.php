<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Setting::create([
            'module' => 'WP',
            'key' => 'wp_url_media',
            'value' => '-',
            'description' => 'URL del API suministrado por WhatsApp para la subida de archivos multimedia'
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

        \App\Models\Setting::create([
            'module' => 'WP',
            'key' => 'wp_token',
            'value' => '-',
            'description' => 'Token de validacion API WhatsApp'
        ]);

        \App\Models\Setting::create([
            'module' => 'WP',
            'key' => 'wp_url',
            'value' => '-',
            'description' => 'URL del API suministrado por WhatsApp'
        ]);

    }
}
