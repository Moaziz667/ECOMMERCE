import { createApp } from 'vue'
import App from './App.vue'
import './assets/style.css'

import { createRouter, createWebHistory } from 'vue-router'
import Home from './pages/Home.vue'
import Register from './pages/Register.vue'
import Login from './pages/Login.vue'
import Admin from './pages/Admin.vue'
import store from './store'  // your Vuex store

const routes = [
  { path: '/', component: Home },
  { path: '/register', component: Register },
  { path: '/login', component: Login },
  { path: '/admin', component: Admin }
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

createApp(App)
  .use(store)    // <-- Add this line to use Vuex store
  .use(router)
  .mount('#app')
