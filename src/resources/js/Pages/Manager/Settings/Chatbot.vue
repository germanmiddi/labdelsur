<template lang="">

    <Toast :toast="this.toastMessage" :type="this.labelType" @clear="clearMessage"></Toast>                         

    <div v-for="m in messages" :key="m.id" class="shadow sm:rounded-md sm:overflow-hidden">
        <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
            <div>
                <div id="plan-heading" 
                     class="text-base leading-4 font-medium text-gray-700">{{m.step}}</div>
            </div>
            <div class="grid grid-cols-5 gap-6">
                <div class="col-span-5 sm:col-span-4">
                    <label for="newMessage" class="block text-sm font-medium text-gray-700">Mensaje</label>
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <textarea rows="10" type="text" name="newMessage" id="newMessage" 
                                  style="whiteSpace: break-spaces" v-model="m.message" 
                                  class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                    </div>
                </div>
            </div>    
        </div>
        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
            <button  @click.prevent="updateMessage(m)" 
                     class="bg-indigo-500 border border-transparent rounded-md shadow-sm py-2 px-3 inline-flex      
                            justify-center text-sm text-white hover:bg-indigo-600 
                            focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Guardar</button>
        </div>
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
            messages: "",
            updMessage:"",
            updId:"",
            newMessage: '',
            showUpdate: false            
        }
    },

    methods: {
        clearMessage() {
			this.toastMessage = ""
		},        

		async getMessages() {
			const response = await axios.get(route('chatbotmessage.list'))
            if(response.status == 200){
                this.messages = response.data
            }
		},        

        async updateMessage(m){

            console.log(m)
            let response = await axios.post(route('chatbotmessage.update'), {
                                                   id: m.id,
                                                   message: m.message })
            
            if(response.status == 200){
                this.labelType = "success"
                this.toastMessage = response.data.message
            }else{
                this.labelType = "danger"
                this.toastMessage = response.data.message
            }
        },
    },

    created() {
        this.getMessages()
    },

}
</script>
<style lang="">
    
</style>