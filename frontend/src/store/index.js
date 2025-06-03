import { createStore } from 'vuex'

const store = createStore({
  state() {
    return {
      isLoggedIn: localStorage.getItem('isLoggedIn') === 'true',
      userRole: localStorage.getItem('userRole') || null,
      userEmail: localStorage.getItem('userEmail') || null,
      username: localStorage.getItem('username') || null,
      cart: JSON.parse(localStorage.getItem('cart')) || [],
    }
  },

  mutations: {
    // ==== Auth ====
    setLoggedIn(state, status) {
      state.isLoggedIn = status
      localStorage.setItem('isLoggedIn', status)
    },
    setUserRole(state, role) {
      state.userRole = role
      role
        ? localStorage.setItem('userRole', role)
        : localStorage.removeItem('userRole')
    },
    setUserEmail(state, email) {
      state.userEmail = email
      email
        ? localStorage.setItem('userEmail', email)
        : localStorage.removeItem('userEmail')
    },
    setUsername(state, username) {
      state.username = username
      username
        ? localStorage.setItem('username', username)
        : localStorage.removeItem('username')
    },
    logout(state) {
      state.isLoggedIn = false
      state.userRole = null
      state.userEmail = null
      state.username = null
      state.cart = []

      localStorage.removeItem('isLoggedIn')
      localStorage.removeItem('userRole')
      localStorage.removeItem('userEmail')
      localStorage.removeItem('username')
    },

    // ==== Cart ====
    addToCart(state, product) {
      const existing = state.cart.find(p => p.product_id === product.product_id)
      if (existing) {
        existing.quantity += 1
      } else {
        state.cart.push({ ...product, quantity: 1 })
      }
      localStorage.setItem('cart', JSON.stringify(state.cart))
    },

    updateQuantity(state, { productId, quantity }) {
      const item = state.cart.find(p => p.product_id === productId)
      if (item) {
        item.quantity = quantity > 0 ? quantity : 1
      }
      localStorage.setItem('cart', JSON.stringify(state.cart))
    },

    removeFromCart(state, productId) {
      state.cart = state.cart.filter(p => p.product_id !== productId)
      localStorage.setItem('cart', JSON.stringify(state.cart))
    },

    clearCart(state) {
      state.cart = []
      localStorage.removeItem('cart')
    }
  },

  getters: {
    cartItems: state => state.cart,
    cartItemCount: state => state.cart.reduce((total, item) => total + item.quantity, 0),
    cartTotal: state => state.cart.reduce((total, item) => total + item.prix * item.quantity, 0),
  },
})

export default store
