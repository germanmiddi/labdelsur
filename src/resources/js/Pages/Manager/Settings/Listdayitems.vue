<template>
      <!-- <tr v-if="country.show" class="hover:bg-gray-50">
        <td class="border-t px-6 py-4 text-center hidden">{{ country.id }}</td>
        <td class="border-t px-6 py-4 text-center">
            {{ country.pais }}
        </td>
        <td class="border-t px-6 py-4 text-center">
            <input v-if="showInput['codigo_afip']" type="text" 
                   v-model="country.codigo_afip" 
                   @keyup.enter="update('codigo_afip')"
                   @keyup.escape="undoInput('codigo_afip')"
                   class="rounded-md text-sm py-2 px-2 w-12" />            
            <span v-else 
                  @click="editMode('codigo_afip', country.codigo_afip )" 
                  class="border border-transparent p-2 
                         hover:border 
                         hover:border-gray-300 
                         hover:p-2 
                         hover:rounded-md 
                         hover:pointer">            
                    {{ country.codigo_afip }}</span>
        </td>
    </tr> -->
    <!-- eslint-disable -->

    <Toast :toast="this.toastMessage" :type="this.labelType" @clear="clearMessage"></Toast>                         
    <tr class="bg-white border-b text-center focus-within:bg-gray-100">
        <th scope="row"
            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
            {{day.description}}
        </th>
        <td class="py-4 px-6">
            <input type="number" 
                   @click="editMode = !editMode"
                   v-model="day.cant_orders" 
                   @keyup.enter="update"
                   @keyup.escape="undoInput"
                   class="border rounded-md text-sm py-1 px-2 w-12 text-right :hover:border hover:border-gray-300 hover:rounded-md hover:pointer"
                   :readonly="!editMode"
                   :class="editMode ? 'border-gray-300' : 'focus:ring-0 border-transparent'" 
                   ref="myInput"/>
            <!-- <span v-else @click="showInput = !showInput"
                  class="border border-transparent p-2 
                         hover:border 
                         hover:border-gray-300 
                         hover:rounded-md 
                         hover:pointer" >{{day.cant_orders}}</span> -->

        </td>
        <!-- <td class="py-4 px-6">
            <button @click="editDay = true,
            form_day.id=day.id,
            form_day.num_day=day.num_day,
            form_day.description=day.description,
            form_day.cant_orders=day.cant_orders"
                class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-blue-300 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <Icons name="edit" class="h-5 w-5"></Icons>
            </button>
        </td> -->
    </tr>


</template>

<script>

import Toast from '@/Layouts/Components/Toast.vue'

export default {

    props: {
        day: {
            type: Object,
            required: true
        }
    },

    components:{
        Toast
    },

    data(){
        return{
            editMode: false,
            labelType: "info",
            toastMessage: "",

        }
    },
    
    methods: {
        clearMessage() {
			this.toastMessage = ""
		},
        update(){

            // alert(this.day.cant_orders)
            // return

            axios.post(route('settings.updateday'), {
                id: this.day.id,
                cant_orders: this.day.cant_orders
                
            }).then(response => {
                this.labelType = "success"
				this.toastMessage = response.data.message
                this.editMode = false
                this.$refs.myInput.blur();
            }).catch(error => {
                this.labelType = "danger"
                this.toastMessage = error.response.data.message
            })
            
        },
        undoInput(){
            this.editMode = false
        }
    }

}

</script>
