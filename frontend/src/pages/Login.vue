<template>
  <div class="min-h-screen bg-gray-100 flex flex-col font-poppins">
    <Navbar />

    <main class="flex-grow flex items-center justify-center px-4">
      <div class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full animate-fadeIn">
        <h2 class="text-3xl font-semibold text-gray-800 mb-6 text-center">Login to Sportify</h2>

        <form @submit.prevent="onSubmit" class="space-y-5">
          <!-- Email -->
          <div>
            <input
              v-model="email"
              type="email"
              placeholder="Email"
              :class="emailError ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-green-500'"
              class="w-full px-4 py-3 border rounded-md focus:outline-none transition"
              @input="emailError = ''"
            />
            <p v-if="emailError" class="text-red-500 text-sm mt-1">{{ emailError }}</p>
          </div>

          <!-- Password -->
          <div>
            <input
              v-model="password"
              type="password"
              placeholder="Password"
              :class="passwordError ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-green-500'"
              class="w-full px-4 py-3 border rounded-md focus:outline-none transition"
              @input="passwordError = ''"
            />
            <p v-if="passwordError" class="text-red-500 text-sm mt-1">{{ passwordError }}</p>
          </div>

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

        <!-- Inline login and server errors -->
        <p v-if="loginError" class="mt-4 text-center text-red-600 font-semibold">{{ loginError }}</p>
        <p v-if="serverError" class="mt-2 text-center text-red-600 font-semibold">{{ serverError }}</p>
      </div>
    </main>
  </div>
</template>
<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useStore } from 'vuex'
import Navbar from '../components/Navbar.vue'

const email = ref('')
const password = ref('')
const emailError = ref('')
const passwordError = ref('')
const loginError = ref('')
const serverError = ref('')

const router = useRouter()
const store = useStore()

function validateEmail(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  return re.test(email)
}

async function onSubmit() {
  emailError.value = ''
  passwordError.value = ''
  loginError.value = ''
  serverError.value = ''

  if (!email.value) {
    emailError.value = 'Email is required.'
  } else if (!validateEmail(email.value)) {
    emailError.value = 'Please enter a valid email.'
  }

  if (!password.value) {
    passwordError.value = 'Password is required.'
  }

  if (emailError.value || passwordError.value) return

  try {
    const response = await fetch('http://localhost/Sporify2/backend/login.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      credentials: 'include',
      body: JSON.stringify({
        login: email.value,
        pwd: password.value,
      }),
    })

    const data = await response.json()

    if (response.ok && data.status === 'success') {
      const userRole = data.session?.role || 'user'

      store.commit('setUserRole', userRole)
      store.commit('setLoggedIn', true)
      store.commit('setUserEmail', email.value)
      store.commit('setUserid', data.session.user_id || null)

      if (userRole === 'admin') {
        router.push('/admin')
      } else {
        router.push('/')
      }
    } else {
      loginError.value = data.message || 'Login failed. Please check your credentials.'
    }
  } catch (error) {
    serverError.value = `Server error: ${error.message}`
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
