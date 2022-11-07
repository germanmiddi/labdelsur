<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class BookingStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('booking_status')->truncate();

        \App\Models\BookingStatus::create([
            'status' => 'AGENDADO',
        ]);

        \App\Models\BookingStatus::create([
            'status' => 'CANCELADO',
        ]);

        \App\Models\BookingStatus::create([
            'status' => 'FINALIZADO',
        ]);
        
    }
}
