<template>

    <transition name="slide-fade">
        <div v-if="toast && this.visible" 
        
            class="max-w-xs w-full flex items-center p-4 fixed top-0 mt-5 mr-5 right-0 z-50 rounded-lg  shadow-xl bg-white"
            :class="[type === 'danger' ? ' bg-red-100' : type === 'info' ? 'bg-blue-100' : 'bg-green-100']"
            
            >
            <div class="mr-2">
                <Icons v-if="type === 'danger'"       name="danger"  class="w-7 h-7 text-red-500"/> 
                <Icons v-else-if="type === 'success'" name="success" class="w-7 h-7 text-green-500"/> 
                <Icons v-else-if="type === 'info'"    name="info"    class="w-7 h-7 text-blue-500"/> 
            </div>
    
            <div class="flex-1 text-gray-800">{{this.toast}} </div>

            <div class="ml-2">
                <Icons name="x" class="w-4 h-4" />
            </div>
        </div>  
    </transition>

  
</template>

<script>
    import Icons from '@/Layouts/Components/Icons.vue'

    export default {
        props:{
            toast: Object,
            type: String
        },
        components:{
            Icons
        },
        data(){
            return{
                visible:false,
                timeOut: null
            }
        },
        watch:{
            toast:{
                handler:function(){
                    this.visible = true
                    if(this.timeOut){
                        clearTimeout(this.timeOut)
                    }
                    this.timeOut = setTimeout(()=> { 
                        this.visible = false
                        this.$emit("clear")
                        }, 3000)
                },
                deep:true
            }
        }

}
</script>

<style>
    /*
    Enter and leave animations can use different
    durations and timing functions.
    */
    .slide-fade-enter-active {
        transition: all 0.3s ease-out;
    }

    .slide-fade-leave-active {
        transition: all 0.8s cubic-bezier(1, 0.5, 0.8, 1);
    }

    .slide-fade-enter-from,
    .slide-fade-leave-to {
        transform: translateX(20px);
        opacity: 0;
    }
</style>