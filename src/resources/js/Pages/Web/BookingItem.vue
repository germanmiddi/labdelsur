<template>
  <div class="w-full px-4 py-16">
    <div class="mx-auto w-full max-w-md">
      <RadioGroup v-model="selected">
        <div class="space-y-2">
          <RadioGroupOption as="template" v-for="day in fechas" :key="day.id" :value="day" 
                            v-slot="{ active, checked }" 
                            @click="emitDateSelected(day)">
            <div :class="[ active ? 'ring-2 ring-white ring-opacity-60 ring-offset-2 ring-offset-sky-300'
                                  : '',
                           checked ? 'bg-blue-800 bg-opacity-75 text-white ' : 'bg-white ',]"
                  class="relative flex cursor-pointer rounded-lg px-5 py-4 shadow-md focus:outline-none">
              <div class="flex w-full items-center justify-between">
                
                <div class="flex items-center">
                  <div class="text-sm">
                    <RadioGroupLabel as="p" class="font-medium" :class="checked ? 'text-white' : 'text-gray-900'">
                     {{ formatFecha(day) }} - Hora: 7:30 a 10:30 hs
                    </RadioGroupLabel>
                    <RadioGroupDescription as="span" class="inline"
                      :class="checked ? 'text-sky-100' : 'text-gray-500'">                      
                      <span>{{ dias(day) }}</span>
                    </RadioGroupDescription>
                  </div>
                </div>

                <div v-show="checked" class="shrink-0 text-white">
                  <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="12" fill="#fff" fill-opacity="0.2" /> <path d="M7 13l3 3 7-7" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /> </svg>
                </div>
              </div>
            </div>
          </RadioGroupOption>
        </div>
      </RadioGroup>
    </div>
  </div>
</template>

<script setup>

import { ref, defineProps, defineEmits } from 'vue'
import {
  RadioGroup,
  RadioGroupLabel,
  RadioGroupDescription,
  RadioGroupOption,
} from '@headlessui/vue'

const props = defineProps({
  fechas: String, // Asegúrate de que el tipo coincida con el tipo de dato que esperas recibir
})

const selected = ref()
const emit = defineEmits();

const emitDateSelected = (selectedDate) => {
  // Emite el evento "dateSelected" con la fecha seleccionada como argumento
  
  emit('dateSelected', selectedDate);
};

const dias = (fecha) => {
    // Crear un objeto de fecha a partir de la cadena de fecha
    const date = new Date(fecha);

    // Obtener la fecha actual
    const currentDate = new Date();

    // Calcular la diferencia en días entre la fecha actual y la fecha dada
    const timeDifference = date.getTime() - currentDate.getTime();
    const daysDifference = Math.ceil(timeDifference / (1000 * 3600 * 24));

    // Agregar la leyenda "En xx días"
    return `En ${daysDifference} días`;
}

const formatFecha = (fecha) => {
    // Crear un objeto de fecha a partir de la cadena de fecha
    const date = new Date(fecha);

    // Obtener la fecha actual
    const currentDate = new Date();

    // Días de la semana y meses en español
    const diasSemana = ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"];
    const meses = [
      "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
      "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
    ];

    // Obtener el día, mes y año
    const dia = date.getDate();
    const mes = date.getMonth();
    const ano = date.getFullYear();

    // Obtener el nombre del día de la semana
    const diaSemana = diasSemana[date.getDay()];

    // Formatear la fecha
    const formattedFecha = `${diaSemana} ${dia} de ${meses[mes]} de ${ano}`;

    // Agregar la leyenda "En xx días"
    return `${formattedFecha}`;
  }
</script>
