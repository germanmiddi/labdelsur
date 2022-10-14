<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Pedidos
            </h2>
             <a class="btn-blue" :href="route('orders.create')">
                    Nuevo Pedido
            </a>  
        </template>
        
        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <table class="w-full whitespace-nowrap"> 
                        <tr class="text-left font-bold bg-blue-500 text-white">
                            <th class="px-6 py-4 text-center">ID</th>
                            <th class="px-6 py-4 text-center">Fecha</th>
                            <th class="px-6 py-4 text-center">Column</th>
                            <th class="px-6 py-4 text-center">Column</th>
                        </tr>
                        <tr v-for="order in orders" :key="order.id" class="hover:bg-gray-100 focus-within:bg-gray-100 text-sm hover:bg-gray-100 focus-within:bg-gray-100">
                            <td class="border-t px-6 py-4 text-center">
                                {{order.id}}
                            </td>
                            <td class="border-t px-6 py-4 text-center">
                                {{order.fecha}}
                            </td>
                            <td class="border-t px-6 py-4 text-center">
                                {{order.estado}}
                            </td>
                            <td class="border-t px-6 py-4 text-center">
                                {{order.cliente}}
                            </td>
                        </tr>
                    </table>    
                </div>                      
            </div>
        </div>  

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <!-- <Welcome /> -->
                </div>
            </div>
        </div>
    </AppLayout>
</template>
<script>

    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue';
    import JetButton from '@/Jetstream/Button.vue';

    export default defineComponent({
        components: {
            AppLayout,      
            JetButton,
        },
        data(){
            return{
                orders: [
                    {"id"      : "123",
                     "fecha"   : "11/02/2022",
                     "estado"  : "Aprobado" ,
                     "cliente" : "John Doe" },
                    {"id"      : "123",
                     "fecha"   : "11/02/2022",
                     "estado"  : "Aprobado" ,
                     "cliente" : "John Doe" }]
                
            }
        }
    })
</script>
