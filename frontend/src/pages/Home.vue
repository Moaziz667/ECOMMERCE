<template>
    <div style="font-family: 'Poppins', sans-serif;">

    <Navbar />

    <!-- HERO / BANNER with zoom animation -->
    <section
      class="relative bg-cover bg-center h-96 overflow-hidden"
      :style="{ backgroundImage: `url('https://4kwallpapers.com/images/wallpapers/cristiano-ronaldo-2560x1080-9595.jpg')` }"
    >
      <div class="absolute inset-0 bg-black bg-opacity-60"></div>
      <div
        class="relative z-10 flex flex-col justify-center items-center h-full text-white px-4 text-center max-w-4xl mx-auto animate-zoomIn"
      >
        <h1 class="text-4xl md:text-6xl font-bold mb-4">Welcome to Sportify</h1>
        <p class="mb-6 text-lg md:text-xl">
          Premium sportswear, footwear & equipment – trusted by athletes worldwide.
        </p>
        <button
          class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-md text-lg transition"
        >
          Browse Products
        </button>
      </div>
    </section>

    <!-- PRODUCTS GRID -->
    <div class="max-w-6xl mx-auto px-4 py-10">
      <h2 class="text-3xl font-semibold mb-6">Featured Products</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
        <SportCard
          v-for="product in products"
          :key="product.name"
          :name="product.name"
          :price="product.price"
          :image="product.image"
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
            <li>
              <a href="#" class="hover:text-green-400 transition">Home</a>
            </li>
            <li>
              <a href="#" class="hover:text-green-400 transition">Shop</a>
            </li>
            <li>
              <a href="#" class="hover:text-green-400 transition">Login</a>
            </li>
            <li>
              <a href="#" class="hover:text-green-400 transition">Register</a>
            </li>
          </ul>
        </div>
        <div>
          <h4 class="text-white text-xl font-bold mb-4">Contact</h4>
          <p>Email: support@sportify.com</p>
          <p>Phone: +1 800 555 1234</p>
        </div>
      </div>
      <div class="text-center text-gray-500 mt-8">
        &copy; 2025 Sportify. All rights reserved.
      </div>
    </footer>
  </div>
</template>

<script setup>
import Navbar from '../components/Navbar.vue'
import SportCard from '../components/SportCard.vue'

const products = [
  {
    name: 'Nike Air Zoom Pegasus',
    price: '119.99',
    image:
 'https://dnaplainfield.com/cdn/shop/files/1b7ac3d4-4753-4ae4-9a32-799467c4dd39.jpg?v=1723062906'  },
  {
    name: 'Adidas Match Ball',
    price: '39.99',
    image:
'https://images-cdn.ubuy.tn/63514bab08592e23cd18483c-adidas-uefa-champions-league-match-ball.jpg'  },
  {
    name: 'Puma Training Gloves',
    price: '19.99',
    image:
'https://underarmour.scene7.com/is/image/Underarmour/PS1377217-001_HF?rp=standard-0pad%7CpdpMainDesktop&scl=1&fmt=jpg&qlt=85&resMode=sharp2&cache=on%2Con&bgc=F0F0F0&wid=566&hei=708&size=566%2C708'  },
  {
    name: 'Under Armour Jersey',
    price: '29.99',
    image:
      'https://underarmour.scene7.com/is/image/Underarmour/V5-1361523-001_HTF?fmt=png-alpha',
  },
  {
    name: 'Manduka Yoga Mat',
    price: '89.99',
    image:
      'https://cdn.shopify.com/s/files/1/0563/1145/0915/products/prolite-midnight-main.png',
  },
  {
    name: 'Reusch Goalkeeper Gloves',
    price: '54.99',
    image:
      'https://cdn11.bigcommerce.com/s-x49po/images/stencil/1280x1280/products/1449/4369/5172735_7700_PROD__54034.1669658809.png',
  },
]
</script>

<style>
@keyframes zoomIn {
  0% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.05);
  }
  100% {
    transform: scale(1);
  }
}

.animate-zoomIn {
  animation: zoomIn 10s ease-in-out infinite;
}
</style>
