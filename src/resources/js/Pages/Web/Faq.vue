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
       
        <div v-if="this.faqsFiltered" class="max-w-7xl flex justify-end px-6 mt-10" >
          <a href="#" @click="clearFilter"
             class="flex items-center justify-center bg-blue-400 hover:bg-hover-500
                    px-2 py-1 text-base font-bold rounded-md shadow-sm text-white sm:px-8 tracking-wide">VER TODO</a>
        </div>


        <div class="max-w-7xl mx-auto py-12 px-4 divide-y divide-gray-200 sm:px-6 lg:py-16 lg:px-8">

          <div v-if="!this.faqsFiltered" class="mt-8">
            <dl class="divide-y divide-gray-200">
              <div v-for="faq in faqs" :key="faq.id" >
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
          </div>

          <div v-if="this.faqsFiltered" class="mt-8">
            <dl class="divide-y divide-gray-200">
              <div v-for="faq in faqsFiltered" :key="faq.id" >
                <div v-if="faq.show" class="pt-6 pb-8 md:grid md:grid-cols-12 md:gap-8">
                  <dt class="text-base font-medium text-gray-900 md:col-span-5">
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

const faqs = [
  { 
    question: '¿Cuál es el horario de atención?',
    answer:   'Lunes a viernes de 7:30 a 18:00 hs. y sábados de 7:30 a 13:00 hs.',
    show: true
  },
  { 
    question: '¿Cual es el horario de extracción?',
    answer: 'Lunes a sábados de 7:30 a 10:00 hs.',
    show: true
  },
  { 
    question: '¿Necesito orden médica para realizar un hisopado de detección de Covid-19?',
    answer: 'No es necesario contar con orden médica. Puede consultar presupuesto y horarios por WhatsApp al cel: 1127714569.',
    show: true
  },

  {  
    question: '¿Cuántas horas de ayuno necesito?',
    answer: '12 hs. de ayuno, cuando se analice: Colesterol, Triglicéridos, HDL, LDL o Hepatograma. 8 hs. de ayuno para el resto de los análisis. Cortisol y curva de glucemia: La extracción debe realizarse entre las 7:30 y las 8:00 AM con 8 hs de ayuno. Prolactina: 8 hs de ayuno y concurrir al laboratorio con 2 horas de haberse levantado, sin haber hecho esfuerzo ni actividad física.',
    show: true
  },
  {  
    question: '¿Qué requisitos necesitan las recetas médicas de obras sociales o prepagas?',
    answer: 'Todas las órdenes deben contar con fecha, firma, sello del médico, datos personales, número de afiliado y diagnóstico.',
    show: true
  },
  {  
    question: '¿Qué validez tienen las recetas?',
    answer: 'SWISS MEDICAL, PAMI, OSDEPYM: 90 días. OSDE, GALENO: 60 días. UTA: 60 días desde su autorización. PREVENCIÓN SALUD, OSMECON: 30 días. Esto varía según su cobertura, puede consultar por whatsapp al 1127714569. ',
    show: true
  },
  {  
    question: 'Como puedo realizarme estudios si no cuento con obra social ni prepaga',
    answer: 'Si no posee obra social o la suya no está dentro de coberturas, puede enviar una foto de su orden por WhatsApp al 1127714569 y le informaremos el importe y formas de pago',
    show: true
  },
  {  
    question: '¿Con qué Obras sociales y prepagas trabajan?',
    answer: 'Puede consultar las coberturas aqui (link)',
    show: true
  },
  {  
    question: 'Cómo hacer la recolección de orina?',
    answer: 'Para recolectar la muestra de sedimento u orina completa utilice el envase que prefiera y recolecte la primera orina de su mañana o en su defecto una orina con 4 hs. de retencion previas',
    show: true
  },
  {  
    question: '¿Cómo hacer la recolección de orina 24 horas?',
    answer: 'Debe descartar la primer orina, luego recolectar todas las restantes durante 24 horas hasta la primera del día siguiente inclusive. La misma debe recolectarse en botellas de agua mineral o gaseosa bien lavadas. Recuerde que es indispensable contar el total de la orina en ese periodo',
    show: true
  },
  {  
    question: '¿Cuál es la preparación y la muestra para sangre oculta en materia fecal?',
    answer: 'Condiciones previas a la recolección de la muestra:   Durante tres días consecutivos el/la paciente evitará comer carne roja y alimentos que contengan sangre. Deberá evitarse la ingestión de: rábanos, nabos y cacao. Los analgésicos y antirreumáticos no son aconsejables durante estos tres días. Al cuarto día recolectar en un frasco de boca ancha bien limpio y seco una porción de una deposición espontánea  (no recolectar orina). Aclarar si el paciente sufre de hemorroides. Rotular con nombre y apellido. Podrá retirar el frasco en cualquiera de nuestras sucursales presentando la orden/DNI del paciente.',
    show: true
  },
  {  
    question: 'Me indicaron un análisis Parasitológico o de Coprocultivo, ¿dónde debo retirar el frasco?¿Cuáles son las indicaciones?',
    answer: 'Puede acercarse cualquier día en nuestro horario de atención para retirar los materiales y las instrucciones necesarias',
    show: true
  },
  {  
    question: '¿Debo dejar de tomar mi medicación habitual para realizar los estudios?',
    answer: 'Si toma medicación para las tiroides y le piden estudios para el dosaje de las mismas debe tomar la medicación del día luego de la extracción. En caso de que tome otras medicaciones su médica/o le indicará cómo proceder.',
    show: true
  },
  {  
    question: '¿Cómo puedo ver mis resultados?',
    answer: '',
    show: true
  },
  {  
    question: '¿Puede retirar los resultados otra persona?',
    answer: 'Si, se pueden retirar con el ticket que se le entregó en el momento de la extracción.',
    show: true
  },
  {  
    question: 'Mi resultado aún no figura online.',
    answer: 'Si ya se cumplió el tiempo previsto para la entrega de su resultado y aún no figura o figura en proceso, envíe un WhatsApp al 1127714569',
    show: true
  },
  {  
    question: '¿Cuáles son las indicaciones para el estudio micológico de uñas?',
      answer: `3 días antes de concurrir al Laboratorio se deben hacer baños de agua tibia y sal, 3 veces por día durante 15 minutos en la uña o uñas afectadas. El día del estudio no debe tener esmaltes ni cremas. 
              ¿Cuáles son las indicaciones para el análisis bacteriológico de orina? (Urocultivo)
              Para mujeres o personas con vulva: 
              Recolectar la primera orina de la mañana o en su defecto la orina con una retención no menor a  tres horas.
              a) Se practicará un cuidadoso lavado de la zona genital  con abundante agua y jabón .
              b) Secar con una toalla limpia y planchada, o con toallitas descartables.
              c) Taponar el orificio vaginal con algodón o con un tampón vaginal.
              d) Separar los labios y orinar desechando el primer chorro de la micción.
              e) Recolectar la porción media de la micción en un frasco estéril.
              f) Tapar el frasco, rotular con nombre y apellido. Guardar en la heladera hasta su envío al laboratorio.`,
    show: true
  },
  {  
    question: 'Para hombres o personas con pene:',
      answer: `Recolectar la primera orina de la mañana o en su defecto la orina con una retención no menor a  tres horas.
              a) Se practicará un cuidadoso lavado del pene con abundante agua y jabón.
              b) Secar con una toalla limpia y planchada, o con toallitas descartables.
              c) Rebatir el prepucio y orinar, desechando el primer chorro de la micción.
              d) Recolectar la porción media de la micción en un frasco estéril.
              e) Tapar el frasco, rotular con nombre y apellido. Guardar en la heladera hasta su envío al laboratorio.`,
    show: true              
  },
 
  {  
    question: 'Bebés, niños y/o adultos que no controlan esfínteres:',
      answer: `- Higienizar muy bien los genitales externos con agua y jabón.
               - Recoger orina AL ACECHO en frasco estéril (una sola micción, no importa que la cantidad sea escasa). Tapar inmediatamente el frasco y conservar en heladera.`,
       show: true
  },
  {  
    question: '¿Hasta qué hora puedo realizar una curva de sobrecarga de glucosa?',
      answer: `Este análisis puede hacerse entre las 7.30 y 8.30 horas.`,
    show: true
  },
  {  
    question: '¿Cuáles son las indicaciones para realizarme un cultivo de flujo?',
      answer: `Durante las 72 hs. anteriores al estudio: 
                - no tomar antibióticos.
                - no colocarse ningún tipo de crema, talco, óvulos, etc. 
                - no mantener relaciones sexuales. 
                - no realizarse ecografías transvaginales. 
                - no estar menstruando. 
                El día del estudio: no utilizar bidet. 
                ¿Cuáles son las indicaciones para realizarme un análisis de Antígeno Prostático Específico (PSA)?
                Ayuno de 8 hs. (si tiene otros estudios como hepatograma o colesteroles considere que esto puede modificarse) 
                Abstinencia sexual al menos 48 hs. previas a la extracción.
                No haberse realizado en la semana previa tacto rectal o ecografía transrectal o biopsia.
                No haber realizado ejercicios sentado (como andar en bicicleta o a caballo) al menos 48 hs. previas a la extracción.`,
    show: true
  }
]




const footerNavigation = {
  solutions: [
    { name: 'Marketing', href: '#' },
    { name: 'Analytics', href: '#' },
    { name: 'Commerce', href: '#' },
    { name: 'Insights', href: '#' },
  ],
  support: [
    { name: 'Pricing', href: '#' },
    { name: 'Documentation', href: '#' },
    { name: 'Guides', href: '#' },
    { name: 'API Status', href: '#' },
  ],
  company: [
    { name: 'About', href: '#' },
    { name: 'Blog', href: '#' },
    { name: 'Jobs', href: '#' },
    { name: 'Press', href: '#' },
    { name: 'Partners', href: '#' },
  ],
  legal: [
    { name: 'Claim', href: '#' },
    { name: 'Privacy', href: '#' },
    { name: 'Terms', href: '#' },
  ],
  social: [
    {
      name: 'Facebook',
      href: '#',
      icon: defineComponent({
        render: () =>
          h('svg', { fill: 'currentColor', viewBox: '0 0 24 24' }, [
            h('path', {
              'fill-rule': 'evenodd',
              d: 'M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z',
              'clip-rule': 'evenodd',
            }),
          ]),
      }),
    },
    {
      name: 'Instagram',
      href: '#',
      icon: defineComponent({
        render: () =>
          h('svg', { fill: 'currentColor', viewBox: '0 0 24 24' }, [
            h('path', {
              'fill-rule': 'evenodd',
              d: 'M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z',
              'clip-rule': 'evenodd',
            }),
          ]),
      }),
    },
    {
      name: 'Twitter',
      href: '#',
      icon: defineComponent({
        render: () =>
          h('svg', { fill: 'currentColor', viewBox: '0 0 24 24' }, [
            h('path', {
              d: 'M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84',
            }),
          ]),
      }),
    },
    {
      name: 'GitHub',
      href: '#',
      icon: defineComponent({
        render: () =>
          h('svg', { fill: 'currentColor', viewBox: '0 0 24 24' }, [
            h('path', {
              'fill-rule': 'evenodd',
              d: 'M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z',
              'clip-rule': 'evenodd',
            }),
          ]),
      }),
    },
    {
      name: 'Dribbble',
      href: '#',
      icon: defineComponent({
        render: () =>
          h('svg', { fill: 'currentColor', viewBox: '0 0 24 24' }, [
            h('path', {
              'fill-rule': 'evenodd',
              d: 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10c5.51 0 10-4.48 10-10S17.51 2 12 2zm6.605 4.61a8.502 8.502 0 011.93 5.314c-.281-.054-3.101-.629-5.943-.271-.065-.141-.12-.293-.184-.445a25.416 25.416 0 00-.564-1.236c3.145-1.28 4.577-3.124 4.761-3.362zM12 3.475c2.17 0 4.154.813 5.662 2.148-.152.216-1.443 1.941-4.48 3.08-1.399-2.57-2.95-4.675-3.189-5A8.687 8.687 0 0112 3.475zm-3.633.803a53.896 53.896 0 013.167 4.935c-3.992 1.063-7.517 1.04-7.896 1.04a8.581 8.581 0 014.729-5.975zM3.453 12.01v-.26c.37.01 4.512.065 8.775-1.215.25.477.477.965.694 1.453-.109.033-.228.065-.336.098-4.404 1.42-6.747 5.303-6.942 5.629a8.522 8.522 0 01-2.19-5.705zM12 20.547a8.482 8.482 0 01-5.239-1.8c.152-.315 1.888-3.656 6.703-5.337.022-.01.033-.01.054-.022a35.318 35.318 0 011.823 6.475 8.4 8.4 0 01-3.341.684zm4.761-1.465c-.086-.52-.542-3.015-1.659-6.084 2.679-.423 5.022.271 5.314.369a8.468 8.468 0 01-3.655 5.715z',
              'clip-rule': 'evenodd',
            }),
          ]),
      }),
    },
  ],
}



export default {
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
    return {
      footerNavigation,
      faqs,     
      
    }
  },
  data(){
    return{
      filtro:"",
      faqsFiltered: ""
    }
  },
  methods:{
    clearFilter(){
      this.faqsFiltered = ""
      this.filtro = ""
    },

    filtroInput(){
      let f = this.filtro
      this.faqsFiltered = this.faqs.filter(function(obj){
        if (obj.question.includes(f) 
         || obj.answer.includes(f) ){
          return obj
        }
      })
    }
  }
}
</script>