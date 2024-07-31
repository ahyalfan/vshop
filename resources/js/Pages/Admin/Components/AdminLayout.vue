<template>
    <div class="antialiased bg-gray-50 dark:bg-gray-900">
    <!-- navbar start -->
        <NavBar></NavBar>
    <!-- navbar end -->

    <!-- Sidebar start -->
        <Sidebar></Sidebar>
    <!-- Sidebar end -->
  
        <!-- slot -->
        <main class="p-4 md:ml-64 h-auto pt-20">
            <slot></slot>
        </main>

    <!-- footer start -->
        <Footer></Footer>
    <!-- footer end -->

    
  </div>
</template>

<!-- ini untuk melakukan reactive halamanya yg sudah sibuatkan flowbite -->
<script setup>
import { onMounted } from 'vue'
import { initFlowbite } from 'flowbite'
import NavBar from "./NavBar.vue"
import Sidebar from './Sidebar.vue'
import Footer from './Footer.vue'

// initialize components based on data attribute selectors
onMounted(() => {
    initFlowbite();
});
</script>