<template>
  <nav class="bg-green-500 text-white shadow-md" style="font-family: 'Poppins', sans-serif">
    <div class="container mx-auto flex justify-between items-center py-4 px-6">
      <div class="text-2xl font-bold tracking-wide">Sportify</div>

      <div class="space-x-8 text-lg font-medium flex items-center">
        <router-link to="/" class="hover:text-green-300 transition-colors duration-200">Home</router-link>

        <template v-if="!isLoggedIn">
          <router-link to="/register" class="hover:text-green-300 transition-colors duration-200">Register</router-link>
          <router-link to="/login" class="hover:text-green-300 transition-colors duration-200">Login</router-link>
        </template>

        <template v-else>
          <!-- 🛒 Cart Icon -->
          <div class="relative cursor-pointer" @click="goToCart">
            <img src="https://cdn-icons-png.flaticon.com/512/1170/1170678.png" alt="Cart" class="w-7 h-7" />
            <span
              v-if="cartCount > 0"
              class="absolute -top-2 -right-2 bg-red-600 text-white text-xs font-bold rounded-full px-1.5 py-0.5"
            >
              {{ cartCount }}
            </span>
          </div>

          <span class="ml-4">Hello, {{ username }}</span>
          <button
            @click="logout"
            class="ml-4 bg-green-700 hover:bg-green-800 text-white px-3 py-1 rounded transition-colors duration-200"
          >
            Logout
          </button>
        </template>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { computed } from 'vue'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'

const store = useStore()
const router = useRouter()

const isLoggedIn = computed(() => store.state.isLoggedIn)
const username = computed(() => store.state.username || store.state.userEmail || 'User')
const cartCount = computed(() => store.getters.cartItemCount)

function logout() {
  store.commit('logout')
  router.push('/login')
}

function goToCart() {
  router.push('/panier')
}
</script>
