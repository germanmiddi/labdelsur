<template>
    <div class="bg-white">
  
    <headers :links=links />
     <main>
        <whatsappbtn :links=links />
  
        <div class="relative">
          <div class="absolute inset-x-0 bottom-0 h-1/2 bg-white" />
          <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="relative shadow-xl sm:rounded-2xl sm:overflow-hidden">
              <div class="absolute inset-0">
                <img class="h-full w-full object-cover" src="/img/nuevo-turno.jpg" alt="" />
                <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-gray-200 mix-blend-multiply" />
              </div>
              <div class="relative px-4 py-16 sm:px-6 sm:py-24 lg:py-32 lg:px-8">
                <h1 class="text-center text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl">
                  <span class="block text-white">Turnos UTA</span>
                </h1>
              </div>
            </div>
          </div>
        </div>
  
        <div class="bg-gradient-to-b from-white to-gray-100">
          <div class="max-w-5xl mx-auto py-12 px-4 divide-y divide-gray-200 sm:px-6 lg:py-16 lg:px-8">              
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-2xl font-semibold leading-7 text-gray-900">Su turno se ha cancelado.</h2>
                    <!-- <p class="mt-1 text-lg leading-6 text-gray-500">Recibira en su mail la confirmación del turno.</p> -->
                </div>
            </div>
          </div>
        </div>
      </main>
   
      <footers :links=links />
  
    </div>
  </template>
  
  <script>
  
  import { defineComponent, h } from 'vue'
  
  import Whatsappbtn from './Whatsappbtn.vue'
  import Headers from './Headers.vue'
  import Footers from './Footer.vue'
  
  
  export default {
    props: {
        links: Object,
    },
    components: {
      Whatsappbtn,
      Headers,
      Footers,
      
    },

    data(){
      return{
       
      }
    },
    methods:{
   
    }
  }
  </script>