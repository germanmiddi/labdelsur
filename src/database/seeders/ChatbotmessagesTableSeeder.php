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
        
        Chatbotmessage::firstOrCreate([
            'name' => 'authz',
            'step' => '0️⃣ Menu Principal / 2️⃣ Autorizaciones'],[
            'message' => ''
        ]);

        Chatbotmessage::firstOrCreate([
            'name' => 'resultados',
            'step' => '0️⃣ Menu Principal / 3️⃣ Resultados'],[
            'message' => ''
        ]);
        
        Chatbotmessage::firstOrCreate([
            'name' => 'covid_valor',
            'step' => '0️⃣ Menu Principal / 6️⃣ COVID 19 / 1️⃣ Importe del estudio particular'],[
            'message' => 'El hisopado PCR  para SARS-CoV-2 🦠​ tiene un valor de $7.900 pesos con tarjeta de débito y $7.000 si abona en efectivo. Puede venir de lunes a viernes de 11:00 a 15:00 hs y sábados de 8:00 a 9:00 hs. Si desea los resultados en el día debería acercarse a las 11:00 o a las 8:00 hs. respectivamente. \n\nEl test rápido para SARS-CoV-2 tiene un valor de $4.600 pesos con tarjeta de débito y $4.000 en efectivo. En caso de que quiera realizarlo puede venir de lunes a viernes de 11:00 a 15:00 hs. y sábado de 8:00 a 12:00 hs. Obtiene el resultado en el momento. \n\nA domicilio el valor es $5.500 pesos el test rápido y $8.000 la PCR.'
        ]);

        Chatbotmessage::firstOrCreate([
            'name' => 'cobertura_ioma',
            'step' => '0️⃣ Menu Principal / 8️⃣ Coberturas / 3️⃣ IOMA'],[
            'message' => 'Los pacientes de IOMA deben enviar las órdenes médicas para autorizar antes de concurrir al laboratorio. Para enviar la orden a autorizar o bien si desea consultar el estado de una orden que envio previamente puede hacerlo digitando la opción \n\n 1️⃣ Autorizaciones de órdenes \n\n*_Si su orden ya está autorizada puede venir sin turno de 7:30 a 10:30 hs. de lunes a sábados_*. Si posee la orden original traigala el día del estudio junto con el número de PRECARGA que le asignamos. Una vez autorizado tiene 3 meses para realizar los estudios \nSi ya envió la orden para autorizar también puede consultar el estado de la misma ingresando a www.faba.org.ar en la opción “consulta de afiliado de IOMA” con su número de DNI'
        ]);

        Chatbotmessage::firstOrCreate([
            'name' => 'confirma_turno',
            'step' => '0️⃣ Menu Principal /  Turnos / .. /  Confirmación Turno'],[
            'message' => '📝 Puede venir en el día asignado de 7:30 a 10:30 hs. con la orden, el carnet y la autorización. Por favor asistir con la orden firmada al dorso con DNI, firma y aclaración y lo mismo en las autorizaciones al frente. Solicitamos concurrir sin acompañantes. ▶ Si pertenece a la mutual (carnet dorado) no abona el coseguro y sólo abona el Acto Profesional Bioquímico de $2.500 pesos, si no tiene mutual se suma el valor del coseguro indicado por la obra social en la autorización, las autorizaciones anteriores al mes de junio han sufrido diferencias en los valores.'
        ]);



    }
}
