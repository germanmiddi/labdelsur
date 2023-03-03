<template>
    <div class="fixed z-50 bottom-10 right-20 hidden md:block">
        <a :href="links['whatsapp_btn']" target="_blank">
            <div class="bg-green-100 flex justify-center items-center rounded-full  border-white cursor-pointer 
                        py-3 px-5 shadow-xl md:border-4
                        hover:bg-green-200">
            <img class="h-10 h-8 mr-2" src="/img/whatsapp.png" alt="">
            <div class="text-xl text-green-800">Escribinos!</div>
            </div>
        </a>
    </div>

    <div class="fixed z-50 bottom-10 right-5 md:hidden">
        <div class="bg-green-200 p-2 rounded-full flex justify-center shadow-md">
            <img class="h-10" src="/img/whatsapp.png" alt="">
        </div>
    </div>
</template>

<script>

export default {
    name:"Whatsappbtn",
    props:{
      links:Object
    },    
}

</script>
