<template lang="">
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
                        <input type="number" name="cant_opciones" id="cant_opciones" class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-md sm:text-sm border-gray-300">
                    </div>
                </div>

                <div class="col-span-3 sm:col-span-2">
                    <label for="hora_limit" class="block text-sm font-medium text-gray-700">Hora salto de día</label>
                    <Datepicker id="hora_limit" name="hora_limit" class="w-full mt-1" v-model="hora_limit"
                                :startTime="startTime" timePicker></Datepicker>
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
            <button class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Guardar</button>
        </div>
    </div>
</template>

<script>

import Datepicker from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'
import ListDayItems from './Listdayitems.vue'

export default {

    components:{
        Datepicker,
        ListDayItems
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
        }
    },
    created(){
        this.getDays()
    },
    methods:{
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