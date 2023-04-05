<template>
    <div class="flex">
      <div class="bg-gray-200 p-4 flex-shrink-0">
        <ul class="list-none">
          <li v-for="(item, index) in items" :key="index">
            <a href="#" @click.prevent="selectItem(index)" :class="{ 'font-bold': selectedIndex === index }" class="block py-2 px-4 rounded hover:bg-gray-300">{{ item.name }}</a>
          </li>
        </ul>
      </div>
      <div class="flex-grow p-4">
        <component :is="selectedItem.componentName" :data="selectedItem.componentData" />
      </div>
    </div>
  </template>
  
  <script>
  import Item1Component from './General.vue';
  import Item2Component from './Turnos.vue';
  import Item3Component from './Whatsapp.vue';
  import {
    UserCircleIcon,
  } from '@heroicons/vue/outline'  

  export default {
    components: {
      Item1Component,
      Item2Component,
      Item3Component,
      UserCircleIcon
    },
    data() {
      return {
        selectedIndex: 0,
        items: [
          {
            name: 'Item 1',
            componentName: 'Item1Component',
            componentData: { message: 'Este es el componente 1.',
            icon: 'UserCircleIcon' }
          },
          {
            name: 'Item 2',
            componentName: 'Item2Component',
            componentData: { message: 'Este es el componente 2.',
            icon: 'UserCircleIcon' }
          },
          {
            name: 'Item 3',
            componentName: 'Item3Component',
            componentData: { message: 'Este es el componente 3.',
            icon: 'UserCircleIcon' }
          }
        ]
      }
    },
    methods: {
      selectItem(index) {
        this.selectedIndex = index;
      }
    },
    computed: {
      selectedItem() {
        return this.items[this.selectedIndex];
      }
    }
  }
  </script>
  
  <style>
  /* Las clases de Tailwind CSS se importan por CDN o instalando el paquete con NPM */
  </style>