<template>
    <app-layout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Pedidos - <span class="font-normal text-gray-600">Crear Pedido</span>
            </h2>

            <button class="btn-blue">
                Guardar
            </button>              
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <div class="md:grid md:grid-cols-3 md:gap-6">

                    <div class="md:col-span-1 flex justify-between">
                        <div class="px-4 sm:px-0">
                             <h3 class="text-lg font-medium leading-6 text-gray-900">
                                Datos del Viaje
                            </h3>

                            <p class="mt-1 text-sm text-gray-600">
                                orem ipsum dolor sit, amet consectetur adipisicing elit.
                            </p>
                        </div>
                    </div>        

                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-md">
                            <div class="grid grid-cols-6 gap-6">

                                <div class="col-span-6 sm:col-span-3">
                                    <jet-label for="fecha_salida" value="Fecha salida" />
                                    <Datepicker class="w-full" v-model="form.fecha_salida" 
                                                :enableTimePicker="false"
                                                :monthChangeOnScroll="false" 
                                                autoApply
                                                :minDate="new Date(new Date().setDate(new Date().getDate()-1))"></Datepicker>
                                </div>  

                                <div class="col-span-6 sm:col-span-3">
                                    <jet-label for="fecha_llegada" value="Fecha llegada" />
                                    <Datepicker class="w-full" v-model="form.fecha_llegada" 
                                                :enableTimePicker="false"
                                                :monthChangeOnScroll="false" 
                                                autoApply
                                                :minDate="form.fecha_salida">
                                    </Datepicker>
                                </div>  

                                <div class="col-span-6 sm:col-span-3">
                                    <jet-label for="pais_origen" value="Pais origen" />
                                    <select v-model="form.pais_origen" id="pais_origen" name="pais_origen" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option disabled value="">Seleccione el Pais</option>
                                        <option v-for="country in countries_list" :key="country.id" :value="country.iso_country">{{country.description}}</option>
                                    </select>
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <jet-label for="pais_destino" value="Pais destino" />
                                    <select v-model="form.pais_destino" id="pais_destino" name="pais_destino" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option disabled value="">Seleccione el Pais</option>
                                        <option v-for="country in countries_list" :key="country.id" :value="country.iso_country">{{country.description}}</option>
                                    </select>
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <jet-label for="id_plan" value="Plan" />
                                    <select v-model="form.id_plan" id="id_plan" name="id_plan" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option disabled value="">Seleccione el Plan</option>
                                        <option v-for="plan in plan_list" :key="plan.id" :value="plan.id">{{plan.titulo}}</option>
                                    </select>
                                </div>

                                <div class="col-span-6 sm:col-span-1">
                                    <jet-label for="costo" value="Total" />
                                    <jet-input v-model="form.costo" id="costo" type="text" class="mt-1 block w-full"  />
                                </div>

                                <div class="col-span-6 sm:col-span-1">
                                    <jet-label for="moneda" value="moneda" />
                                    <jet-input v-model="form.moneda" id="moneda" type="text" class="mt-1 block w-full" />
                                </div>

                                <div class="col-span-6 sm:col-span-1">
                                    <jet-label for="tasa_cambio" value="tasa_cambio" />
                                    <jet-input v-model="form.tasa_cambio" id="tasa_cambio" type="text" class="mt-1 block w-full"  />
                                </div>


                            </div>

                        </div>

                    </div>

                    <!-- <div class="md:col-span-1 flex justify-between">
                        <div class="px-4 sm:px-0">
                             <h3 class="text-lg font-medium leading-6 text-gray-900">
                                Datos de Contacto
                            </h3>

                            <p class="mt-1 text-sm text-gray-600">
                                orem ipsum dolor sit, amet consectetur adipisicing elit.
                            </p>
                        </div>
                    </div>    

                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-md">
                            <div class="grid grid-cols-6 gap-6">

                                <div class="col-span-6 sm:col-span-2">
                                    <jet-label for="nombre_contacto" value="Nombre" />
                                    <jet-input v-model="form.nombre_contacto" id="nombre_contacto" type="text" class="mt-1 block w-full"  />
                                </div>

                                <div class="col-span-6 sm:col-span-2">
                                    <jet-label for="telefono_contacto" value="Telefono" />
                                    <jet-input v-model="form.telefono_contacto" id="telefono_contacto" type="text" class="mt-1 block w-full"  />
                                </div>


                                <div class="col-span-6 sm:col-span-2">
                                    <jet-label for="email_contacto" value="E-Mail" />
                                    <jet-input v-model="form.email_contacto" id="email_contacto" type="text" class="mt-1 block w-full"  />
                                </div>

                                <div class="col-span-6 sm:col-span-6">
                                    <jet-label for="consideraciones_generales" value="Consideraciones Generales" />
                                    <jet-input v-model="form.consideraciones_generales" id="consideraciones_generales" type="text" class="mt-1 block w-full"  />
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-span-3 divider" ></div>

                    <div class="md:col-span-1 flex justify-between">
                        <div class="px-4 sm:px-0">
                             <h3 class="text-lg font-medium leading-6 text-gray-900">
                                Datos de pasajeros
                            </h3>

                            <p class="mt-1 text-sm text-gray-600">
                                orem ipsum dolor sit, amet consectetur adipisicing elit.
                            </p>
                        </div>
                    </div>    

                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-md">
                            <div class="w-full">
                                <button @click="addPax" class="btn-primary">Agregar Pasajero</button> 
                            </div>

                            <div v-for="pax in paxes" :key="pax.id" class="grid grid-cols-6 gap-6">
                                <div class="col-span-6 sm:col-span-6 mt-4">
                                    <div class="flex justify-between items-center">
                                        <div>Pasajero # {{pax.id}}</div> 
                                        <button class="btn btn-danger"><Icons name="trash" class="w-5 h-5 mr-2" /><span>Eliminar</span></button>
                                    </div>
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <jet-label for="pax_name" value="Nombre" />
                                    <jet-input v-model="pax.name" id="pax.name" type="text" class="mt-1 block w-full"  />
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <jet-label for="pax_apellido" value="Apellido" />
                                    <jet-input v-model="pax.lastname" id="pax.name" type="text" class="mt-1 block w-full"  />
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <jet-label for="pax_doc" value="Documento" />
                                    <jet-input v-model="pax.doc" id="pax.doc" type="text" class="mt-1 block w-full"  />
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <jet-label for="pax_nac" value="Fecha Nacimiento" />
                                    <jet-input v-model="pax.nac" id="pax.nac" type="text" class="mt-1 block w-full"  />
                                </div>

                                <div class="col-span-6 divider" ></div>

                            </div>
                        </div>
                    </div> -->

                </div>
            </div>
        </div>            

    </app-layout>
</template>


<script>
    import { defineComponent } from 'vue'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
    import JetFormSection from '@/Jetstream/FormSection.vue'
    import JetActionMessage from '@/Jetstream/ActionMessage.vue'
    import JetInputError from '@/Jetstream/InputError.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetLabel from '@/Jetstream/Label.vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import Datepicker from '@vuepic/vue-datepicker';
    import '@vuepic/vue-datepicker/dist/main.css'
    import { ref } from 'vue'
    import Icons from '@/Layouts/Components/Icons.vue'    


    export default defineComponent({
        props:{

        },

        components: {
            JetActionMessage,
            JetButton,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
            JetSecondaryButton,
            AppLayout,
            Datepicker,
            Icons
        },

        data() {
            return {
                form: {},
            }
        },

        methods:{
            submit(){

            },

        },
    })
</script>
