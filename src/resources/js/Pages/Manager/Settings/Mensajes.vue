<template lang="">
    <Toast :toast="this.toastMessage" :type="this.labelType" @clear="clearMessage"></Toast>                         
    <div class="shadow sm:rounded-md sm:overflow-hidden">
        <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
            <div>
                <h2 id="plan-heading" class="text-lg leading-6 font-medium text-gray-900">Nuevo Mensaje</h2>
            </div>
            <div class="grid grid-cols-5 gap-6">
                <div v-if="!showUpdate"  class="col-span-5 sm:col-span-4">
                    <label for="newMessage" class="block text-sm font-medium text-gray-700">Mensaje</label>
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <textarea rows="10" type="text" name="newMessage" id="newMessage"
                                v-model="newMessage" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                    </div>
                </div>

                <div v-else class="col-span-5 sm:col-span-4">
                    <label for="newMessage" class="block text-sm font-medium text-gray-700">Modificar Mensaje</label>
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <input type="hidden" name="id" id="id" v-model="updIdMessage">
                        <textarea rows="10" type="text" name="newMessage" id="newMessage"
                                v-model="updMessage" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                    </div>
                </div>
            </div>    
        </div>
        <div v-if="!showUpdate" class="px-4 py-3 bg-gray-50 text-right sm:px-6">
            <button  @click.prevent="createMessage" class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-md font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Crear Mensaje</button>
        </div>
        <div v-else class="px-4 py-3 bg-gray-50 text-right sm:px-6">
            <button  @click.prevent="updateMessage" class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-md font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Guardar</button>
        </div>
    </div>
    <div class="shadow sm:rounded-md sm:overflow-hidden">
        <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
            <div>
                <h2 id="plan-heading" class="text-lg leading-6 font-medium text-gray-900">Mensajes Predefinidos</h2>
            </div>
            
            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">    
                    <!-- <thead class="bg-indigo-500">
                        <tr>
                            <th class="px-6 py-4 text-sm font-medium text-white uppercase tracking-wider text-center"></th>
                        </tr>
                    </thead> -->
                    <tbody>
                        <tr v-for="message in messages" :key="message.id"
                            class="bg-white border-b text-left flex items-center hover:bg-gray-50 focus-within:bg-gray-100">
                            <td class="py-4 px-6 text-sm" style="whiteSpace: break-spaces">
                                {{message.description}}
                            </td>
                            <td class="py-4 px-6 flex items-center ">
                                <button @click="updMessage = message.description; updIdMessage = message.id; showUpdate = true"
                                        class="mr-1 bg-indigo-600 border border-transparent rounded-md shadow-sm py-1 px-2 inline-flex justify-center text-xs font-normal text-white hover:bg-indigo-700 ">Editar</button>
                                <TrashIcon @click="deleteMessage(message.id)"
                                            class="p-1 w-7 h-7 text-red-500 hover:bg-red-400 hover:text-white rounded-md cursor-pointer" />

                                <!-- <TrashIcon  
                                            class="text-red-500
                                                   border border-transparent rounded-md py-1 px-3 
                                                    hover:bg-red-500 hover:text-white 
                                                   focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" /> -->
                            </td>
                        </tr>
                    </tbody>
                </table> 
            </div>

        </div>
        <!-- <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
            <button class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Guardar</button>
        </div> -->
    </div>
</template>

<script>

import { TrashIcon } from '@heroicons/vue/outline'  
import Toast from '@/Layouts/Components/Toast.vue'

export default {
    components: {
        TrashIcon,
        Toast
    },

    data() {
        return {
            labelType: "info",
            toastMessage: "",            
            id: '',
            messages: [],
            updMessage:"",
            updId:"",
            newMessage: '',
            showUpdate: false            
        }
    },

    filters: {
        nl2br(value) {
            return value.replace(/\n/g, "<br>");
        }
    },  

    methods: {
        clearMessage() {
			this.toastMessage = ""
		},        
        getMessages() { 
            this.$inertia.get(route('manager.settings.mensajes.index'))
        },

		async getMessages() {
			this.messages = []

            const get = `${route('settings.listmessage')}`

			const response = await fetch(get, { method: 'GET' })
			this.messages = await response.json()
		},        

        createMessage() {

            if(this.newMessage == ''){
                this.labelType = "danger"
                this.toastMessage = "El mensaje no puede estar vacío"
                return
            }
            axios.post(route('settings.storemessage'), {
                        message: this.newMessage,
            }).then(response=>{
                this.labelType = "success"
				this.toastMessage = response.data.message
                this.newMessage = ''
                this.getMessages()
               
            }).catch(error=>{
                this.labelType = "danger"
                this.toastMessage = error.response.data.message                
            })
        },

        deleteMessage(id) {
            axios.delete(route('settings.deletemessage', id))
                 .then(response=>{
                    this.labelType = "success"
                 })
        },

        async updateMessage(){
            if(this.updMessage == ''){
                this.labelType = "danger"
                this.toastMessage = "El mensaje no puede estar vacío"
                return
            }
            
            axios.post(route('settings.updatemessage'), {
                id: this.updIdMessage,
                message: this.updMessage,
            }).then(response=>{
                this.labelType = "success"
                this.toastMessage = response.data.message
                this.updMessage = ''
                this.updId = ''
                this.showUpdate = false
                this.getMessages()
            }).catch(error=>{
                this.labelType = "danger"
                this.toastMessage = error.response.data.message                
            })
            
        },
        
    },

    created() {
        this.getMessages()
    },

}
</script>
<style lang="">
    
</style>