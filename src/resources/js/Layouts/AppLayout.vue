<template>
  <div class="min-h-screen font-['inter'] ">
    <Disclosure as="nav" class="bg-indigo-600" v-slot="{ open }">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <!-- <img class="w-24" src="/img/logo.png" /> -->
              <!-- <img class="h-8 w-8" src="https://tailwindui.com/img/logos/workflow-mark-indigo-300.svg" alt="Workflow" /> -->
            </div>
            <div class="hidden md:block">
              <div class="ml-10 flex items-baseline space-x-4">
                <a v-for="item in navigation" :key="item.name" :href="item.href" :class="[item.current ? 'bg-indigo-700 text-white' : 'text-white hover:bg-indigo-500 hover:bg-opacity-75', 'px-3 py-2 rounded-md text-sm font-medium']" :aria-current="item.current ? 'page' : undefined">{{ item.name }}</a>
              </div>
            </div>
          </div>
          <div class="hidden md:block">
            <div class="ml-4 flex items-center md:ml-6">
              <!-- <button type="button" class="p-1 bg-indigo-600 rounded-full text-indigo-200 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-indigo-600 focus:ring-white">
                <span class="sr-only">View notifications</span>
                <BellIcon class="h-6 w-6" aria-hidden="true" />
              </button> -->

              <!-- Profile dropdown -->
              <Menu as="div" class="ml-3 relative">
                <div>
                  <MenuButton class="max-w-xs bg-indigo-600 rounded-full flex items-center text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-indigo-600 focus:ring-white">
                    <span class="sr-only">Open user menu</span>
                    <!-- <img class="h-8 w-8 rounded-full" :src="user.imageUrl" alt="" /> -->
                    <div class="bg-white rounded-full p-2">{{ userInitials }}</div>
                  </MenuButton>
                </div>
                <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                  <MenuItems class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
                    <MenuItem v-for="item in userNavigation" :key="item.name" v-slot="{ active }">
                      <a :href="item.href" :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']">{{ item.name }}</a>
                    </MenuItem>
                  </MenuItems>
                </transition>
              </Menu>
            </div>
          </div>
          <div class="-mr-2 flex md:hidden">
            <!-- Mobile menu button -->
            <DisclosureButton class="bg-indigo-600 inline-flex items-center justify-center p-2 rounded-md text-indigo-200 hover:text-white hover:bg-indigo-500 hover:bg-opacity-75 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-indigo-600 focus:ring-white">
              <span class="sr-only">Open main menu</span>
              <MenuIcon v-if="!open" class="block h-6 w-6" aria-hidden="true" />
              <XIcon v-else class="block h-6 w-6" aria-hidden="true" />
            </DisclosureButton>
          </div>
        </div>
      </div>

      <DisclosurePanel class="md:hidden">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
          <DisclosureButton v-for="item in navigation" :key="item.name" as="a" :href="item.href" :class="[item.current ? 'bg-indigo-700 text-white' : 'text-white hover:bg-indigo-500 hover:bg-opacity-75', 'block px-3 py-2 rounded-md text-base font-medium']" :aria-current="item.current ? 'page' : undefined">{{ item.name }}</DisclosureButton>
        </div>

      </DisclosurePanel>
    </Disclosure>

    <!-- <header class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
        <slot name="header" />
      </div>
    </header> -->
    <main>
      <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <slot name="content" />
        <!-- Replace with your content -->
        <!-- <div class="px-4 py-4 sm:px-0">
          <div class="border-4 border-dashed border-gray-200 rounded-lg h-96" />
        </div> -->
        <!-- /End replace -->
      </div>
    </main>
  </div>
</template>

<script>
import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { BellIcon, MenuIcon, XIcon } from '@heroicons/vue/outline'

const user = {
  name: 'Tom Cook',
  email: 'tom@example.com',
  imageUrl:
    'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
}
const navigation = [
  { name: 'Mensajes', href: route('dashboard'), current: window.location.pathname === '/dashboard' ? true : false },
  { name: 'Contactos', href: route('contacts'), current: window.location.pathname === '/contacts' ? true : false },
  { name: 'Turnos', href: route('booking'), current: window.location.pathname === '/booking' ? true : false },
  /* { name: 'Reportes', href: '' , current: false }, */
  { name: 'Usuarios', href: route('user') , current: window.location.pathname === '/user' ? true : false },
  { name: 'FAQs', href: route('faqs.index') , current: window.location.pathname === '/faq' ? true : false },
  { name: 'O. Sociales', href: route('obras-sociales.index') , current: window.location.pathname === '/obras-sociales/index' ? true : false },
  { name: 'Estudios', href: route('estudios.index') , current: window.location.pathname === '/estudios/index' ? true : false },
  { name: 'Configuración', href: route('settings'), current: window.location.pathname === '/settings' ? true : false },
]
const userNavigation = [
  { name: 'Salir', href: route('logout') }
]

export default {
  components: {
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    BellIcon,
    MenuIcon,
    XIcon,
  },
  computed: {
    userInitials() {

      // if (this.$page.auth.user) {
      //   const name = this.$page.auth.user.name;
      if (this.$page.props.user.name) {
        const name = this.$page.props.user.name;        
        const names = name.split(' ');

        if (names.length > 1) {
          const initials = names[0].charAt(0) + names[names.length - 1].charAt(0);
          return initials.toUpperCase();
        } else {
          return name.charAt(0).toUpperCase();
        }
      }

      return '';
    }
  },  
  setup() {
    return {
      user,
      navigation,
      userNavigation,
    }
  },
}
</script>