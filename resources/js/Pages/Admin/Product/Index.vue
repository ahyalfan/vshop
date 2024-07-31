<template>
    <AdminLayout>
        <ProductList :produc="products"></ProductList>
        <!-- <h1>{{ products }}</h1> -->
    </AdminLayout>
</template>

<script setup>
defineProps({
    products:Array
})
import { usePage } from "@inertiajs/vue3";
import AdminLayout from "../Components/AdminLayout.vue"
import ProductList from "./ProductList.vue";
</script>