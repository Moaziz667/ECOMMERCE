<template>
  <Navbar />

  <div class="container mx-auto p-6">
    <h1 class="text-4xl font-extrabold mb-8 text-green-800 tracking-tight">Orders & Products</h1>

    <div v-if="loading" class="text-gray-500 text-center text-lg py-10">Loading orders...</div>
    <div v-if="error" class="text-red-600 mb-6 font-semibold text-center">{{ error }}</div>

    <div v-if="orders.length" class="space-y-8">
      <div
        v-for="order in orders"
        :key="order.commande_id"
        class="bg-white rounded-lg border border-green-300 shadow-md hover:shadow-lg transition-shadow duration-300 p-6"
      >
        <h2 class="text-2xl font-semibold text-green-700 mb-3">
          Order #{{ order.commande_id }} &mdash; 
          <time :datetime="order.date" class="text-gray-600 font-medium">
            {{ new Date(order.date).toLocaleDateString() }}
          </time>
        </h2>
        <p class="mb-1 text-sm text-green-900">
          Status: <span class="capitalize font-semibold">{{ order.status }}</span>
        </p>
        <p class="mb-5 text-lg font-bold text-green-800">
          Total Price: €{{ order.prix_total.toFixed(2) }}
        </p>

        <h3 class="font-bold text-lg mb-4 border-b border-green-200 pb-1">Products</h3>
        <ul class="space-y-4">
          <li
            v-for="prod in order.products"
            :key="prod.product_id"
            class="flex gap-4 items-center bg-green-50 rounded-md p-3 shadow-sm hover:bg-green-100 transition-colors duration-200"
          >
            <img
              :src="prod.image_url || 'https://via.placeholder.com/60?text=No+Image'"
              alt="Product Image"
              class="w-16 h-16 object-cover rounded-md border border-green-300"
            />
            <div class="flex-1">
              <div class="font-semibold text-green-900 text-lg">{{ prod.libelle }}</div>
              <div class="text-sm text-green-700 mb-1">{{ prod.description }}</div>
              <div class="text-sm text-green-800">
                Quantity: <span class="font-semibold">{{ prod.quantity }}</span> &mdash; 
                Price: <span class="font-semibold">€{{ prod.prix.toFixed(2) }}</span>
              </div>
            </div>
          </li>
        </ul>

        <!-- Status update controls -->
        <div class="mt-4 flex items-center gap-4">
          <label :for="'status-' + order.commande_id" class="font-semibold text-green-700">Change Status:</label>
          
          <select
            :id="'status-' + order.commande_id"
            v-model="order.newStatus"
            class="border border-green-400 rounded px-3 py-1 focus:outline-none focus:ring-2 focus:ring-green-500"
          >
            <option value="pending">Pending</option>
            <option value="shipped">Shipped</option>
            <option value="delivered">Delivered</option>
            <option value="cancelled">Cancelled</option>
          </select>

          <button
            @click="updateStatus(order)"
            :disabled="order.updating"
            class="bg-green-600 hover:bg-green-700 disabled:bg-green-300 text-white font-semibold rounded px-4 py-1 transition"
          >
            {{ order.updating ? 'Updating...' : 'Update' }}
          </button>

          <span v-if="order.updateMessage" :class="order.updateSuccess ? 'text-green-700' : 'text-red-600'">
            {{ order.updateMessage }}
          </span>
        </div>
      </div>
    </div>

    <div v-else class="text-gray-600 text-center mt-16 text-lg">No orders found.</div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import Navbar from '../components/Navbar.vue'

const orders = ref([])
const loading = ref(true)
const error = ref(null)

function initOrdersWithStatus(ordersArray) {
  ordersArray.forEach(order => {
    order.newStatus = order.status
    order.updating = false
    order.updateMessage = ''
    order.updateSuccess = false
  })
  return ordersArray
}

async function fetchOrders() {
  loading.value = true
  error.value = null

  try {
    const response = await fetch('http://localhost/Sporify2/backend/entities/listOrders.php', {
      credentials: 'include',
    })
    const data = await response.json()

    if (data.success) {
      orders.value = initOrdersWithStatus(data.orders)
    } else {
      error.value = data.message || 'Failed to load orders.'
    }
  } catch (err) {
    error.value = 'Error fetching orders: ' + err.message
  } finally {
    loading.value = false
  }
}

async function updateStatus(order) {
  if (order.newStatus === order.status) {
    order.updateMessage = 'Status unchanged.'
    order.updateSuccess = false
    return
  }

  order.updating = true
  order.updateMessage = ''

  try {
    const response = await fetch('http://localhost/Sporify2/backend/entities/updateOrders.php', {
      method: 'POST',
      credentials: 'include',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        commande_id: order.commande_id,
        status: order.newStatus,
      }),
    })

    const data = await response.json()

    if (data.success) {
      order.status = order.newStatus
      order.updateMessage = 'Status updated successfully.'
      order.updateSuccess = true
    } else {
      order.updateMessage = data.message || 'Failed to update status.'
      order.updateSuccess = false
    }
  } catch (err) {
    order.updateMessage = 'Error updating status: ' + err.message
    order.updateSuccess = false
  } finally {
    order.updating = false
  }
}

onMounted(() => {
  fetchOrders()
})
</script>

<style scoped>
/* Add any custom styles here if needed */
</style>
