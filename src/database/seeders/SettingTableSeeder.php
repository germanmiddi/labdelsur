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
        
    }
}
