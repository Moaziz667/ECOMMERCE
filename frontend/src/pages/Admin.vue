<template>
  <Navbar />

  <main class="max-w-6xl mx-auto mt-10 p-6 bg-white rounded-2xl shadow-lg border border-green-300">
    <!-- Add New Product Section -->
    <section class="mb-10">
      <h2 class="text-3xl font-extrabold text-green-700 mb-6 text-center">Add New Product</h2>

      <form @submit.prevent="submitProduct" class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
          <label class="block text-green-800 font-semibold mb-2" for="libelle">Libelle</label>
          <input
            id="libelle"
            v-model="product.libelle"
            type="text"
            class="w-full border border-green-400 p-3 rounded focus:ring-2 focus:ring-green-500"
            required
          />
        </div>

        <div>
          <label class="block text-green-800 font-semibold mb-2" for="description">Description</label>
          <input
            id="description"
            v-model="product.description"
            type="text"
            class="w-full border border-green-400 p-3 rounded focus:ring-2 focus:ring-green-500"
          />
        </div>

        <div>
          <label class="block text-green-800 font-semibold mb-2" for="prix">Prix (€)</label>
          <input
            id="prix"
            v-model.number="product.prix"
            type="number"
            class="w-full border border-green-400 p-3 rounded focus:ring-2 focus:ring-green-500"
            required
            min="0"
            step="0.01"
          />
        </div>

        <div>
          <label class="block text-green-800 font-semibold mb-2" for="quantity">Quantity</label>
          <input
            id="quantity"
            v-model.number="product.quantity"
            type="number"
            class="w-full border border-green-400 p-3 rounded focus:ring-2 focus:ring-green-500"
            required
            min="0"
          />
        </div>

        <div>
          <label class="block text-green-800 font-semibold mb-2" for="category">Category</label>
          <input
            id="category"
            v-model="product.category"
            type="text"
            class="w-full border border-green-400 p-3 rounded focus:ring-2 focus:ring-green-500"
            required
          />
        </div>

        <div>
          <label class="block text-green-800 font-semibold mb-2" for="image_url">Image URL</label>
          <input
            id="image_url"
            v-model="product.image_url"
            type="url"
            class="w-full border border-green-400 p-3 rounded focus:ring-2 focus:ring-green-500"
            placeholder="https://example.com/image.jpg"
          />
        </div>

        <!-- Buttons Row -->
        <div class="sm:col-span-2 flex justify-between items-center gap-4 mt-4">
          <button
            type="submit"
            class="flex-grow bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded transition duration-300"
          >
            Add Product
          </button>

          <button
            @click.prevent="goToOrders"
            class="flex-grow bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded transition duration-300"
          >
            View Orders
          </button>
        </div>
      </form>

      <!-- Feedback Messages -->
      <p v-if="message" class="mt-6 text-green-700 font-semibold text-center">{{ message }}</p>
      <p v-if="error" class="mt-6 text-red-600 font-semibold text-center">{{ error }}</p>
    </section>

    <!-- Product List Section -->
    <section>
      <h2 class="text-2xl font-extrabold text-green-700 mb-6">Product List</h2>

      <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
        <article
          v-for="p in products"
          :key="p.product_id"
          class="bg-white border border-green-300 rounded-lg shadow-md p-6 flex flex-col"
        >
          <img
            :src="p.image_url || 'https://via.placeholder.com/300x160?text=No+Image'"
            alt="Product Image"
            class="h-40 w-full object-cover rounded mb-4"
          />
          <h3 class="text-lg font-bold text-green-800 mb-1">{{ p.libelle }}</h3>
          <p class="text-gray-700 mb-2 flex-grow">{{ p.description || 'No description provided.' }}</p>
          <p class="text-green-600 font-semibold mb-1">€{{ p.prix.toFixed(2) }}</p>
          <p class="text-sm text-gray-500 mb-4">Qty: {{ p.quantity }} | Category: {{ p.category }}</p>

          <!-- Action Buttons -->
          <div class="flex gap-3">
            <button
              @click="startEdit(p)"
              class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 rounded transition"
            >
              Edit
            </button>
            <button
              @click="deleteProduct(p.product_id)"
              class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-2 rounded transition"
            >
              Delete
            </button>
          </div>
        </article>
      </div>
    </section>

    <!-- Edit Product Modal -->
    <div
      v-if="editing"
      class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 px-4"
      @click.self="cancelEdit"
    >
      <section class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full">
        <h3 class="text-2xl font-extrabold mb-6 text-green-700 text-center">Edit Product</h3>

        <form @submit.prevent="submitEdit" class="space-y-4">
          <input type="hidden" v-model="editProduct.product_id" />

          <div>
            <label class="block text-green-800 font-semibold mb-1" for="editLibelle">Libelle</label>
            <input
              id="editLibelle"
              v-model="editProduct.libelle"
              type="text"
              class="w-full border border-green-400 p-3 rounded focus:ring-2 focus:ring-green-500"
              required
            />
          </div>

          <div>
            <label class="block text-green-800 font-semibold mb-1" for="editDescription">Description</label>
            <input
              id="editDescription"
              v-model="editProduct.description"
              type="text"
              class="w-full border border-green-400 p-3 rounded focus:ring-2 focus:ring-green-500"
            />
          </div>

          <div>
            <label class="block text-green-800 font-semibold mb-1" for="editPrix">Prix (€)</label>
            <input
              id="editPrix"
              v-model.number="editProduct.prix"
              type="number"
              class="w-full border border-green-400 p-3 rounded focus:ring-2 focus:ring-green-500"
              required
              min="0"
              step="0.01"
            />
          </div>

          <div>
            <label class="block text-green-800 font-semibold mb-1" for="editQuantity">Quantity</label>
            <input
              id="editQuantity"
              v-model.number="editProduct.quantity"
              type="number"
              class="w-full border border-green-400 p-3 rounded focus:ring-2 focus:ring-green-500"
              required
              min="0"
            />
          </div>

          <div>
            <label class="block text-green-800 font-semibold mb-1" for="editCategory">Category</label>
            <input
              id="editCategory"
              v-model="editProduct.category"
              type="text"
              class="w-full border border-green-400 p-3 rounded focus:ring-2 focus:ring-green-500"
              required
            />
          </div>

          <div>
            <label class="block text-green-800 font-semibold mb-1" for="editImageUrl">Image URL</label>
            <input
              id="editImageUrl"
              v-model="editProduct.image_url"
              type="url"
              class="w-full border border-green-400 p-3 rounded focus:ring-2 focus:ring-green-500"
              placeholder="https://example.com/image.jpg"
            />
          </div>

          <div class="flex justify-end gap-4 mt-6">
            <button
              type="button"
              @click="cancelEdit"
              class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-6 rounded transition"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded transition"
            >
              Save
            </button>
          </div>
        </form>
      </section>
    </div>
  </main>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import Navbar from '../components/Navbar.vue'
import { useRouter } from 'vue-router'

const router = useRouter()

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

// Fetch products list
const fetchProducts = async () => {
  try {
    const res = await fetch('http://localhost/Sporify2/backend/entities/list_product.php', {
      credentials: 'include'
    })
    const data = await res.json()
    if (data.success) {
      products.value = data.data
    } else {
      error.value = 'Failed to load products.'
    }
  } catch (err) {
    error.value = 'Network error: ' + err.message
  }
}

// Add new product
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

// Delete product
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

// Start editing a product
const startEdit = (p) => {
  editProduct.value = { ...p }
  editing.value = true
}

// Cancel editing
const cancelEdit = () => {
  editProduct.value = null
  editing.value = false
}

// Submit edited product
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

// Navigate to orders page
const goToOrders = () => {
  router.push('/orders') // Adjust this route path as needed
}

onMounted(() => {
  fetchProducts()
})
</script>
