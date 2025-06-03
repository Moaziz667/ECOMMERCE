<template>
  <Navbar />
  <div class="max-w-6xl mx-auto px-4 py-10" style="font-family: 'Poppins', sans-serif;">
    <h2 class="text-3xl font-semibold mb-6">Your Cart</h2>

    <div v-if="cartItems.length === 0" class="text-center text-gray-600 text-xl">
      Your cart is empty.
    </div>

    <div v-else class="space-y-6">
      <div
        v-for="item in cartItems"
        :key="item.product_id"
        class="flex flex-col sm:flex-row items-center justify-between bg-white shadow-md rounded-lg p-4"
      >
        <div class="flex items-center space-x-4">
          <img :src="item.image_url" class="w-24 h-24 object-cover rounded-md" />
          <div>
            <h3 class="text-lg font-bold">{{ item.libelle }}</h3>
            <p class="text-gray-600 text-sm">{{ item.description }}</p>
            <p class="text-sm text-gray-500">Category: {{ item.category }}</p>
            <p class="text-sm text-gray-500">Available: {{ item.quantity }}</p>
            <p class="text-green-600 font-bold mt-1">${{ item.prix }}</p>
          </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center gap-4 mt-4 sm:mt-0">
          <input
            type="number"
            class="w-20 text-center border rounded px-2 py-1"
            v-model.number="item.quantity"
            min="1"
            @change="updateItem(item.product_id, item.quantity)"
          />
          <button
            class="text-red-600 hover:underline"
            @click="removeItem(item.product_id)"
          >
            Remove
          </button>
        </div>
      </div>

      <div class="text-right text-xl font-semibold mt-6">
        Total: ${{ cartTotal.toFixed(2) }}
      </div>

      <div class="text-right mt-4">
        <button
          class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition"
          @click="checkout"
        >
          Checkout
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useStore } from 'vuex'
import Navbar from '../components/Navbar.vue'
import Swal from 'sweetalert2'

const store = useStore()

const cartItems = computed(() => store.getters.cartItems)
const cartTotal = computed(() => store.getters.cartTotal)
const userId = computed(() => store.state.userId) // Ensure userId exists in Vuex state

function updateItem(productId, quantity) {
  if (quantity < 1) return
  store.commit('updateQuantity', { productId, quantity })
}

function removeItem(productId) {
  store.commit('removeFromCart', productId)
}

async function checkout() {
  const payload = {
    user_id: userId.value,
    products: cartItems.value.map(item => ({
      product_id: item.product_id,
      quantity: item.quantity,
    })),
  }

  try {
    const response = await fetch('http://localhost/Sporify2/backend/command.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload),
    })

    const data = await response.json()

    if (data.success) {
      await Swal.fire({
        icon: 'success',
        title: 'Order placed!',
        text: `Commande ID: ${data.commande_id}`,
        timer: 3000,
        showConfirmButton: false,
      })
      store.commit('clearCart')
    } else {
      await Swal.fire({
        icon: 'error',
        title: 'Error',
        text: data.message,
      })
    }
  } catch (error) {
    console.error('Checkout failed:', error)
    await Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'An error occurred during checkout.',
    })
  }
}
</script>
