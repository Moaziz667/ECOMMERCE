<template>
  <div style="font-family: 'Poppins', sans-serif;">
    <Navbar />

    <!-- HERO -->
    <section class="relative bg-cover bg-center h-96 overflow-hidden"
      :style="{ backgroundImage: `url('https://4kwallpapers.com/images/wallpapers/cristiano-ronaldo-2560x1080-9595.jpg')` }"
    >
      <div class="absolute inset-0 bg-black bg-opacity-60"></div>
      <div class="relative z-10 flex flex-col justify-center items-center h-full text-white px-4 text-center max-w-4xl mx-auto animate-zoomIn">
        <h1 class="text-4xl md:text-6xl font-bold mb-4">Welcome to Sportify</h1>
        <p class="mb-6 text-lg md:text-xl">Premium sportswear, footwear & equipment – trusted by athletes worldwide.</p>
        <button class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-md text-lg transition">Browse Products</button>
      </div>
    </section>

    <!-- PRODUCTS GRID -->
    <div class="max-w-6xl mx-auto px-4 py-10">
      <h2 class="text-3xl font-semibold mb-6">Featured Products</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
        <SportCard
          v-for="product in products"
          :key="product.product_id"
          :product_id="product.product_id"
          :name="product.libelle"
          :price="product.prix"
          :image="product.image_url"
          :description="product.description"
          :category="product.category"
          :quantity="product.quantity"
        />
      </div>
    </div>

    <!-- FOOTER -->
    <footer class="bg-gray-900 text-gray-300 py-10 mt-20">
      <div class="max-w-6xl mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8">
        <div>
          <h4 class="text-white text-xl font-bold mb-4">Sportify</h4>
          <p>Elevate your game with world-class gear.</p>
        </div>
        <div>
          <h4 class="text-white text-xl font-bold mb-4">Quick Links</h4>
          <ul>
            <li><a href="#" class="hover:text-green-400 transition">Home</a></li>
            <li><a href="#" class="hover:text-green-400 transition">Shop</a></li>
            <li><a href="#" class="hover:text-green-400 transition">Login</a></li>
            <li><a href="#" class="hover:text-green-400 transition">Register</a></li>
          </ul>
        </div>
        <div>
          <h4 class="text-white text-xl font-bold mb-4">Contact</h4>
          <p>Email: support@sportify.com</p>
          <p>Phone: +1 800 555 1234</p>
        </div>
      </div>
      <div class="text-center text-gray-500 mt-8">&copy; 2025 Sportify. All rights reserved.</div>
    </footer>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import Navbar from '../components/Navbar.vue'
import SportCard from '../components/SportCard.vue'

const products = ref([])

async function fetchProducts() {
  try {
    const response = await fetch('http://localhost/Sporify2/backend/entities/list_product.php', {
      credentials: 'include'
    })
    const json = await response.json()

    if (json.success) {
      products.value = json.data
    } else {
      console.error('Failed to load products:', json)
    }
  } catch (error) {
    console.error('Fetch error:', error)
  }
}

onMounted(() => {
  fetchProducts()
})
</script>

<style>
@keyframes zoomIn {
  0% { transform: scale(1); }
  50% { transform: scale(1.05); }
  100% { transform: scale(1); }
}
.animate-zoomIn {
  animation: zoomIn 10s ease-in-out infinite;
}
</style>
