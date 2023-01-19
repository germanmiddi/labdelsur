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
              <h1 class="text-center text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl">
                <span class="block text-white">PREGUNTAS FRECUENTES</span>
              </h1>

              <div class="mt-10 max-w-sm mx-auto flex justify-center">
                  <input class="w-64 rounded-md mr-2" type="text"  v-model="filtro"/>
                  <a href="#" 
                     @click="filtroInput"
                     class="flex items-center justify-center px-4 py-3 border border-transparent text-base font-bold rounded-md shadow-sm text-white bg-blue-500 hover:bg-blue-600 sm:px-8 tracking-wide">BUSCAR</a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-gradient-to-b from-white to-gray-100">
       
        <!-- <div v-if="this.faqsFiltered" class="max-w-7xl flex justify-end px-6 mt-10" >
          <a href="#" @click="clearFilter"
             class="flex items-center justify-center bg-blue-400 hover:bg-hover-500
                    px-2 py-1 text-base font-bold rounded-md shadow-sm text-white sm:px-8 tracking-wide">VER TODO</a>
        </div> -->


        <div class="max-w-7xl mx-auto py-12 px-4 divide-y divide-gray-200 sm:px-6 lg:py-16 lg:px-8">

          <!-- <div v-if="!this.faqsFiltered" class="mt-8">
            <dl class="divide-y divide-gray-200">
              <div v-for="faq in faqsFiltered" :key="faq.id" >
                <div v-if="faq.show" class="pt-6 pb-8 md:grid md:grid-cols-12 md:gap-8">
                  <dt class="text-base font-medium text-blue-900 md:col-span-5">
                    {{ faq.question }}
                  </dt>
                  <dd class="mt-2 md:mt-0 md:col-span-7">
                    <p class="text-base text-gray-500">
                      {{ faq.answer }}
                    </p>
                  </dd>
                </div>
              </div>
            </dl>
          </div> -->

          <div v-if="this.faqsFiltered" class="mt-8">
            <dl class="divide-y divide-gray-200">
              <div v-for="faq in faqsFiltered" :key="faq.id" >
                <div class="pt-6 pb-8 md:grid md:grid-cols-12 md:gap-8">
                  <dt class="text-base font-medium text-gray-900 md:col-span-5">
                    {{ faq.question }}
                  </dt>
                  <dd class="mt-2 md:mt-0 md:col-span-7">
                    <p class="text-base text-gray-500 text-justify" v-html="faq.answer">
                      
                    </p>
                  </dd>
                </div>
              </div>
            </dl>
          </div>


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
import Footers from './Footer.vue'
import Whatsappbtn from './Whatsappbtn.vue'
import Headers from './Headers.vue'

export default {
  props: {
    faqs: Object
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
    Footers,
    Whatsappbtn,
    Headers
  
  },
  setup() {
    
  },
  data(){
    return{
      filtro:"",
      faqsFiltered: {}
    }
  },
  methods:{
    filtroInput(){
      let f = this.filtro
      this.faqsFiltered = this.faqs.filter(function(obj){
        if (obj.question.toUpperCase().includes(f.toUpperCase()) 
         || obj.answer.toUpperCase().includes(f.toUpperCase()) ){
          return obj
        }
      })
    }
  },
  mounted() {
    this.faqsFiltered = this.faqs
  }
}
</script>