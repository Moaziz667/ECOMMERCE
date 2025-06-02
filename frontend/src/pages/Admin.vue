<template>
  <Navbar />

  <div class="max-w-4xl mx-auto mt-10 p-6 bg-white rounded-2xl shadow-lg border border-green-300">
    <!-- Add Product Form -->
    <h2 class="text-2xl font-bold text-green-700 mb-6 text-center">Add New Product</h2>

    <form @submit.prevent="submitProduct">
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-green-800 font-medium mb-1">Libelle</label>
          <input
            v-model="product.libelle"
            type="text"
            class="w-full border border-green-400 p-2 rounded focus:ring-2 focus:ring-green-500"
            required
          />
        </div>
        <div>
          <label class="block text-green-800 font-medium mb-1">Description</label>
          <input
            v-model="product.description"
            type="text"
            class="w-full border border-green-400 p-2 rounded focus:ring-2 focus:ring-green-500"
          />
        </div>
        <div>
          <label class="block text-green-800 font-medium mb-1">Prix</label>
          <input
            v-model.number="product.prix"
            type="number"
            class="w-full border border-green-400 p-2 rounded focus:ring-2 focus:ring-green-500"
            required
          />
        </div>
        <div>
          <label class="block text-green-800 font-medium mb-1">Quantity</label>
          <input
            v-model.number="product.quantity"
            type="number"
            class="w-full border border-green-400 p-2 rounded focus:ring-2 focus:ring-green-500"
            required
          />
        </div>
        <div>
          <label class="block text-green-800 font-medium mb-1">Category</label>
          <input
            v-model="product.category"
            type="text"
            class="w-full border border-green-400 p-2 rounded focus:ring-2 focus:ring-green-500"
            required
          />
        </div>
        <div>
          <label class="block text-green-800 font-medium mb-1">Image URL</label>
          <input
            v-model="product.image_url"
            type="text"
            class="w-full border border-green-400 p-2 rounded focus:ring-2 focus:ring-green-500"
          />
        </div>
      </div>

      <button
        type="submit"
        class="mt-6 w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded transition duration-300"
      >
        Add Product
      </button>

      <p v-if="message" class="mt-4 text-green-700 font-medium text-center">{{ message }}</p>
      <p v-if="error" class="mt-4 text-red-600 font-medium text-center">{{ error }}</p>
    </form>
  </div>

  <!-- Product List -->
  <div class="max-w-6xl mx-auto mt-10 mb-3 px-6">
    <h2 class="text-xl font-bold text-green-700 mb-4">Product List</h2>
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
      <div
        v-for="p in products"
        :key="p.product_id"
        class="bg-white border border-green-300 rounded-lg shadow p-4 flex flex-col justify-between"
      >
        <img
          :src="p.image_url"
          alt="Product Image"
          class="h-40 w-full object-cover rounded mb-3"
        />
        <h3 class="text-lg font-semibold text-green-800">{{ p.libelle }}</h3>
        <p class="text-gray-600 text-sm mb-1">{{ p.description }}</p>
        <p class="text-green-600 font-bold">€{{ p.prix }}</p>
        <p class="text-sm text-gray-500">Qty: {{ p.quantity }} | Cat: {{ p.category }}</p>

        <!-- Update & Delete Buttons -->
        <div class="mt-4 flex gap-2">
          <button
            @click="startEdit(p)"
            class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-1 rounded"
          >
            Edit
          </button>
          <button
            @click="deleteProduct(p.product_id)"
            class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-1 rounded"
          >
            Delete
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Product Modal -->
  <div v-if="editing" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 w-96">
      <h3 class="text-xl font-bold mb-4 text-green-700">Edit Product</h3>

      <form @submit.prevent="submitEdit">
        <input type="hidden" v-model="editProduct.product_id" />

        <label class="block text-green-800 font-medium mb-1">Libelle</label>
        <input
          v-model="editProduct.libelle"
          type="text"
          class="w-full border border-green-400 p-2 rounded mb-3 focus:ring-2 focus:ring-green-500"
          required
        />

        <label class="block text-green-800 font-medium mb-1">Description</label>
        <input
          v-model="editProduct.description"
          type="text"
          class="w-full border border-green-400 p-2 rounded mb-3 focus:ring-2 focus:ring-green-500"
        />

        <label class="block text-green-800 font-medium mb-1">Prix</label>
        <input
          v-model.number="editProduct.prix"
          type="number"
          class="w-full border border-green-400 p-2 rounded mb-3 focus:ring-2 focus:ring-green-500"
          required
        />

        <label class="block text-green-800 font-medium mb-1">Quantity</label>
        <input
          v-model.number="editProduct.quantity"
          type="number"
          class="w-full border border-green-400 p-2 rounded mb-3 focus:ring-2 focus:ring-green-500"
          required
        />

        <label class="block text-green-800 font-medium mb-1">Category</label>
        <input
          v-model="editProduct.category"
          type="text"
          class="w-full border border-green-400 p-2 rounded mb-3 focus:ring-2 focus:ring-green-500"
          required
        />

        <label class="block text-green-800 font-medium mb-1">Image URL</label>
        <input
          v-model="editProduct.image_url"
          type="text"
          class="w-full border border-green-400 p-2 rounded mb-4 focus:ring-2 focus:ring-green-500"
        />

        <div class="flex justify-end gap-2">
          <button
            type="button"
            @click="cancelEdit"
            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded"
          >
            Cancel
          </button>
          <button
            type="submit"
            class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded"
          >
            Save
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import Navbar from '../components/Navbar.vue'

const product = ref({
  libelle: '',
  description: '',
  prix: 0.0,
  quantity: 0,
  category: '',
  image_url: ''
})

const editProduct = ref(null)
const editing = ref(false)

const message = ref('')
const error = ref('')
const products = ref([])

const fetchProducts = async () => {
  try {
    const res = await fetch('http://localhost/Sporify2/backend/entities/list_product.php', {
      credentials: 'include'
    })
    const data = await res.json()
    if (data.success) {
      products.value = data.data
      console.log('Products loaded:', products.value)
    } else {
      error.value = 'Failed to load products.'
    }
  } catch (err) {
    error.value = 'Network error: ' + err.message
  }
}

const submitProduct = async () => {
  message.value = ''
  error.value = ''

  try {
    const response = await fetch('http://localhost/Sporify2/backend/entities/add_product.php', {
      method: 'POST',
      credentials: 'include',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(product.value)
    })

    const result = await response.json()

    if (result.success) {
      message.value = result.message || 'Product added successfully!'
      product.value = {
        libelle: '',
        description: '',
        prix: 0.0,
        quantity: 0,
        category: '',
        image_url: ''
      }
      fetchProducts()
    } else {
      error.value = result.message || 'Something went wrong!'
    }
  } catch (err) {
    error.value = 'Network error: ' + err.message
  }
}

// Delete product method
const deleteProduct = async (id) => {
  if (!confirm('Are you sure you want to delete this product?')) return

  message.value = ''
  error.value = ''

  try {
    const response = await fetch('http://localhost/Sporify2/backend/entities/delete_product.php', {
      method: 'DELETE',
      credentials: 'include',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id })
    })

    const result = await response.json()

    if (result.success) {
      message.value = result.message || 'Product deleted successfully!'
      fetchProducts()
    } else {
      error.value = result.message || 'Failed to delete product.'
    }
  } catch (err) {
    error.value = 'Network error: ' + err.message
  }
}

// Start editing product
const startEdit = (p) => {
  editProduct.value = { ...p } // shallow copy
  editing.value = true
}

// Cancel editing
const cancelEdit = () => {
  editProduct.value = null
  editing.value = false
}

// Submit updated product
const submitEdit = async () => {
  message.value = ''
  error.value = ''

  try {
    const response = await fetch('http://localhost/Sporify2/backend/entities/modify_product.php', {
      method: 'PUT',
      credentials: 'include',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(editProduct.value)
    })

    const result = await response.json()

    if (result.success) {
      message.value = result.message || 'Product updated successfully!'
      editing.value = false
      editProduct.value = null
      fetchProducts()
    } else {
      error.value = result.message || 'Failed to update product.'
    }
  } catch (err) {
    error.value = 'Network error: ' + err.message
  }
}

onMounted(() => {
  fetchProducts()
})
</script>
