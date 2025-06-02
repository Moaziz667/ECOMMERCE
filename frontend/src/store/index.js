import { createStore } from 'vuex'

const store = createStore({
  state() {
    return {
      isLoggedIn: false,
      userRole: null,
      userEmail: null,
      username: null,  // add username here
    }
  },
  mutations: {
    setLoggedIn(state, status) {
      state.isLoggedIn = status
    },
    setUserRole(state, role) {
      state.userRole = role
    },
    setUserEmail(state, email) {
      state.userEmail = email
    },
    setUsername(state, username) {
      state.username = username
    },
    logout(state) {
      state.isLoggedIn = false
      state.userRole = null
      state.userEmail = null
      state.username = null
    },
  },
})

export default store
