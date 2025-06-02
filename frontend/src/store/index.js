import { createStore } from 'vuex'

const store = createStore({
  state() {
    return {
      isLoggedIn: localStorage.getItem('isLoggedIn') === 'true' || false,
      userRole: localStorage.getItem('userRole') || null,
      userEmail: localStorage.getItem('userEmail') || null,
      username: localStorage.getItem('username') || null,
    }
  },
  mutations: {
    setLoggedIn(state, status) {
      state.isLoggedIn = status
      localStorage.setItem('isLoggedIn', status)
    },
    setUserRole(state, role) {
      state.userRole = role
      if (role) localStorage.setItem('userRole', role)
      else localStorage.removeItem('userRole')
    },
    setUserEmail(state, email) {
      state.userEmail = email
      if (email) localStorage.setItem('userEmail', email)
      else localStorage.removeItem('userEmail')
    },
    setUsername(state, username) {
      state.username = username
      if (username) localStorage.setItem('username', username)
      else localStorage.removeItem('username')
    },
    logout(state) {
      state.isLoggedIn = false
      state.userRole = null
      state.userEmail = null
      state.username = null
      localStorage.removeItem('isLoggedIn')
      localStorage.removeItem('userRole')
      localStorage.removeItem('userEmail')
      localStorage.removeItem('username')
    },
  },
})

export default store
