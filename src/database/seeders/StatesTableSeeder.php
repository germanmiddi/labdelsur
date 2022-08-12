<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class StatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $states = [
                [
                
                'state_ltxt' => 'BUENOS AIRES',
                'state_stxt' => 'BUE',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                ],
                [
                
                'state_ltxt' => 'CATAMARCA',
                'state_stxt' => 'CAT',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                ],
                [
                
                'state_ltxt' => 'CORDOBA',
                'state_stxt' => 'COR',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                ],
                [
                
                'state_ltxt' => 'CAPITAL FEDERAL',
                'state_stxt' => 'CAP',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                ],
                [
                
                'state_ltxt' => 'CHACO',
                'state_stxt' => 'CHA',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                ],
                [
                
                'state_ltxt' => 'CHUBUT',
                'state_stxt' => 'CHU',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                ],
                [
                
                'state_ltxt' => 'CORRIENTES',
                'state_stxt' => 'COR',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                ],
                [
                
                'state_ltxt' => 'ENTRE RIOS',
                'state_stxt' => 'ENT',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                ],
                [
                
                'state_ltxt' => 'FORMOSA',
                'state_stxt' => 'FOR',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                ],
                [
                
                'state_ltxt' => 'JUJUY',
                'state_stxt' => 'JUJ',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                ],
                [
                
                'state_ltxt' => 'LA PAMPA',
                'state_stxt' => 'LA ',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                ],
                [
                
                'state_ltxt' => 'LA RIOJA',
                'state_stxt' => 'LA ',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                ],
                [
                
                'state_ltxt' => 'MENDOZA',
                'state_stxt' => 'MEN',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                ],
                [
                
                'state_ltxt' => 'MISIONES',
                'state_stxt' => 'MIS',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                ],
                [
                
                'state_ltxt' => 'NEUQUEN',
                'state_stxt' => 'NEU',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                ],
                [
                
                'state_ltxt' => 'RIO NEGRO',
                'state_stxt' => 'RIO',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                ],
                [
                
                'state_ltxt' => 'SALTA',
                'state_stxt' => 'SAL',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                ],
                [
                
                'state_ltxt' => 'SANTA CRUZ',
                'state_stxt' => 'SAN',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                ],
                [
                
                'state_ltxt' => 'SANTIAGO DEL ESTERO',
                'state_stxt' => 'SAN',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                ],
                [
                
                'state_ltxt' => 'SANTA FE',
                'state_stxt' => 'SAN',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                ],
                [
                
                'state_ltxt' => 'SAN JUAN',
                'state_stxt' => 'SAN',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                ],
                [
                
                'state_ltxt' => 'SAN LUIS',
                'state_stxt' => 'SAN',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                ],
                [
                
                'state_ltxt' => 'TIERRA DEL FUEGO',
                'state_stxt' => 'TIE',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                ],
                [
                
                'state_ltxt' => 'TUCUMAN',
                'state_stxt' => 'TUC',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                ]
        ];

        State::insert($states);
        
    }
}
