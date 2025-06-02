<template>
  <form @submit.prevent="addProduct" class="max-w-md mx-auto p-4 bg-white shadow rounded">
    <input v-model="form.libelle" placeholder="Libelle" required class="input" />
    <input v-model="form.description" placeholder="Description" required class="input" />
    <input v-model.number="form.prix" type="number" placeholder="Prix" required class="input" />
    <input v-model.number="form.quantity" type="number" placeholder="Quantity" required class="input" />
    <input v-model="form.category" placeholder="Category" required class="input" />
    <input v-model="form.image_url" placeholder="Image URL" class="input" />

    <button type="submit" class="btn btn-green mt-4">Add Product</button>
  </form>
</template>
<script setup>
import { ref } from 'vue'

const form = ref({
  libelle: '',
  description: '',
  prix: 0,
  quantity: 0,
  category: '',
  image_url: ''
})

const API_URL = 'http://localhost/Sporify2/backend/product.php'

async function addProduct() {
  try {
    const res = await fetch(API_URL, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      credentials: 'include', // if your PHP session uses cookies
      body: JSON.stringify(form.value),
    })

    const data = await res.json()
    if (data.success) {
      alert('Product added successfully!')
      // Clear form
      form.value = {
        libelle: '',
        description: '',
        prix: 0,
        quantity: 0,
        category: '',
        image_url: ''
      }
      // Optionally fetch products again or update UI
    } else {
      alert('Failed to add product: ' + data.message)
    }
  } catch (error) {
    alert('Error: ' + error.message)
  }
}
</script>
