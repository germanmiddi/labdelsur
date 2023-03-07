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
              <img class="h-full w-full object-cover" src="/img/os.jpeg" alt="" />
              <div class="absolute inset-0 bg-gradient-to-r from-blue-800 to-gray-500 mix-blend-multiply" />
            </div>
            <div class="relative px-4 py-16 sm:px-6 sm:py-24 lg:py-32 lg:px-8">
              <h1 class="text-center text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl">
                <span class="block text-white">OBRAS SOCIALES</span>
              </h1>
              <p class="mt-6 max-w-lg mx-auto text-center text-2xl text-blue-200 sm:max-w-3xl">Atendemos más de 50 obras sociales</p>
              <div class="mt-10 max-w-sm mx-auto flex justify-center">
                  <input class="w-64 rounded-md mr-2" type="text"  v-model="filtro"/>
                  <a href="#" 
                     @click="filtroInput"
                     @keydown.enter="filtroInput"
                     class="flex items-center justify-center px-4 py-3 border border-transparent text-base font-bold rounded-md shadow-sm text-white bg-blue-500 hover:bg-blue-600 sm:px-8 tracking-wide">BUSCAR</a>
              </div>
            </div>
            
          </div>
        </div>
      </div>

      <div class="bg-white">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
           
            <div class="mt-6 grid grid-cols-2 gap-8 md:grid-cols-6 lg:grid-cols-5">
              <div class="col-span-1 flex justify-center md:col-span-2 lg:col-span-1" v-for="obra in obras_img" :key="obra.id">
                <img class="h-20" :src="'/storage/'+obra.url" alt="" />
              </div>
            </div>

        </div>
      </div>


      <div class="bg-gradient-to-b from-white to-gray-100">
       
        <!-- <div v-if="this.faqsFiltered" class="max-w-7xl flex justify-end px-6 mt-10" >
          <a href="#" @click="clearFilter"
             class="flex items-center justify-center bg-blue-400 hover:bg-hover-500
                    px-2 py-1 text-base font-bold rounded-md shadow-sm text-white sm:px-8 tracking-wide">VER TODO</a>
        </div>
 -->

        <div class="max-w-7xl mx-auto py-12 px-4 divide-y divide-gray-200 sm:px-6 lg:py-16 lg:px-8">

          <!-- <div v-if="!this.faqsFiltered" class="mt-8">
            <dl class="divide-y divide-gray-200">
              <div v-for="faq in os" :key="faq.id" >
                <div v-if="faq.show" class="pt-6 pb-8 md:grid md:grid-cols-12 md:gap-8">
                  <dt class="text-base font-medium text-blue-900 md:col-span-5">
                    {{ faq.name }}
                  </dt>
                  <dd class="mt-2 md:mt-0 md:col-span-7">
                    <p class="text-base text-gray-500">
                      {{ faq.description }}
                    </p>
                  </dd>
                </div>
              </div>
            </dl>
          </div> -->

          <div class="mt-8">
            <dl v-if="obrasFiltered.length" class="divide-y divide-gray-200">
              <div v-for="obra in obrasFiltered" :key="obra.id" >
                <div class="pt-6 pb-8 md:grid md:grid-cols-12 md:gap-8">
                  <dt class="text-base font-medium text-gray-900 md:col-span-5">
                    {{ obra.title }}
                  </dt>
                  <dd class="mt-2 md:mt-0 md:col-span-7">
                    <p class="text-base text-gray-500">
                      {{ obra.description }}
                    </p>
                  </dd>
                </div>
              </div>
              <div>

              </div>
            </dl>

            <div v-else class="rounded-md bg-blue-50 p-4 shadow-lg">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <InformationCircleIcon class="h-8 w-8 text-blue-400" aria-hidden="true" />
                </div>
                <div class="ml-3 flex-1 md:flex md:justify-between items-center">
                  <p class="text-lg text-blue-700">No se encontraron resultados para su busqueda. Consulte a nuestros asesores haciendo click <a class="underline" target="_blank" href="https://wa.me/5491126887264?text=Consulta%20Obra%20social" >aquí</a>.</p>
                  <p class="mt-3 text-sm md:mt-0 md:ml-6">
                    <a href="#" @click="obrasFiltered = obras" class="whitespace-nowrap font-medium text-blue-700 hover:text-blue-600 hover:underline">Limpiar búsqueda</a>
                  </p>                  
                </div>
              </div>
            </div>            
          </div>


        </div>
      </div>

    </main>
 
    <footers :links=links />
  </div>
</template>

<script>

import { GlobeAltIcon, LightningBoltIcon, ScaleIcon, MailIcon, PhoneIcon, ClockIcon } from '@heroicons/vue/outline'
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'
import { ChevronDownIcon, InformationCircleIcon } from '@heroicons/vue/outline'
import { defineComponent, h } from 'vue'
import { Popover, PopoverButton, PopoverGroup, PopoverPanel } from '@headlessui/vue'
import {
  AnnotationIcon,
  ChatAlt2Icon,
  ChatAltIcon,
  DocumentReportIcon,
  HeartIcon,
  InboxIcon,
  MenuIcon,
  PencilAltIcon,
  QuestionMarkCircleIcon,
  ReplyIcon,
  SparklesIcon,
  TrashIcon,
  UsersIcon,
  XIcon,
} from '@heroicons/vue/outline'

import { CheckIcon } from '@heroicons/vue/outline'
import Whatsappbtn from './Whatsappbtn.vue'
import Headers from './Headers.vue'
import Footers from './Footer.vue'

export default {
  props: {
    obras_img: Object,
    obras: Object,
    links: Object        
  },
  components: {
    Popover,
    PopoverButton,
    PopoverGroup,
    PopoverPanel,
    ChevronDownIcon,
    InboxIcon,
    MenuIcon,
    SparklesIcon,
    XIcon,
    CheckIcon,
    MailIcon,
    PhoneIcon,
    ClockIcon,
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    Whatsappbtn,
    Headers,
    Footers,
    InformationCircleIcon   
  
  },
  setup() {
  },
  data(){
    return{
      filtro:"",
      obrasFiltered: ""
    }
  },
  methods:{
    filtroInput(){
      let f = this.filtro
      this.obrasFiltered = this.obras.filter(function(obj){
        if (obj.title.toUpperCase().includes(f.toUpperCase()) 
         || obj.description.toUpperCase().includes(f.toUpperCase()) ){
          return obj
        }
      })
    }
  },
  mounted() {
    this.obrasFiltered = this.obras
  }
}
</script>