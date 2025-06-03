<template>
  <div
    class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300"
    style="font-family: 'Poppins', sans-serif;"
  >
    <img
      :src="image"
      alt="product"
      class="w-full h-48 object-cover rounded-t-lg"
    />
    <div class="p-4">
      <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ name }}</h3>
      <p class="text-gray-700 mb-2">{{ description }}</p>
      <p class="text-gray-500 text-sm mb-2">Category: {{ category }}</p>
      <p class="text-gray-500 text-sm mb-4">Available: {{ quantity }}</p>
      <p class="text-green-600 font-bold text-xl mb-4">${{ price }}</p>
      <button
        class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-md transition-colors duration-200"
        @click="addToCart"
      >
        Add to Cart
      </button>
    </div>
  </div>
</template>

<script setup>
import { useStore } from 'vuex'

const store = useStore()

const props = defineProps([
  'name',
  'price',
  'image',
  'description',
  'category',
  'quantity',
  'product_id'
])

function addToCart() {
  store.commit('addToCart', {
    product_id: props.product_id,
    libelle: props.name,
    prix: props.price,
    image_url: props.image,
    description: props.description,
    category: props.category,
    quantity: 1
  })
}
</script>
