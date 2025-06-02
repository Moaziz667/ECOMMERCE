<template>
  <div class="min-h-screen bg-gray-100 flex flex-col font-poppins">
    <Navbar />

    <main class="flex-grow flex items-center justify-center px-4">
      <div class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full animate-fadeIn">
        <h2 class="text-3xl font-semibold text-gray-800 mb-6 text-center">Login to Sportify</h2>

        <form @submit.prevent="onSubmit" class="space-y-5">
          <input 
          name="login"
            v-model="email"
            type="email"
            placeholder="Email"
            required
            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 transition"
          />
          <input 
          name="pwd"
            v-model="password"
            type="password"
            placeholder="Password"
            required
            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 transition"
          />
          <button
            type="submit"
            class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-md transition-colors duration-200"
          >
            Login
          </button>
        </form>

        <p class="mt-4 text-center text-gray-600">
          Don't have an account?
          <router-link to="/register" class="text-green-600 hover:text-green-700 font-semibold transition">
            Register here
          </router-link>
        </p>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useStore } from 'vuex'       // Import Vuex store composable
import Navbar from '../components/Navbar.vue'

const email = ref('')
const password = ref('')
const router = useRouter()
const store = useStore()             // Get Vuex store instance

async function onSubmit() {
  try {
    const response = await fetch('http://localhost/Sporify2/backend/login.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      credentials: 'include',
      body: JSON.stringify({
        login: email.value,
        pwd: password.value
      })
    })

    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`)
    }

    const data = await response.json()

    if (data.status === 'success') {
      // Assuming backend sends something like data.role
      const userRole = data.role || 'user'  // fallback role if none provided

      // Commit login info to Vuex store
      store.commit('setUserRole', userRole)
      store.commit('setLoggedIn', true)
      store.commit('setUserEmail', email.value)

      // Redirect to admin or other page
      router.push('/admin')
    } else {
      alert(`❌ ${data.message}`)
    }
  } catch (error) {
    alert(`🚫 Server error: ${error.message}`)
  }
}
</script>



<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');

.font-poppins {
  font-family: 'Poppins', sans-serif;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(15px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fadeIn {
  animation: fadeIn 0.7s ease forwards;
}
</style>
