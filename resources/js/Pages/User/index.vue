<script setup>
import { Link, router } from '@inertiajs/vue3';
import UserLayout from './Layouts/UserLayout.vue';
import Hero from './Layouts/Hero.vue';
import Products from './Components/Products.vue'

// product
defineProps({
    products: Object
});

// tidak kita gunkan karena kita pakai yg di dalam component
// const addTocart = (product) => {
//   router.post(route('cart.store',product),{
//     onSuccess: (page) => {
//       if(page.props.flash.succes){
//         Swal.fire({
//                     toast: true,
//                     icon: "success",
//                     position: "top-end",
//                     showConfirmButton: false,
//                     title: page.props.flash.success
//                 });
//       }
//     }
//   });
// };

</script>

<template>
<div class="min-h-screen">
    <UserLayout>

      <!-- hero section -->
      <Hero/>

  <main class="bg-white">
    <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
      <h2 class="text-2xl font-bold tracking-tight text-gray-900">Latest Product</h2>

      <!-- ini yg coba pisahkan components -->
      <Products :products="products.data"></Products>

      <!-- <div class="mt-6 grid grid-cols-1 gap-6 gap-x-6 gap-y-6 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8"> -->
        <!-- <div v-for="product in products" :key="product.id" class="group relative">
          <div class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-md bg-gray-200 lg:aspect-none group-hover:opacity-75 lg:h-80">
            <img v-if="product.product_image[0]" :src="'/'+product.product_image[0].image" :alt="product.title" class="h-full w-full object-cover object-center lg:h-full lg:w-full" />
            <img v-else src="https://t4.ftcdn.net/jpg/04/99/93/31/360_F_499933117_ZAUBfv3P1HEOsZDrnkbNCt4jc3AodArl.jpg" :alt="product.title" class="h-full w-full object-cover object-center lg:h-full lg:w-full" />
          </div>

          <div class="absolute inset-0 flex justify-center items-center opacity-0 group-hover:opacity-100 hover:cursor-pointer transition duration-300">
            <span class="bg-blue-500 rounded-full p-1 hover:opacity-80" @click="addTocart(product)">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
              </svg>

            </span>
          </div>

          <div class="mt-4 flex justify-between relative">
            <div>
              <h3 class="text-sm text-gray-700">
                <a :href="product.id" >
                  <span aria-hidden="true" class="absolute inset-0 ml-3"/>
                  {{ product.title }}
                </a>
              </h3>
              <p class="mt-1 text-sm text-gray-500">{{ product.brand.name }}</p>
            </div>
            <p class="text-sm font-medium text-gray-900 ml-3">{{ product.price }}</p>
          </div>
        </div> -->
      <!-- </div> -->
      <!-- disini kita akan coba pisah, karena list products ini juga akan digunkan di bagian productList maka pengulangan nya akan kita buat menjadi komponen agar dapat digunakan berulang kali -->

      <div class="flex justify-center">
        <Link :href="route('product.index')" class="relative inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-green-400 to-blue-600 group-hover:from-green-400 group-hover:to-blue-600 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-green-200 dark:focus:ring-green-800">
          <span class="relative px-5 py-2.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0 font-extrabold">
        More Load
          </span>
        </Link>
      </div>

    </div>
  </main>
    </UserLayout>
</div>   
</template>