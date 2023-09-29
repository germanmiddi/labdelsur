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
                <span class="block text-white">Nuevo Turno</span>
              </h1>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-gradient-to-b from-white to-gray-100">
        <div class="max-w-5xl mx-auto py-12 px-4 divide-y divide-gray-200 sm:px-6 lg:py-16 lg:px-8">
          <form id="form" class="mt-10">

            <div class="space-y-12">
              <div class="border-b border-gray-900/10 pb-12">
                <h2 class="text-2xl font-semibold leading-7 text-gray-900">Complete el formulario y reserve su turno</h2>
                <p class="mt-1 text-lg leading-6 text-gray-500">Recibira en su mail la confirmación del turno.</p>
                <div class="my-10 w-4/5  bg-indigo-50 px-4 py-6 rounded-md shadow-md">    
                  <svg class="text-white w-16 h-16 absolute z-0" 
                      xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                  </svg>
                  
                  <p class="relative text-xl z-10 ml-9 text-gray-700">
                    Este servicio de turnos es exclusivo para afiliados/as de la obra social <span class="font-bold  text-indigo-500">UTA</span>. 
                    <br>El horario de los turnos es siempre de 7:30 a 10:30, debe traer la orden médica y la autorización impresa.
                  </p>
                </div>
                <div v-if="msjForm.show" 
                    :class="classMessageBox[msjForm.type]" 
                    class=" rounded-lg px-3 py-4 border-l-2  mt-5">
                  <p v-html="msjForm.message"></p>
                </div>
                
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                  <div class="col-span-1 sm:col-span-3">
                    <label for="name" class="block text-lg font-medium leading-6 text-gray-900">Nombre</label>
                    <div class="mt-2">
                      <input type="text" name="name" id="name" 
                              v-model="name"
                              class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                    </div>
                  </div>
                  <div class="col-span-1 sm:col-span-3">
                    <label for="lastname" class="block text-lg font-medium leading-6 text-gray-900">Apellido</label>
                    <div class="mt-2">
                      <input type="text" name="lastname" id="lastname" 
                              v-model="lastname"        
                              class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                    </div>
                  </div>
                  <div class="col-span-1 sm:col-span-3">
                    <label for="email" class="block text-lg font-medium leading-6 text-gray-900">Email</label>
                    <div class="mt-2">
                      <input id="email" name="email" type="email" autocomplete="email" 
                              v-model="email"
                              class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                    </div>
                  </div>
        
                  <div class="col-span-1 sm:col-span-3">
                    <label for="telefono" class="block text-lg font-medium leading-6 text-gray-900">Teléfono</label>
                    <div class="mt-2">
                      <input type="text" name="telefono" id="telefono" 
                             v-model="telefono"
                             class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                    </div>
                  </div>

                  <div class="col-span-1 sm:col-span-3">
                    <label for="afiliado" class="block text-lg font-medium leading-6 text-gray-900">DNI / Nro Afiliado</label>
                    <div class="mt-2">
                      <input type="number" name="afiliado" id="afiliado" 
                             v-model="afiliado"
                             class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                    </div>
                  </div>
        
                  <div class="col-span-1 sm:col-span-6">
                    <div class="mt-2">
                      <p class="text-2xl">Próximos Turnos Disponibles</p>
                      <div v-if="errorDate" class="bg-red-100 rounded-lg px-3 py-4 border-l-2 border-red-500 mt-5">
                        <p class="text-red-500">Debe seleccionar una fecha</p>
                      </div>
                      <BookingItem :fechas="bookings" @dateSelected="handleDateSelected"></BookingItem>
                    </div>
                  </div>

                </div>

              </div>

            </div>
        
            <div class="mt-6 flex items-center justify-center gap-x-6">
              <button @click.prevent="submit" 
                      class="flex items-center rounded-md  px-3 py-2 text-xl font-semibold  shadow-sm 
                          bg-indigo-600 hover:bg-indigo-500 text-white
                            focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Confirmar Turno 
                <Icons v-if="loading" name="loading" class="w-5 h-5 ml-2"/></button>
            </div>
          </form>          

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
import BookingItem from './BookingItem.vue'
import Icons from '@/Layouts/Components/Icons.vue'

const classMessageBox = {
	'success' : 'bg-green-50 text-green-800 border-green-600',
	'error'   : 'bg-red-100 text-red-500 border-red-600',

}

export default {
  props: {
    obras_img: Object,
    obras: Object,
    links: Object,
    bookings: Object        
  },
  components: {
    Whatsappbtn,
    Headers,
    Footers,
    BookingItem,
    Icons
  },
  setup() {
    return { 
      classMessageBox 
    }
  },
  data(){
    return{
      name: "",
      lastname: "",
      email: "",
      telefono: "",
      afiliado: "",
      fechaSeleccionada: '',
      filtro:"",
      errorDate: false,
      errorForm: false,
      loading: false,
      msjForm: {
        show: false, // Inicialmente oculto
        message: '',
        type: '', 
      },
    }
  },
  methods:{
    handleDateSelected(selectedDate) {
      this.fechaSeleccionada = selectedDate;
    },
    scrollToTop() {
      // Obtén una referencia al elemento con id="top"
      const topElement = document.getElementById('form');

      // Comprueba si el elemento existe
      if (topElement) {
        // Utiliza scrollIntoView para desplazarte hacia el elemento
        topElement.scrollIntoView({ behavior: 'smooth' });
      }
    },    
    
    async submit(){
      
      if(this.loading) return
      this.loading = true

      this.errorForm = false
      this.errorDate = false
      const camposVacios = [];

      if (this.name == '') {
        camposVacios.push('name');
      }
      if (this.lastname === '') {
        camposVacios.push('lastname');
      }
      if (this.email === '') {
        camposVacios.push('email');
      }
      if (this.telefono === '') {
        camposVacios.push('telefono');
      }
      if (this.afiliado === '') {
        camposVacios.push('afiliado');
      }
      if (this.fechaSeleccionada === null) {
        this.errorDate = true
        camposVacios.push('fecha');
      }

      // Aplica la clase "border b-red-500" a los campos vacíos
      camposVacios.forEach((campo) => {
        const inputElement = document.getElementById(campo);
        if (inputElement) {
          inputElement.classList.remove('border-0');
          inputElement.classList.add('border-1', 'border-red-500');
          
        }
      });

      console.log(camposVacios)
      // Si hay campos vacíos, muestra un mensaje de error y no envía los datos
      if (camposVacios.length > 0) {
        this.errorForm = true
        this.scrollToTop()
        return false
      }

      let form = new FormData()
      form.append('first_name', this.name)
      form.append('last_name', this.lastname)
      form.append('email', this.email)
      form.append('phone', this.telefono)
      form.append('nro_afiliado', this.afiliado)
      form.append('date', this.fechaSeleccionada)

      // let route = route('turno_post');
      
      const post = route('turno_post') //`${route('turno_post')}`
      
      try{
        const response = await axios.post(post, form) //fetch(route('api/turno_post'), { method: 'POST' });
        // const message = await response.json()
        console.log(response)
        
        if(response.status == 200){
          this.name = ""
          this.lastname = ""
          this.email = ""
          this.telefono = ""
          this.afiliado = ""
          this.fechaSeleccionada = ""
          this.loading = false
          this.msjForm.show = true;
          this.msjForm.message = 'Turno reservado con éxito. <br> Recibirá en su correo electrónico la confirmación del turno.';
          this.msjForm.type = 'success';
          this.scrollToTop()
        }else{
          this.loading = false
          this.msjForm.show = true;
          this.msjForm.message = 'Error al reservar el turno';
          this.msjForm.type = 'error';
          this.scrollToTop()
        }
      }catch(error){
        this.loading = false
          this.msjForm.show = true;
          this.msjForm.message = 'Error al reservar el turno';
          this.msjForm.type = 'error';
          this.scrollToTop()
        console.log(error)
      }
        
      // let request  = await axios.post(route, form)
      // let response = await request.json()

      // if(response.status == 'success'){
      //   this.$toast.success(response.message)
      // }

    },
  }
}
</script>