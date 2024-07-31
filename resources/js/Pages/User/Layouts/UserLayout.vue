<script setup>
import { onMounted } from 'vue';
import { initFlowbite } from 'flowbite';
import NavBar from './NavBar.vue'
import Hero from './Hero.vue';
import Footer from './Footer.vue';
onMounted(() => {
    initFlowbite();
});


</script>
<template>
    <div class="min-h-screen">
    
        <!-- header -->
        <NavBar/>
        <!-- end header -->
    
    <!-- main content -->
    <slot></slot>
    <!-- end main content -->
    
        <!-- footer -->
        <Footer></Footer>
        <!-- end footer -->
     
    </div>   
</template>