<template>
  <div class="bg-white">
    <header>
      <Popover class="relative bg-white">
        <div class="flex justify-between items-center max-w-7xl mx-auto px-4 py-6 sm:px-6 md:justify-start md:space-x-10 lg:px-8">
          <div class="flex justify-start lg:w-0 lg:flex-1">
            <a href="#">
              <img class="w-32" src="/img/logo.png" />
            </a>
          </div>
          <div class="-mr-2 -my-2 md:hidden">
            <PopoverButton class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
              <span class="sr-only">Open menu</span>
              <MenuIcon class="h-6 w-6" aria-hidden="true" />
            </PopoverButton>
          </div>
          <PopoverGroup as="nav" class="hidden md:flex space-x-10">
            <a href="#" class="text-base font-semibold font-['jost'] text-neutral-500 hover:text-gray-900"> NOSOTROS </a>
            <a href="#" class="text-base font-semibold font-['jost'] text-neutral-500 hover:text-gray-900"> RESULTADOS </a>
            <a :href="route('preguntas-frecuentes')" class="text-base font-semibold font-['jost'] text-neutral-500 hover:text-gray-900"> PREGUNTAS FRECUENTES </a>
            <a href="#" class="text-base font-semibold font-['jost'] text-neutral-500 hover:text-gray-900"> UBICACIÓN </a>
          </PopoverGroup>
        </div>

        <transition enter-active-class="duration-200 ease-out" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="duration-100 ease-in" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
          <PopoverPanel focus class="absolute z-30 top-0 inset-x-0 p-2 transition transform origin-top-right md:hidden">
            <div class="rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 bg-white divide-y-2 divide-gray-50">
              <div class="pt-5 pb-6 px-5">
                <div class="flex items-center justify-between">
                  <div>
                    <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/workflow-mark-purple-600-to-blue-600.svg" alt="Workflow" />
                  </div>
                  <div class="-mr-2">
                    <PopoverButton class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                      <span class="sr-only">Close menu</span>
                      <XIcon class="h-6 w-6" aria-hidden="true" />
                    </PopoverButton>
                  </div>
                </div>
                <div class="mt-6">
                  <nav class="grid grid-cols-1 gap-7">
                    <a v-for="item in solutions" :key="item.name" :href="item.href" class="-m-3 p-3 flex items-center rounded-lg hover:bg-gray-50">
                      <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-md bg-gradient-to-r from-purple-600 to-blue-600 text-white">
                        <component :is="item.icon" class="h-6 w-6" aria-hidden="true" />
                      </div>
                      <div class="ml-4 text-base font-medium text-gray-900">
                        {{ item.name }}
                      </div>
                    </a>
                  </nav>
                </div>
              </div>
              <div class="py-6 px-5">
                <div class="grid grid-cols-2 gap-4">
                  <a href="#" class="text-base font-medium text-gray-900 hover:text-gray-700"> Pricing </a>
                  <a href="#" class="text-base font-medium text-gray-900 hover:text-gray-700"> Partners </a>
                  <a href="#" class="text-base font-medium text-gray-900 hover:text-gray-700"> Company </a>
                </div>
                <div class="mt-6">
                  <a href="#" class="w-full flex items-center justify-center bg-gradient-to-r from-purple-600 to-blue-600 bg-origin-border px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white hover:from-purple-700 hover:to-blue-700"> Sign up </a>
                  <p class="mt-6 text-center text-base font-medium text-gray-500">
                    Existing customer?
                    <a href="#" class="text-gray-900"> Sign in </a>
                  </p>
                </div>
              </div>
            </div>
          </PopoverPanel>
        </transition>
      </Popover>
    </header>

    <main>
      <div class="fixed z-50 bottom-10 right-20">
        <div class="bg-green-100 flex justify-center items-center rounded-full py-3 px-5 shadow-xl border-4 border-white cursor-pointer 
                      hover:bg-green-200">
          <img class="h-10 mr-2" src="/img/whatsapp.png" alt="">
          <div class="text-xl text-green-800">Escribinos!</div>
        </div>
      </div>
      <!-- Hero section -->
      <div class="relative">
        <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gray-100" />
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="relative shadow-xl sm:rounded-2xl sm:overflow-hidden">
            <div class="absolute inset-0">
              <img class="h-full w-full object-cover" src="/img/banner.jpg" alt="People working on laptops" />
              <div class="absolute inset-0 bg-gradient-to-r from-blue-800 to-gray-500 mix-blend-multiply" />
            </div>
            <div class="relative px-4 py-16 sm:px-6 sm:py-24 lg:py-32 lg:px-8">
              <h1 class="text-center text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl">
                <span class="block text-white">Atención profesional personalizada</span>
                <span class="block text-blue-200"></span>
              </h1>
              <p class="mt-6 max-w-lg mx-auto text-center text-xl text-blue-200 sm:max-w-3xl">
                Somos el laboratorio reconocido por brindar servicios de calidad y confiables, por la calidez de nuestra atención y la rapidez en la entrega de protocolos.</p>
              <div class="mt-10 max-w-sm mx-auto sm:max-w-none sm:flex sm:justify-center">
                <div class="space-y-4 sm:space-y-0 sm:mx-auto sm:inline-grid sm:grid-cols-2 sm:gap-5">
                  <a href="#" class="flex items-center justify-center px-4 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-blue-700 bg-white hover:bg-blue-50 sm:px-8 tracking-wide"> Resultados</a>
                  <a href="#" class="flex items-center justify-center px-4 py-3 border border-transparent text-base font-bold rounded-md shadow-sm text-white bg-blue-500 bg-opacity-60 hover:bg-opacity-70 sm:px-8 tracking-wide">TURNOS</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Logo Cloud -->
      <!-- <div class="bg-gray-100">
        <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
          <div class="mt-6 grid grid-cols-2 gap-8 md:grid-cols-6 lg:grid-cols-5">
            <div class="col-span-2 flex justify-center md:col-span-2 md:col-start-4 lg:col-span-1">
              <img class="h-20 " src="/img/obrassociales/swiss.png" alt="" />
            </div>
            <div class="col-span-1 flex justify-center md:col-span-2 lg:col-span-1">
              <img class="h-20 " src="/img/obrassociales/galeno.png" alt="" />
            </div>
            <div class="col-span-1 flex justify-center md:col-span-2 md:col-start-2 lg:col-span-1">
              <img class="h-20 " src="/img/obrassociales/medife.png" alt="" />
            </div>
            <div class="col-span-2 flex justify-center md:col-span-2 md:col-start-4 lg:col-span-1">
              <img class="h-20 " src="/img/obrassociales/pami.png" alt="" />
            </div>
            <div class="col-span-2 flex justify-center md:col-span-2 md:col-start-4 lg:col-span-1">
              <img class="h-20 " src="/img/obrassociales/sancorsalud.png" alt="" />
            </div>
          </div>
        </div>
      </div> -->

      <div class="bg-gray-100">
          <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:py-24 lg:px-8 lg:grid lg:grid-cols-3 lg:gap-x-8">
            <div>
                <h2 class="text-base font-semibold text-blue-600 uppercase tracking-wide">Lorem ipsum.</h2>
                <p class="mt-2 text-3xl font-extrabold text-gray-900">Análisis Clinicos</p>
                <p class="mt-4 text-lg text-gray-500">Ac euismod vel sit maecenas id pellentesque eu sed consectetur. Malesuada adipiscing sagittis vel nulla nec.</p>
            </div>
            <div class="mt-12 lg:mt-0 lg:col-span-2">
                <dl class="space-y-10 sm:space-y-0 sm:grid sm:grid-cols-2 sm:grid-rows-4 sm:grid-flow-col sm:gap-x-6 sm:gap-y-10 lg:gap-x-8">
                    <div v-for="feature in features" :key="feature.name" class="relative">
                        <dt>
                            <CheckIcon class="absolute h-6 w-6 text-green-500" aria-hidden="true" />
                            <p class="ml-9 text-lg leading-6 font-medium text-gray-900">{{ feature.name }}</p>
                        </dt>
                        <dd class="mt-2 ml-9 text-base text-gray-500">
                            {{ feature.description }}
                        </dd>
                    </div>
                </dl>
            </div>
          </div>
      </div>

      <!-- Testimonial section -->
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
              <h2 class="text-2xl font-extrabold tracking-tight text-gray-200 sm:text-3xl">Contactanos</h2>
              <p class="mt-3 text-lg leading-6 text-gray-50">Nullam risus blandit ac aliquam justo ipsum. Quam mauris volutpat massa dictumst amet. Sapien tortor lacus arcu.</p>
              <dl class="mt-8 text-base text-gray-100">
                <div>
                  <dt class="sr-only">Postal address</dt>
                  <dd>
                    <p>Margarita Weild 1200</p>
                    <p>Lanús Este, Prov. Buenos Aires</p>
                  </dd>
                </div>
                <div class="mt-6">
                  <dt class="sr-only">Phone number</dt>
                  <dd class="flex">
                    <PhoneIcon class="flex-shrink-0 h-6 w-6 text-blue-200" aria-hidden="true" />
                    <span class="ml-3"> 4225-0789 / 4249-8651</span>
                  </dd>
                </div>
                <div class="mt-3">
                  <dt class="sr-only">Email</dt>
                  <dd class="flex">
                    <MailIcon class="flex-shrink-0 h-6 w-6 text-blue-200" aria-hidden="true" />
                    <span class="ml-3">labdelsur@yahoo.com.ar</span>
                  </dd>
                </div>
                <div class="mt-3">
                  <dt class="sr-only">Email</dt>
                  <dd class="flex">
                    <ClockIcon class="flex-shrink-0 h-6 w-6 text-blue-200" aria-hidden="true" />
                    <span class="ml-3">Lunes a Viernes 7:30 a 18:00 / Sábados 7:30 a 13:00</span>
                  </dd>
                </div>
              </dl>

            </div>
          </div>
        </div>
      </div>

      <!-- <section class="text-gray-600 body-font relative">
        <div class="absolute inset-0 bg-gray-300">
        </div>
        <div class="container px-5 py-24 mx-auto flex">
          <div class="lg:w-1/3 md:w-1/2 bg-white rounded-lg p-8 flex flex-col md:ml-auto w-full mt-10 md:mt-0 relative z-10 shadow-md">
            <div class="max-w-lg mx-auto">
              <h2 class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl">Contactanos</h2>
              <p class="mt-3 text-lg leading-6 text-gray-500">Nullam risus blandit ac aliquam justo ipsum. Quam mauris volutpat massa dictumst amet. Sapien tortor lacus arcu.</p>
              <dl class="mt-8 text-base text-gray-500">
                <div>
                  <dt class="sr-only">Postal address</dt>
                  <dd>
                    <p>Margarita Weild 1200</p>
                    <p>Lanús Este, Prov. Buenos Aires</p>
                  </dd>
                </div>
                <div class="mt-6">
                  <dt class="sr-only">Phone number</dt>
                  <dd class="flex">
                    <PhoneIcon class="flex-shrink-0 h-6 w-6 text-blue-500" aria-hidden="true" />
                    <span class="ml-3"> 4225-0789 / 4249-8651</span>
                  </dd>
                </div>
                <div class="mt-3">
                  <dt class="sr-only">Email</dt>
                  <dd class="flex">
                    <MailIcon class="flex-shrink-0 h-6 w-6 text-blue-500" aria-hidden="true" />
                    <span class="ml-3">labdelsur@yahoo.com.ar</span>
                  </dd>
                </div>
                <div class="mt-3">
                  <dt class="sr-only">Email</dt>
                  <dd class="flex">
                    <ClockIcon class="flex-shrink-0 h-6 w-6 text-blue-500" aria-hidden="true" />
                    <span class="ml-3">Lunes a Viernes 7:30 a 18:00 / Sábados 7:30 a 13:00</span>
                  </dd>
                </div>
              </dl>
            </div>
          </div>
        </div>
      </section> -->

      <div class="bg-gray-50">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:py-16 sm:px-6 lg:px-8">
          <div class="max-w-3xl mx-auto divide-y-2 divide-gray-200">
            <h2 class="text-center text-3xl font-extrabold text-gray-900 sm:text-4xl">Preguntas Frecuentes
              <p class="mt-3 text-lg leading-6 font-normal text-gray-500">Aquí encontrará las respuestas a las consultas más comunes, si no encuentra la información que necesita puede contactarnos por whatsapp.</p>  
            </h2>
            
            <dl class="mt-6 space-y-6 divide-y divide-gray-200">
              <Disclosure as="div" v-for="faq in faqs" :key="faq.question" class="pt-6" v-slot="{ open }">
                <dt class="text-lg">
                  <DisclosureButton class="text-left w-full flex justify-between items-start text-gray-400">
                    <span class="font-medium text-gray-900">
                      {{ faq.question }}
                    </span>
                    <span class="ml-6 h-7 flex items-center">
                      <ChevronDownIcon :class="[open ? '-rotate-180' : 'rotate-0', 'h-6 w-6 transform']" aria-hidden="true" />
                    </span>
                  </DisclosureButton>
                </dt>
                <DisclosurePanel as="dd" class="mt-2 pr-12">
                  <p class="text-base text-gray-500">
                    {{ faq.answer }}
                  </p>
                </DisclosurePanel>
              </Disclosure>
            </dl>
          </div>
        </div>
      </div>

      <div class="overflow-hidden relative">
        <div aria-hidden="true" class="absolute inset-x-0 top-0 h-48 bg-gradient-to-b from-gray-50 " />
        <div class="relative max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
          <div class="hidden lg:block bg-gray-50 absolute top-0 bottom-0 left-3/4 w-screen" />
          <div class="mx-auto text-base max-w-prose lg:grid lg:grid-cols-2 lg:gap-8 lg:max-w-none">
            <div>
              <h2 class="text-base text-blue-600 font-semibold tracking-wide uppercase">Nosotros</h2>
              <h3 class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">Calidad y profesionalismo desde hace 25 años</h3>
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
                    <img class="rounded-lg shadow-lg object-cover object-center" src="https://lifebritelabs.com/wp-content/uploads/2019/07/lab-worker-looking-at-vial-of-blood.jpg" alt="" width="1184" height="1376" />
                  </div>
                </figure>
              </div>
            </div>
            <div class="mt-8 lg:mt-0">
              <div class="mt-5 prose prose-blue text-gray-500 mx-auto lg:max-w-none lg:row-start-1 lg:col-start-1">
                <p>Desde 1996 somos el laboratorio de análisis clínicos referente en la zona. </p>
                <p>Cuidamos a nuestros pacientes guiados bajo los principios de solidaridad y ética profesional.</p>
                <p>Formamos un grupo humano calificado que trabaja en equipo, cumpliendo con las tareas y objetivos planteados y bajo la premisa de la capacitación y desarrollo profesional constante.</p>
                <p>Nuestro compromiso reside en brindar un servicio de salud de excelencia, mediante la entrega de informes con resultados confiables y de calidad para nuestros pacientes y los profesionales de la salud.</p>
                <p>Brindamos atención personalizada, en tiempo oportuno, procurando satisfacer las necesidades de nuestros pacientes con la incorporación de innovación y la mejora en la tecnología aplicada a nuestros procesos.</p>            
              </div>
            </div>
          </div>
        </div>
        <div class="relative max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
          <div class="flex items-center justify-evenly ">
            <div>
              <img class="h-40 " src="/img/iso.png" alt="" />
            </div>
            <div>
              <img class="h-52 " src="/img/peec.png" alt="" />
            </div>
          </div>
          
        </div>
      </div>

      <!-- <div class="bg-gray-50 pt-12 sm:pt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">Trusted by developers from over 80 planets</h2>
            <p class="mt-3 text-xl text-gray-500 sm:mt-4">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Repellendus repellat laudantium.</p>
          </div>
        </div>
        <div class="mt-10 pb-12 bg-white sm:pb-16">
          <div class="relative">
            <div class="absolute inset-0 h-1/2 bg-gray-50" />
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
              <div class="max-w-4xl mx-auto">
                <dl class="rounded-lg bg-white shadow-lg sm:grid sm:grid-cols-3">
                  <div class="flex flex-col border-b border-gray-100 p-6 text-center sm:border-0 sm:border-r">
                    <dt class="order-2 mt-2 text-lg leading-6 font-medium text-gray-500">Pepperoni</dt>
                    <dd class="order-1 text-5xl font-extrabold text-indigo-600">100%</dd>
                  </div>
                  <div class="flex flex-col border-t border-b border-gray-100 p-6 text-center sm:border-0 sm:border-l sm:border-r">
                    <dt class="order-2 mt-2 text-lg leading-6 font-medium text-gray-500">Delivery</dt>
                    <dd class="order-1 text-5xl font-extrabold text-indigo-600">24/7</dd>
                  </div>
                  <div class="flex flex-col border-t border-gray-100 p-6 text-center sm:border-0 sm:border-l">
                    <dt class="order-2 mt-2 text-lg leading-6 font-medium text-gray-500">Calories</dt>
                    <dd class="order-1 text-5xl font-extrabold text-indigo-600">100k</dd>
                  </div>
                </dl>
              </div>
            </div>
          </div>
        </div>
      </div> -->


        <!-- CTA Section -->
      <div class="bg-white relative">
        <div aria-hidden="true" class="absolute inset-x-0 bottom-0 h-32 bg-gray-50" />  
        <div class="relative max-w-4xl mx-auto py-14  px-6  sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8 lg:flex lg:items-center lg:justify-between bg-white rounded-2xl shadow-lg">
            <h2 class="text-4xl font-extrabold tracking-tight text-gray-700 sm:text-4xl">
            <span class="block">Resultados online</span>
            <span class="block bg-gradient-to-r from-blue-700 to-blue-500 bg-clip-text text-transparent">Accede desde aquí al portal de resultados</span>
            </h2>
            <div class="mt-6 space-y-4 sm:space-y-0 sm:flex sm:space-x-5">
            <a href="#" class="flex items-center justify-center bg-gradient-to-r from-blue-700 to-blue-500 bg-origin-border px-4 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white hover:from-purple-700 hover:to-blue-700">INGRESAR</a>
            <a href="#" class="flex items-center justify-center px-4 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-blue-800 bg-blue-50 hover:bg-blue-100">Consultas</a>
            </div>
        </div>
                
      </div>

    </main>
 

    <footer class="bg-gray-50" aria-labelledby="footer-heading">
      <div class="max-w-7xl mx-auto pt-12 pb-8 px-4 sm:px-6 lg:pt-20 lg:px-8 flex flex-col items-center">
        <div class="mb-8">
          <img class="w-52" src="/img/logo.png" />
        </div>
        <div class="w-1/2">
          <ul class="flex justify-between text-gray-700">
            <li>NOSOTROS</li>
            <li>RESULTADOS</li>
            <li>TURNOS</li>
            <li>UBICACIÓN</li>
          </ul>
        </div>
        <div class="mt-12 border-t border-gray-200 pt-8 lg:mt-12 w-full">
          <p class="mt-8 text-base text-gray-400 md:mt-0 md:order-1 text-center">&copy; 2022 - Diseño y Desarrollo por onMedia.</p>
        </div>
      </div>
    </footer>
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

const faqs = [
  {
    question: 'Horarios de hisopado COVID19',
    answer:
      'You boil the hell out of it. Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas cupiditate laboriosam fugiat.',
  },
  {
    question: '¿Cómo obtener mis resultados?',
    answer:
      'You boil the hell out of it. Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas cupiditate laboriosam fugiat.',
  },
  {
    question: '¿Cuales son los horario de atención y la ubicación?',
    answer:
      'You boil the hell out of it. Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas cupiditate laboriosam fugiat.',
  },
  {
    question: '¿Como solicito un servicio a domicilio?',
    answer:
      'You boil the hell out of it. Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas cupiditate laboriosam fugiat.',
  },
  {
    question: 'How do you make holy water?',
    answer:
      'You boil the hell out of it. Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas cupiditate laboriosam fugiat.',
  },
  {
    question: 'How do you make holy water?',
    answer:
      'You boil the hell out of it. Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas cupiditate laboriosam fugiat.',
  },
  // More questions...
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
    name: 'Hisopados COVID',
    description: 'Ac tincidunt sapien vehicula erat auctor pellentesque rhoncus. Et magna sit morbi lobortis.',
    icon: InboxIcon,
  },
  {
    name: 'Extracciones a domicilio',
    description: 'Ac tincidunt sapien vehicula erat auctor pellentesque rhoncus. Et magna sit morbi lobortis.',
    icon: UsersIcon,
  },
  {
    name: 'Resultados online',
    description: 'Ac tincidunt sapien vehicula erat auctor pellentesque rhoncus. Et magna sit morbi lobortis.',
    icon: TrashIcon,
  },
  {
    name: '',
    description: 'Ac tincidunt sapien vehicula erat auctor pellentesque rhoncus. Et magna sit morbi lobortis.',
    icon: PencilAltIcon,
  },
  {
    name: 'Team Reporting',
    description: 'Ac tincidunt sapien vehicula erat auctor pellentesque rhoncus. Et magna sit morbi lobortis.',
    icon: DocumentReportIcon,
  }
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

// const features = [
//   {
//     name: 'Invite team members',
//     description: 'You can manage phone, email and chat conversations all from a single mailbox.',
//   },
//   { name: 'List view', description: 'You can manage phone, email and chat conversations all from a single mailbox.' },
//   {
//     name: 'Keyboard shortcuts',
//     description: 'You can manage phone, email and chat conversations all from a single mailbox.',
//   },
//   { name: 'Calendars', description: 'You can manage phone, email and chat conversations all from a single mailbox.' },
//   { name: 'Notifications', description: 'Find what you need with advanced filters, bulk actions, and quick views.' },
//   { name: 'Boards', description: 'Find what you need with advanced filters, bulk actions, and quick views.' },
//   { name: 'Reporting', description: 'Find what you need with advanced filters, bulk actions, and quick views.' },
//   { name: 'Mobile app', description: 'Find what you need with advanced filters, bulk actions, and quick views.' },
// ]

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
  
  },
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