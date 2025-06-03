<template>
  <div>
    <Navbar />

    <div
      class="max-w-xl mx-auto mt-10 p-6 bg-white shadow-md rounded-md"
      style="font-family: 'Poppins', sans-serif"
    >
      <h2 class="text-2xl font-bold mb-4 text-center">Account Information</h2>

      <div v-if="user">
        <p class="mb-2"><strong>Username:</strong> {{ user.username }}</p>
        <p class="mb-2"><strong>Email:</strong> {{ user.email }}</p>
        <p class="mb-2"><strong>Phone:</strong> {{ user.phone }}</p>
        <p class="mb-2"><strong>Last Name:</strong> {{ user.last_name }}</p>
        <p class="mb-2"><strong>User ID:</strong> {{ user.user_id }}</p>
      </div>

      <div v-else class="text-gray-500 text-center">
        Loading user information...
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import Navbar from '../components/Navbar.vue'

const user = ref(null)

onMounted(async () => {
  try {
    const response = await fetch('http://localhost/Sporify2/backend/entities/getUser.php', {
      method: 'GET',
      credentials: 'include', // Important for sending cookies/session
      headers: {
        'Content-Type': 'application/json'
      }
    })

    const data = await response.json()

    if (data.success) {
      user.value = data.user
    } else {
      console.error('Failed to load user:', data.message)
    }
  } catch (error) {
    console.error('Error fetching user info:', error)
  }
})
</script>
