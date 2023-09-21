<template lang="">
    <Toast :toast="this.toastMessage" :type="this.labelType" @clear="clearMessage"></Toast>                         
    <div class="shadow sm:rounded-md sm:overflow-hidden">
        <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
            <div>
                <h2 id="plan-heading" class="text-lg leading-6 font-medium text-gray-900">Cantidad turnos por día</h2>
            </div>
            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">    
                    <thead class="bg-indigo-500">
                        <tr>
                            <th class="px-6 py-4 text-sm font-medium text-white uppercase tracking-wider text-center">Día</th>
                            <th class="px-6 py-4 text-sm font-medium text-white uppercase tracking-wider text-center">Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <list-day-items v-for="day in list_days" :day="day"/>

                        <!-- <tr v-for="day in list_days"
                            class="bg-white border-b text-center hover:bg-gray-50 focus-within:bg-gray-100">
                            <th scope="row"
                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                {{day.description}}
                            </th>
                            <td class="py-4 px-6">
                                {{day.cant_orders}}
                            </td>
                        </tr> -->
                    </tbody>
                </table> 
            </div>
        </div>

    </div>

    <div class="shadow sm:rounded-md sm:overflow-hidden">
        <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
            <div>
                <h2 id="plan-heading" class="text-lg leading-6 font-medium text-gray-900">Parámetros</h2>
            </div>
            <div class="grid grid-cols-3 gap-6">

                <div class="col-span-3 sm:col-span-2">
                    <label for="cant_opciones" class="block text-sm font-medium text-gray-700">Cantidad Opciones de Turnos</label>
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <input type="number" v-model="cant_opciones" name="cant_opciones" id="cant_opciones" class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-md sm:text-sm border-gray-300">
                    </div>
                </div>

                <div class="col-span-3 sm:col-span-2">
                    <label for="hora_limit" class="block text-sm font-medium text-gray-700">Hora salto de día</label>
                    <!-- <Datepicker id="hora_limit" name="hora_limit" class="w-full mt-1" v-model="hora_limit" 
                                :startTime="startTime"  :monthChangeOnScroll="false" autoApply  time-picker
                                ></Datepicker> -->
                    <input type="text" name="hora_limit" id="hora_limit"
                     class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-md sm:text-sm border-gray-300"
                            v-model="hora_limit" @input="validarHoraMinuto" placeholder="HH:MM" />
                    <div v-if="!inputEsValido" 
                        class="text-xs text-red-500 mt-2 ml-1 ">El formato debe ser HH:MM</div>
                    
                    
                </div>

                <div class="col-span-3 sm:col-span-2">
                    <label for="date_limit" class="block text-sm font-medium text-gray-700">Fecha límite de turnos</label>
					<Datepicker id="date_limit" class="w-full mt-1"
                                v-model="date_limit" name="date_limit"
                                :enableTimePicker="false" :monthChangeOnScroll="false" autoApply
                                :format="format"></Datepicker>
                </div>

            </div>    
        </div>
        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
            <button @click="saveTurnos" class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Guardar</button>
        </div>
    </div>
</template>

<script>

import Datepicker from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'
import ListDayItems from './Listdayitems.vue'
import Toast from '@/Layouts/Components/Toast.vue'

export default {

    components:{
        Datepicker,
        ListDayItems,
        Toast
    },
    setup(){
        const format = (date) => {
            return date.toLocaleDateString('es-AR', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
            });
        };
        return{
            format
        }
    },

    data(){
        return{
            cant_opciones: 0,
            hora_limit: "",
            date_limit: "",
            startTime: "",
            list_days: [],
            labelType: "info",
            toastMessage: "",            
        }
    },
    computed: {
        inputEsValido() {
        // Expresión regular para validar HH:MM
        const regex = /^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/;
        return regex.test(this.hora_limit);
        },
    },    
    created(){
        this.getTurnosSettings()
        // this.getDays()
    },
    methods:{
        clearMessage() {
			this.toastMessage = ""
		},                
        async saveTurnos(){
            if (!this.inputEsValido) {
                this.labelType = "danger"
                this.toastMessage = "El formato de la hora debe ser HH:MM"
                return
            }

            const post = `${route('settings.turnosUpdate')}`

            let data = new FormData()
            data.append('cant_days_booking', this.cant_opciones)
            data.append('hora_limit_booking', this.hora_limit)
            data.append('day_limit_booking', this.date_limit)

            const response = await axios.post(post, data)
            console.log(response)

            if (response.status == 200) {
                this.labelType = "success"
				this.toastMessage = "Se guardaron los cambios"               
            } else {
                this.labelType = "danger"
                this.toastMessage = "No se guardaron los cambios"                
            }
        },  
        async getTurnosSettings(){

            const get = `${route('settings.turnos')}`
            const response = await fetch(get, { method: 'GET' })
            const data = await response.json()
            console.log(response, data)
            this.cant_opciones = data.turnos.cant_days_booking
            // pasar la hora a hora_limit de forma que lo pueda interpretar datapicker    
            this.hora_limit = data.turnos.hora_limit_booking
            this.date_limit = data.turnos.day_limit_booking
            this.list_days = data.dias


        },
        format_hora_limit(hora_limit_booking){

            const horaLimitParts = hora_limit_booking.split(':');
            const hora = parseInt(horaLimitParts[0]);
            const minuto = parseInt(horaLimitParts[1]);
            console.log(hora, minuto)
            
            const fechaHora = new Date();
            fechaHora.setHours(hora, minuto, 0, 0);

            // Asignar la fechaHora al componente Datepicker
            
            return {hora: hora, minuto: minuto}


        },

		async getDays() {
			const get = `${route('settings.listday')}`

			const response = await fetch(get, { method: 'GET' })
			this.list_days = await response.json()
		}
    },
}
</script>
<style lang="">
    
</style>