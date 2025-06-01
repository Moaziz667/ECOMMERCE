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
      <p class="text-green-600 font-bold text-xl mb-4">${{ price }}</p>
      <button
        class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-md transition-colors duration-200"
      >
        Add to Cart
      </button>
    </div>
  </div>
</template>

<script setup>
defineProps(['name', 'price', 'image'])
</script>
