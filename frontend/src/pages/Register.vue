<template>
  <div class="min-h-screen bg-gray-100 flex flex-col font-poppins">
    <Navbar />

    <main class="flex-grow flex items-center justify-center px-4">
      <div class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full animate-fadeIn">
        <h2 class="text-3xl font-semibold text-gray-800 mb-6 text-center">Create Account</h2>

        <form @submit.prevent="onSubmit" class="space-y-5">
          <input 
          name="username"
            v-model="form.username"
            type="text"
            placeholder="Username"
            required
            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 transition"
          />

          <input
 name="last_name"            v-model="form.last_name"
            type="text"
            placeholder="Last Name"
            required
            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 transition"
          />

          <input
       name="phone"     v-model.number="form.phone"
            type="tel"
            placeholder="Phone Number"
            required
            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 transition"
          />

          <input name="email"
            v-model="form.email"
            type="email"
            placeholder="Email"
            required
            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 transition"
          />

          <input name="pwd"
            v-model="form.password"
            type="password"
            placeholder="Password"
            required
            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 transition"
          />

          
          <button
            type="submit"
            class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-md transition-colors duration-200"
          >
            Register
          </button>
        </form>

        <p class="mt-4 text-center text-gray-600">
          Already have an account?
          <router-link to="/login" class="text-green-600 hover:text-green-700 font-semibold transition">
            Log in
          </router-link>
        </p>
      </div>
    </main>
  </div>
</template>

<script setup>
import Navbar from '../components/Navbar.vue'
import { reactive } from 'vue'
import Swal from 'sweetalert2'

const form = reactive({
  username: '',
  last_name: '',
  phone: null,
  email: '',
  password: '',
  role: 'user',       // Default role
  panier_id: null,    // Optional
})

async function onSubmit() {
  // Simple validation
  if (!form.username || !form.last_name || !form.phone || !form.email || !form.password) {
    Swal.fire({
      icon: 'warning',
      title: 'Incomplete Form',
      text: 'Please fill in all required fields.',
    })
    return
  }

  try {
    const response = await fetch('http://localhost/Sporify2/backend/signup.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(form),
    })

    const result = await response.json()

    if (response.ok) {
      Swal.fire({
        icon: 'success',
        title: 'Registration Successful',
        text: 'Your account has been created!',
        timer: 2000,
        showConfirmButton: false
      })

      // Clear the form
      Object.keys(form).forEach(key => {
        form[key] = key === 'panier_id' || key === 'phone' ? null : (key === 'role' ? 'user' : '')
      })
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Registration Failed',
        text: result.message || 'An error occurred during registration.',
      })
    }
  } catch (error) {
    Swal.fire({
      icon: 'error',
      title: 'Server Error',
      text: error.message,
    })
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
