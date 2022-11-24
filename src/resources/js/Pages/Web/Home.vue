<template>
  <div class="bg-white">
   

    <headers />
    <main>
      <whatsappbtn />

      <!-- Hero section -->
      <div class="relative">
        <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gray-100" />
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="relative shadow-xl sm:rounded-2xl sm:overflow-hidden">
            <div class="absolute inset-0">
              <img class="h-full w-full object-cover object-bottom" src="/img/banner2.jpg" alt="" />
              <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-gray-300 mix-blend-multiply" />
            </div>
            <div class="relative px-4 py-16 sm:px-6 sm:py-24 lg:py-32 lg:px-8">
              <h1 class="text-center text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl">
                <span class="block text-white">Comprometidos con la salud de nuestros pacientes</span>
                <span class="block text-blue-200"></span>
              </h1>
              <p class="mt-6 max-w-lg mx-auto text-center text-xl text-blue-100 sm:max-w-3xl">
                Reconocidos por ser sinónimo de calidad, responsabilidad y profesionalismo en el área. Brindamos un servicio rápido y confiable para nuestros pacientes.</p>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-gray-100">
          <div class="max-w-7xl mx-auto py-24 px-4 sm:px-6 lg:py-24 lg:px-8 lg:grid lg:grid-cols-3 lg:gap-x-8">
            <div>
                <h2 class="text-base font-semibold text-blue-600 uppercase tracking-wide">Realizamos.</h2>
                <p class="mt-2 text-2xl md:text-3xl font-extrabold text-gray-900">Análisis clínicos y microbiológicos</p>
                <p class="mt-4 text-lg text-gray-500">De baja, mediana y alta complejidad. Estudios bacteriológicos, biología molecular, marcadores oncológicos y estudios genómicos.</p>
            </div>
            <div class="mt-12 lg:mt-0 lg:col-span-2">
                <dl class="space-y-10 sm:space-y-0 sm:grid sm:grid-cols-2 sm:grid-rows-2 sm:grid-flow-col sm:gap-x-6 sm:gap-y-10 lg:gap-x-8">
                    <div v-for="feature in features" :key="feature.name" class="relative">
                        <dt>
                            <CheckIcon class="absolute h-6 w-6 text-green-500" aria-hidden="true" />
                            <p class="ml-9 text-lg leading-6 font-medium text-gray-900">{{ feature.name }}</p>
                        </dt>
                        <dd class="mt-2 ml-9 text-base text-gray-500 text-justify" v-html="feature.description.replace(/\n/g, '<br>')">
                            
                        </dd>
                    </div>
                </dl>
            </div>
          </div>
      </div>

      <!-- Contacto -->
      <div class="pb-16 bg-gradient-to-r from-blue-500 to-blue-800 lg:pb-0 lg:z-10 lg:relative">
        <div class="lg:mx-auto lg:max-w-7xl lg:px-8 lg:grid lg:grid-cols-4 lg:gap-8">
          <div class="relative lg:col-span-2 lg:-my-8 shadow-xl rounded-2xl overflow-hidden  ">
              <iframe  width="100%" height="100%" 
                                        style="border:0;" frameborder="0" marginheight="0" marginwidth="0" 
                                        title="map" scrolling="no" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1081.944959699275!2d-58.39016251175489!3d-34.70911644321135!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95bccd2349da42d1%3A0x5137a310ec6e1bbc!2sDEL%20SUR%20An%C3%A1lisis%20Cl%C3%ADnicos!5e0!3m2!1ses-419!2sar!4v1660225044066!5m2!1ses-419!2sar"></iframe>            
          </div>
          <div class="mt-12 lg:m-0 lg:col-span-2 lg:pl-8">
            <div class="mx-auto max-w-md px-4 sm:max-w-2xl sm:px-6 lg:px-0 lg:py-20 lg:max-w-none">
            <!-- <div class="max-w-lg mx-auto"> -->
              <dl class="mt-8 text-base text-gray-100">
                
                <div>
                  <dt class="sr-only">Phone number</dt>
                  <dd class="flex">
                    <PhoneIcon class="flex-shrink-0 h-6 w-6 text-blue-200" aria-hidden="true" />
                    <span class="ml-3"> 4225-0789 / 4249-8651</span>
                  </dd>
                </div>
                <div class="mt-3">
                  <dt class="sr-only">Email</dt>
                  <dd class="flex">
                    <ChatIcon class="flex-shrink-0 h-6 w-6 text-blue-200" aria-hidden="true" />
                    <a class="ml-3 hover:underline" href="https://api.whatsapp.com/send?phone=541127714569&text=Hola" target="_blank">11 2771 45 69</a>
                  </dd>
                </div>
                <div class="mt-3">
                  <dt class="sr-only">Email</dt>
                  <dd class="flex">
                    <MailIcon class="flex-shrink-0 h-6 w-6 text-blue-200" aria-hidden="true" />
                    <a href="mailto:info@laboratoriodelsur.com.ar" target="_blank" class="ml-3 hover:underline">info@laboratoriodelsur.com.ar</a>
                  </dd>
                </div>
                <div class="mt-3">
                  <dt class="sr-only">Email</dt>
                  <dd class="flex">
                    <ClockIcon class="flex-shrink-0 h-6 w-6 text-blue-200" aria-hidden="true" />
                    <span class="ml-3">Lunes a Viernes 7:30 a 18:00 hs. / Sábados 7:30 a 13:00. hs. <br>Extracciones hasta 10:30 hs.</span>
                  </dd>
                </div>
                <div class="mt-6">
                  <dd>
                    <p>Margarita Weild 1200</p>
                    <p>Lanús Este, Prov. de Buenos Aires</p>
                  </dd>
                </div>
              </dl>

            </div>
          </div>
        </div>
      </div>

      <!-- FAQ -->
      <div class="bg-gray-50">
        <div class="max-w-7xl mx-auto py-16 px-4 divide-y-2 divide-gray-200 sm:py-24 sm:px-6 lg:px-8">
          <div class="flex justify-between items-center">
            <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900">Preguntas Frecuentes</h2>
            <a :href="route('preguntas-frecuentes')" class="hidden md:flex h-12 items-center justify-center px-4 py-3 border border-transparent text-base font-bold rounded-md shadow-sm text-white bg-blue-500 hover:bg-blue-600 sm:px-8 tracking-wide">Ver todas</a>
            <a :href="route('preguntas-frecuentes')" class="flex md:hidden h-12 items-center justify-center text-base font-bold text-blue-500 tracking-wide">Ver todas</a>
          </div>
          
          <div class="mt-6 pt-10">
            <dl class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-12">
              <div v-for="faq in faqs" :key="faq.id">
                <dt class="text-lg leading-6 font-medium text-gray-900">
                  {{ faq.question }}
                </dt>
                <dd class="mt-2 text-base text-gray-500 text-justify" v-html="faq.answer.replace(/\n/g, '<br>')">
                  
                </dd>
              </div>
            </dl>
          </div>
        </div>
      </div>

      <!-- Obras Sociales -->
      <div class="bg-white">
        <div class="max-w-7xl mx-auto py-24 px-4 sm:px-6 lg:px-8">
            <div class="hidden md:flex justify-between items-center">
              <h3 class="mb-8 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">Atendemos más de 50 obras sociales</h3>
              <a :href="route('obras-sociales')" class="h-12 flex items-center justify-center px-4 py-3 border border-transparent text-base font-bold rounded-md shadow-sm text-white bg-blue-500 hover:bg-blue-600 sm:px-8 tracking-wide">Ver todas</a>
            </div>
            <div class="flex md:hidden justify-between items-center mb-3">
              <h2 class="text-3xl font-extrabold text-gray-900">Obras Sociales</h2>
              <a :href="route('obras-sociales')" class="flex md:hidden h-12 items-center justify-center text-base font-bold text-blue-500 tracking-wide">Ver todas</a>
            </div>            

            <div class="mt-6 grid grid-cols-2 gap-8 md:grid-cols-6 lg:grid-cols-5">
              <div class="col-span-1 flex justify-center md:col-span-2 lg:col-span-1">
                <img class="h-20  grayscale" src="/img/obrassociales/uta.png" alt="" />
              </div>
              <div class="col-span-1 flex justify-center md:col-span-2 lg:col-span-1">
                <img class="h-20 grayscale" src="/img/obrassociales/ioma.jpeg" alt="" />
              </div>
              <div class="col-span-1 flex justify-center md:col-span-2 lg:col-span-1">
                <img class="h-20  grayscale" src="/img/obrassociales/galeno.png" alt="" />
              </div>
              <div class="col-span-1 flex justify-center md:col-span-2 lg:col-span-1">
                <img class="h-20  grayscale" src="/img/obrassociales/pami.png" alt="" />
              </div>
              <div class="col-span-1 flex justify-center md:col-span-2 lg:col-span-1">
                <img class="h-20  grayscale" src="/img/obrassociales/omint.png" alt="" />
              </div>
              <div class="col-span-1 flex justify-center md:col-span-2 lg:col-span-1">
                <img class="h-20  grayscale" src="/img/obrassociales/sancorsalud.png" alt="" />
              </div>
              <div class="col-span-1 flex justify-center md:col-span-2 lg:col-span-1">
                <img class="h-20  grayscale" src="/img/obrassociales/swiss.png" alt="" />
              </div>
              <div class="col-span-1 flex justify-center md:col-span-2 lg:col-span-1">
                <img class="h-20  grayscale" src="/img/obrassociales/osmecon.jpeg" alt="" />
              </div>
              <div class="col-span-1 flex justify-center md:col-span-2 lg:col-span-1">
                <img class="h-20  grayscale" src="/img/obrassociales/osde.png" alt="" />
              </div>
              <div class="col-span-1 flex justify-center items-center md:col-span-2 lg:col-span-1">
                <img class="h-10  grayscale" src="/img/obrassociales/osdepym.png" alt="" />
              </div>
            </div>

        </div>
      </div>

      <div class="bg-white relative pb-4">
        <div aria-hidden="true" class="absolute inset-x-0 bottom-0 h-24 bg-gray-50" />  
        <div class="relative max-w-4xl mx-auto py-10 px-6 bg-gray-200 rounded-2xl shadow-lg
                    lg:max-w-7xl lg:px-8 lg:flex lg:items-center lg:justify-between
                    md:py-14
                    ">
            <h2 class="tracking-tight  sm:text-4xl">
              <span class="text-2xl md:text-3xl font-extrabold block text-gray-500">Si Ud. es paciente de UTA</span>
              <span class="text-2xl md:text-4xl font-extrabold block bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">Ingrese al siguiente enlace</span>
            </h2>
            <div class="space-y-4 sm:space-y-0 sm:flex sm:space-x-5 mt-4 sm:mt-0">
              <a href="https://wa.me/5491126887264?text=Hola" class="h-12 flex items-center justify-center px-4 py-3 border border-transparent text-base font-bold rounded-md shadow-sm text-white bg-blue-500 hover:bg-blue-600 sm:px-8 tracking-wide">TURNOS UTA</a>
            </div>
        </div>
      </div>

      <!-- Nosotros -->
      <div class="overflow-hidden relative">
        <div aria-hidden="true" class="absolute inset-x-0 top-0 h-48 bg-gradient-to-b from-gray-50 " />
        <div class="relative max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
          <div class="hidden lg:block bg-gray-50 absolute top-0 bottom-0 left-3/4 w-screen" />
          <div class="mx-auto text-base max-w-prose lg:grid lg:grid-cols-2 lg:gap-8 lg:max-w-none">
            <div>
              <h2 class="text-base text-blue-600 font-semibold tracking-wide uppercase">Nosotros</h2>
              <h3 class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">Calidad, responsabilidad y profesionalismo desde hace más de 25 años</h3>
            </div>
          </div>
          <div class="mt-8 lg:grid lg:grid-cols-2 lg:gap-8">
            <div class="relative lg:row-start-1 lg:col-start-2">
              <svg class="hidden lg:block absolute top-0 right-0 -mt-20 -mr-20" width="404" height="384" fill="none" viewBox="0 0 404 384" aria-hidden="true">
                <defs>
                  <pattern id="de316486-4a29-4312-bdfc-fbce2132a2c1" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                    <rect x="0" y="0" width="4" height="4" class="text-gray-200" fill="currentColor" />
                  </pattern>
                </defs>
                <rect width="404" height="384" fill="url(#de316486-4a29-4312-bdfc-fbce2132a2c1)" />
              </svg>
              <div class="relative text-base mx-auto max-w-prose lg:max-w-none">
                <figure>
                  <div class="aspect-w-12 aspect-h-7 lg:aspect-none">
                    <img class="rounded-lg shadow-lg object-cover object-center" src="img/fachada.png" alt="" width="1184" height="1376" />
                    <div class="rounded-lg absolute inset-0 bg-gradient-to-r from-blue-200 to-gray-50 mix-blend-multiply" />
                    <!-- <img class="rounded-lg shadow-lg object-cover object-center" src="https://lifebritelabs.com/wp-content/uploads/2019/07/lab-worker-looking-at-vial-of-blood.jpg" alt="" width="1184" height="1376" /> -->
                  </div>
                </figure>
              </div>
            </div>
            <div class="mt-8 lg:mt-0">
              <div class="mt-5 prose prose-blue text-gray-500 mx-auto lg:max-w-none lg:row-start-1 lg:col-start-1">
                <p class="text-justify">Desde 1996 somos el laboratorio de análisis clínicos y microbiología referente en la zona.</p>
                <p class="text-justify">Cuidamos a nuestros pacientes con calidez en la atención y ética profesional.</p>
                <p class="text-justify">Formamos un grupo humano calificado que trabaja en equipo, cumpliendo con las tareas y objetivos planteados. Bajo la premisa de la capacitación y desarrollo profesional constante.</p>
                <p class="text-justify">Nuestro compromiso reside en brindar un servicio de salud de excelencia, mediante la entrega de informes con resultados confiables y de calidad para nuestros pacientes y los profesionales de la salud.</p>
                <p class="text-justify">Brindamos atención personalizada, en tiempo oportuno, procurando satisfacer las necesidades de nuestros pacientes con la incorporación de innovación y la mejora en la tecnología aplicada a nuestros procesos.</p>            
              </div>
            </div>
          </div>
        </div>
        <div class="relative max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
          <div class="flex flex-col md:flex-row items-center justify-evenly ">
            <div>
              <img class="h-32 md:40 " src="/img/lab_acreditado.png" alt="" />
            </div>
            <div>
              <img class="h-52 " src="/img/peec.png" alt="" />
            </div>
          </div>
          
        </div>
      </div>

      <!-- CTA Section -->
      <div class="bg-white relative">
        <div aria-hidden="true" class="absolute inset-x-0 bottom-0 h-32 bg-gray-50" />  
        <div class="relative max-w-4xl mx-auto py-14  px-6  sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8 lg:flex lg:items-center lg:justify-between bg-white rounded-2xl shadow-lg">
            <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight text-gray-700">
            <span class="block">Resultados online</span>
            <span class="block bg-gradient-to-r from-blue-700 to-blue-500 bg-clip-text text-transparent">Accede desde aquí al portal de resultados</span>
            </h2>
            <div class="mt-6 space-y-4 sm:space-y-0 sm:flex sm:space-x-5">
            <a href="#" class="flex items-center justify-center bg-blue-500 bg-origin-border px-4 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white hover:bg-blue-600">INGRESAR</a>
            <a href="https://wa.me/5491126887264?text=Hola" class="flex items-center justify-center px-4 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-blue-800 bg-blue-50 hover:bg-blue-100">Más información</a>
            </div>
        </div>
      </div>

    </main>

    <footers />

  </div>
</template>

<script>

import { GlobeAltIcon, LightningBoltIcon, ScaleIcon, MailIcon, PhoneIcon, ClockIcon, ChatIcon } from '@heroicons/vue/outline'
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
const faqs = [
  {
    question: 'Horarios de hisopado COVID19',
    answer:
      'Los hisopados son sin turno de 11:00 a 15:00 hs. de lunes a viernes y sábados de 9:00 a 12:00 hs. \nTest de antígeno de 7:30 a 9.30 (Hisopado PCR).',
  },
  {
    question: '¿Cómo obtener mis resultados?',
    answer:
      'Puede retirar sus resultados de lunes a viernes de 14 a 18 hs.,  sábados de 10 a 13 hs. o bien descargarlos a través del portal en cualquier momento.',
  },
  {
    question: '¿Cuales son los horario de atención y la ubicación?',
    answer: 'Atendemos de lunes a viernes de 7:30 a 18:00 hs, los sábados de 7:30 a 13:00. hs. Las extracciones son hasta 10:30 hs. Nuestra dirección es Margarita Weild 1200, Lanús Este, Prov. de Buenos Aires'
      ,
  },
  {
    question: '¿Como solicito un servicio a domicilio?',
    answer:
      'Lo puede solicitar a nuestro asistente virtual haciendo click <b><a class="hover:underline" href="https://api.whatsapp.com/send?phone=541127714569&text=Hola" target="_blank"> aqui</a></b>.',
  },

]

const solutions = [
  {
    name: 'Inbox',
    description: 'Get a better understanding of where your traffic is coming from.',
    href: '#',
    icon: InboxIcon,
  },
  {
    name: 'Messaging',
    description: 'Speak directly to your customers in a more meaningful way.',
    href: '#',
    icon: AnnotationIcon,
  },
  { name: 'Live Chat', description: "Your customers' data will be safe and secure.", href: '#', icon: ChatAlt2Icon },
  {
    name: 'Knowledge Base',
    description: "Connect with third-party tools that you're already using.",
    href: '#',
    icon: QuestionMarkCircleIcon,
  },
]
const features = [
  {
    name: 'Hisopados',
    description: 'Realizamos testeos para detectar COVID-19, Influenza A y B y Estreptococo Beta Hemolítico.',
    icon: InboxIcon,
  },
  {
    name: 'Extracciones a domicilio',
    description: 'Ofrecemos extracciones a domicilio para que no tenga que salir de su casa. Solicite su turno online <b><a href="https://api.whatsapp.com/send?phone=541127714569&text=Hola" target="_blank"> aqui</a></b>',
    icon: UsersIcon,
  },
  {
    name: 'Resultados online',
    description: 'Reciba sus resultados de forma rápida y segura a través del siguiente <b><a href="https://www.suresultado.com/labexpress/Labexpress-form.html" target="_blank"> sitio</a></b>.',
    icon: TrashIcon,
  },
  {
    name: 'Empresas e instituciones',
    description: 'Atención y asesoramiento a empresas e instituciones, exámenes pre ocupacionales, anuales y otros servicios. Consultanos <a href="mailto:info@laboratoriodelsur.com.ar">info@laboratoriodelsur.com.ar</a>.',
    icon: PencilAltIcon,
  },

]
const metrics = [
  { id: 1, stat: '8K+', emphasis: 'Companies', rest: 'use laoreet amet lacus nibh integer quis.' },
  { id: 2, stat: '25K+', emphasis: 'Countries around the globe', rest: 'lacus nibh integer quis.' },
  { id: 3, stat: '98%', emphasis: 'Customer satisfaction', rest: 'laoreet amet lacus nibh integer quis.' },
  { id: 4, stat: '12M+', emphasis: 'Issues resolved', rest: 'lacus nibh integer quis.' },
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
    ChatIcon,
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    Headers,
    Footers,
    Whatsappbtn },
  setup() {
    return {
      solutions,
      features,
      metrics,
      footerNavigation,
      faqs,
    //   features
    }
  },
}
</script>