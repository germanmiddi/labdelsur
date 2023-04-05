<template lang="">
    <Toast :toast="this.toastMessage" :type="this.labelType" @clear="clearMessage"></Toast>
    <div class="shadow sm:rounded-md sm:overflow-hidden">
        <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
            <div>
                <h2 id="plan-heading" class="text-lg leading-6 font-medium text-gray-900">Whatsapp</h2>
            </div>
            <div class="grid grid-cols-3 gap-6">

                <div class="col-span-3 sm:col-span-2">
                    <label for="wp_url"
                        class="block text-sm font-medium text-gray-700">URL | WhatsApp</label>
                    <input type="text" name="wp_url" id="wp_url" v-model="wp_url"
                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                </div>

                <div class="col-span-3 sm:col-span-2">
                    <label for="wp_url_media"
                        class="block text-sm font-medium text-gray-700">URL | WhatsApp Media</label>
                    <input type="text" name="wp_url_media" id="wp_url_media" v-model="wp_url_media"
                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                </div>

                <div class="col-span-3 sm:col-span-2">
                    <label for="wp_token"
                        class="block text-sm font-medium text-gray-700">Token | WhatsApp</label>
                    <input type="text" name="wp_token" id="wp_token" v-model="wp_token"
                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                </div>

            </div>    
        </div>
        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
            <button @click="updateWhatsapp" class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Guardar</button>
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
            wp_token: "",
            wp_url: "",
            wp_url_media: "",
            toastMessage: "",
            labelType: "info"

        }
    },

    methods: {
        clearMessage() {
			this.toastMessage = ""
		},

        async getWhatsapp() {
            axios.get(route('settings.getwhatsapp'))
            .then(response => {
                console.log(response.data.filter(item => item.key === "wp_token"));
                this.wp_url_media = response.data.find(item => item.key === "wp_url_media").value
                this.wp_url = response.data.find(item => item.key === "wp_url").value
                this.wp_token = response.data.find(item => item.key === "wp_token").value

            })
            .catch(error => {
                console.log(error);
            });
        },

        async updateWhatsapp() {
            axios.post(route('settings.updatewhatsapp'), {
                wp_url: this.wp_url,
                wp_url_media: this.wp_url_media,
                wp_token: this.wp_token
            })
            .then(response => {
                this.labelType = "success"
				this.toastMessage = response.data.message
            })
            .catch(error => {
				this.labelType = "danger"
				this.toastMessage = error.response.data.message
            });
        }
    },

    created() {
        this.getWhatsapp();
    },


}
</script>

<style lang="">
    
</style>