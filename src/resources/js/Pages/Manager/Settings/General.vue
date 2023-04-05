<template lang="">
    <Toast :toast="this.toastMessage" :type="this.labelType" @clear="clearMessage"></Toast>
    <div class="shadow sm:rounded-md sm:overflow-hidden">
        <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
            <div>
                <h2 id="plan-heading" class="text-lg leading-6 font-medium text-gray-900">General</h2>
            </div>
            <div>
                <h2 id="plan-heading" class="text-base  leading-6 font-medium text-gray-900">Chatbot</h2>
            </div>

            <div class="grid grid-cols-3 gap-6">
                
                <!-- <div v-for="data in settings" 
                    class="col-span-3 sm:col-span-2">
                    <label :for="data.id" class="block text-sm font-medium text-gray-700">{{data.description}}</label>
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <input type="text" :name="data.id" :id="data.id" v-model="data.value"
                               class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-md sm:text-sm border-gray-300" />
                    </div>
                </div> -->

                <div class="col-span-3 sm:col-span-2">
                    <label for="waiting_time" class="block text-sm font-medium text-gray-700">Tiempo de espera asesor <span class="text-gray-400">(indicar tiempo en minutos)</span></label>
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <input v-model="waiting_time" type="number" name="waiting_time" id="waiting_time" 
                               class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-md sm:text-sm border-gray-300" />
                    </div>
                </div>
            </div>    
            <div class="w-full border-t border-gray-100 mt-10"></div>
            <div>
                <h2 id="plan-heading" class="text-base leading-6 font-medium text-gray-900">Pagina Web</h2>
            </div>

            <div class="grid grid-cols-3 gap-6">
                
                <div class="col-span-3 sm:col-span-2">
                    <label for="kern_url" class="block text-sm font-medium text-gray-700">Link de estudios médicos KERN</label>
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <input v-model="kern_url" type="text" name="kern_url" id="kern_url" 
                               class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-md sm:text-sm border-gray-300" />
                    </div>
                </div>

                <div class="col-span-3 sm:col-span-2">
                    <label for="whatsapp_btn" class="block text-sm font-medium text-gray-700">Link del botón de Whatsapp</label>
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <input v-model="whatsapp_btn" type="text" name="whatsapp_btn" id="whatsapp_btn" 
                               class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-md sm:text-sm border-gray-300" />
                    </div>
                </div>
            </div>    
        </div>
        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
            <button @click.prevent="updateGeneral" type="button" 
                    class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Guardar</button>
        </div>
    </div>
</template>

<script>

import Toast from '@/Layouts/Components/Toast.vue'

export default {
    components: {
        Toast
    },

    data() {
        return {
            toastMessage: "",
            labelType: "info",
            settings: [],

            waiting_time: "",
            old_waiting_time: "",


            kern_url: "",
            old_kern_url: "",

            whatsapp_btn: "",
            old_whatsapp_btn: "",

        }
    },

    mounted() {
        this.getGeneral()
    },
    watch: {
        waiting_time: function(newValue, oldValue) {
            if (newValue !== oldValue) {
                this.upd_waiting_time = true;
            }
        },        

    },
    methods: {
        clearMessage() {
            this.toastMessage = ""
        },
        async getGeneral() {
           
            const response = await fetch((route('settings.getgeneral')), { method: 'GET' })
			const settings = await response.json()
            
			this.waiting_time = settings.find(item=>item.key === 'waiting_time').value
            this.old_waiting_time = this.waiting_time

            this.kern_url = settings.find(item=>item.key === 'kern_url').value
            this.old_kern_url = this.kern_url

            this.whatsapp_btn = settings.find(item=>item.key === 'whatsapp_btn').value
            this.old_whatsapp_btn = this.whatsapp_btn

            // this.waiting_time = settings.find(item=>item.key === 'waiting_time').value
            // this.old_waiting_time = this.waiting_time
        },

        async updateGeneral() {
           
            if(this.waiting_time == ''){
                this.toastMessage = "El tiempo de espera no puede estar vacío"
                this.labelType = "error"
                return
            }
            let rows = []

            if(this.waiting_time != this.old_waiting_time ){
                rows.push({
                    key: 'waiting_time',
                    value: this.waiting_time
                })
            }

            if(this.kern_url != this.old_kern_url ){
                rows.push({
                    key: 'kern_url',
                    value: this.kern_url
                })
            }

            if(this.whatsapp_btn != this.old_whatsapp_btn ){
                rows.push({
                    key: 'whatsapp_btn',
                    value: this.whatsapp_btn
                })
            }

            if(rows.length == 0){
                this.toastMessage = "No se han realizado cambios"
                this.labelType = "info"
                return
            }else{

                const response = await axios.post((route('settings.updategeneral')), { rows })

                if (response.status === 200) {
                    this.toastMessage = response.data.message;
                    this.labelType = "success";
                    this.old_waiting_time = this.waiting_time
                    this.old_kern_url = this.kern_url
                    this.old_whatsapp_btn = this.whatsapp_btn

                } else {
                    this.toastMessage = response.data.message;
                    this.labelType = "danger";
                }
                
            }
           
        },

    },

}
</script>
<style lang="">
    
</style>