<template>
   <header>
      <Popover class="relative bg-white">
        <div class="flex justify-between items-center max-w-7xl mx-auto px-4 py-6 sm:px-6 md:justify-start md:space-x-10 lg:px-8">
          <div class="flex justify-start lg:w-0 lg:flex-1">
            <a :href="route('home')">
              <img class="w-40" src="/img/logo.png" />
            </a>
          </div>
          <div class="-mr-2 -my-2 md:hidden">
            <PopoverButton class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
              <span class="sr-only">Open menu</span>
              <MenuIcon class="h-6 w-6" aria-hidden="true" />
            </PopoverButton>
          </div>
          <PopoverGroup as="nav" class="hidden md:flex space-x-10">
            <!-- <a href="#" class="text-base font-semibold font-['jost'] text-neutral-500 hover:text-gray-900"> NOSOTROS </a> -->
            <a href="#" class="text-base font-semibold font-['jost'] text-neutral-500 hover:text-gray-900"> RESULTADOS </a>
            <a :href="route('estudios')" class="text-base font-semibold font-['jost'] text-neutral-500 hover:text-gray-900"> ESTUDIOS </a>
            <a :href="route('preguntas-frecuentes')" class="text-base font-semibold font-['jost'] text-neutral-500 hover:text-gray-900"> PREGUNTAS FRECUENTES </a>
            <a :href="route('obras-sociales')" class="text-base font-semibold font-['jost'] text-neutral-500 hover:text-gray-900"> OBRAS SOCIALES </a>
            <!-- <a href="#" class="text-base font-semibold font-['jost'] text-neutral-500 hover:text-gray-900"> UBICACIÓN </a> -->
          </PopoverGroup>
        </div>

        <transition enter-active-class="duration-200 ease-out" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="duration-100 ease-in" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
          <PopoverPanel focus class="absolute z-30 top-0 inset-x-0 p-2 transition transform origin-top-right md:hidden">
            <div class="rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 bg-white divide-y-2 divide-gray-50">
              <div class="pt-5 pb-6 px-5">
                <div class="flex items-center justify-between">
                  <div class="-mr-2">
                    <PopoverButton class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                      <span class="sr-only">Close menu</span>
                      <XIcon class="h-6 w-6" aria-hidden="true" />
                    </PopoverButton>
                  </div>
                </div>
              </div>
              <div class="py-6 px-5">
                <div class="flex flex-col">
                  <!-- <a href="#" class="text-base font-medium text-gray-900 hover:text-gray-700 py-2"> NOSOTROS </a> -->
                  <a href="#" class="text-base font-medium text-gray-900 hover:text-gray-700 py-2"> RESULTADOS </a>
                  <a :href="route('estudios')" class="text-base font-medium text-gray-900 hover:text-gray-700 py-2"> ESTUDIOS </a>
                  <a :href="route('preguntas-frecuentes')" class="text-base font-medium text-gray-900 hover:text-gray-700 py-2"> PREGUNTAS FRECUENTES </a>
                  <a :href="route('obras-sociales')" class="text-base font-medium text-gray-900 hover:text-gray-700 py-2"> OBRAS SOCIALES </a>
                  <!-- <a href="#" class="text-base font-medium text-gray-900 hover:text-gray-700 py-2"> UBICACIÓN </a> -->
                </div>
              </div>
            </div>
          </PopoverPanel>
        </transition>
      </Popover>
    </header>
</template>

<script>
import { Popover, PopoverButton, PopoverGroup, PopoverPanel } from '@headlessui/vue'
import { MenuIcon,XIcon } from '@heroicons/vue/outline'


export default {
    name:"Headers",
    components:{
        Popover,
        PopoverButton,
        PopoverGroup,
        PopoverPanel,
        MenuIcon,
        XIcon
    }
}
</script>

<style>

</style>