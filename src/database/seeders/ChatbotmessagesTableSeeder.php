<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Chatbotmessage;

class ChatbotmessagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        Chatbotmessage::create([
            'name' => 'covid_valor',
            'step' => '0️⃣ Menu Principal / 6️⃣ COVID 19 / 1️⃣ Importe del estudio particular',
            'message' => 'El hisopado PCR  para SARS-CoV-2 🦠​ tiene un valor de $7.900 pesos con tarjeta de débito y $7.000 si abona en efectivo. Puede venir de lunes a viernes de 11:00 a 15:00 hs y sábados de 8:00 a 9:00 hs. Si desea los resultados en el día debería acercarse a las 11:00 o a las 8:00 hs. respectivamente. \n\nEl test rápido para SARS-CoV-2 tiene un valor de $4.600 pesos con tarjeta de débito y $4.000 en efectivo. En caso de que quiera realizarlo puede venir de lunes a viernes de 11:00 a 15:00 hs. y sábado de 8:00 a 12:00 hs. Obtiene el resultado en el momento. \n\nA domicilio el valor es $5.500 pesos el test rápido y $8.000 la PCR.'
        ]);

        Chatbotmessage::create([
            'name' => 'cobertura_ioma',
            'step' => '["0.8.3"]',
            'message' => 'Los pacientes de IOMA deben enviar las órdenes médicas para autorizar antes de concurrir al laboratorio. Para enviar la orden a autorizar o bien si desea consultar el estado de una orden que envio previamente puede hacerlo digitando la opción \n\n 1️⃣ Autorizaciones de órdenes \n\n*_Si su orden ya está autorizada puede venir sin turno de 7:30 a 10:30 hs. de lunes a sábados_*. Si posee la orden original traigala el día del estudio junto con el número de PRECARGA que le asignamos. Una vez autorizado tiene 3 meses para realizar los estudios \nSi ya envió la orden para autorizar también puede consultar el estado de la misma ingresando a www.faba.org.ar en la opción “consulta de afiliado de IOMA” con su número de DNI'
        ]);
      
        

    }
}
