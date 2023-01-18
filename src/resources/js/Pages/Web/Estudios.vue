<template>
  <div class="bg-white">
   <headers />
   <main>
      <whatsappbtn />
      <div class="relative">
        <div class="absolute inset-x-0 bottom-0 h-1/2 bg-white" />
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="relative shadow-xl sm:rounded-2xl sm:overflow-hidden">
            <div class="absolute inset-0">
              <img class="h-full w-full object-cover" src="/img/faq.jpg" alt="" />
              <div class="absolute inset-0 bg-gradient-to-r from-blue-800 to-gray-500 mix-blend-multiply" />
            </div>
            <div class="relative px-4 py-16 sm:px-6 sm:py-24 lg:py-32 lg:px-8">
              <h1 class="text-center text-4xl font-extrabold sm:text-5xl lg:text-6xl">
                <span class="block text-white">ESTUDIOS</span>
                
              </h1>
              <!-- <p class="text-center text-lg block text-white">Consulte por los detalles de estudios.</p> -->

              <div class="mt-10 max-w-sm mx-auto flex justify-center">
                <input class="w-64 rounded-md mr-2" type="text" v-model="filtro" />
                <a @click="filtroInput"
                  class="flex items-center justify-center px-4 py-3 border border-transparent text-base font-bold rounded-md shadow-sm text-white bg-blue-500 hover:bg-blue-600 sm:px-8 tracking-wide">BUSCAR</a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-gradient-to-b from-white to-gray-100 pb-32">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <p class="text-2xl font-medium text-blue-900 py-5 ">Búsqueda rápida por palabras claves:</p>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-wrap">
          <div v-for="estudio in estudiosFiltered" :key="estudio.id" class="bg-gray-200 text-gray-800 rounded-lg py-2 px-3 mb-4 mr-3">{{ estudio.title }}</div>
        </div>
      </div> 
    </main>
    <footers />
  </div>
</template>

<script>

import { GlobeAltIcon, LightningBoltIcon, ScaleIcon, MailIcon, PhoneIcon, ClockIcon } from '@heroicons/vue/outline'
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'
import { ChevronDownIcon } from '@heroicons/vue/outline'
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

import Headers from './Headers.vue'
import Whatsappbtn from './Whatsappbtn.vue'
import Footers from './Footer.vue'



export default {
  props: {
    estudios: Object
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
    Headers,
    Whatsappbtn,
    Footers,
  },
  setup() {
    return {
    }
  },
  data() {
    return {
      filtro: "",
      estudiosFiltered: {}
    }
  },
  methods: {
    clearFilter() {
      this.estudiosFiltered = {}
      this.filtro = ""
    },

    filtroInput() {
      let f = this.filtro
      this.estudiosFiltered = this.estudios.filter(function (obj) {
        if (obj.title.toUpperCase().includes(f.toUpperCase()) 
          || obj.description.toUpperCase().includes(f.toUpperCase())) {
          return obj
        }
      })
    }
  },
  mounted() {
    this.estudiosFiltered = this.estudios
  }
}
</script>