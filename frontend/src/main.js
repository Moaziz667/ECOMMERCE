import { createApp } from 'vue'
import App from './App.vue'
import './assets/style.css'

import { createRouter, createWebHistory } from 'vue-router'
import Home from './pages/Home.vue'
import Register from './pages/Register.vue'
import Login from './pages/Login.vue'
import Admin from './pages/Admin.vue'
import store from './store'  // your Vuex store
import User from './pages/User.vue'
import Panier from './pages/Panier.vue'
import Profile from './pages/Profile.vue'
const routes = [
  { path: '/', component: Home },
  { path: '/register', component: Register },
  { path: '/login', component: Login },
  { path: '/admin', component: Admin },
    {path:'/user',component:User},
    {path:'/panier',component:Panier},
    {path:'/profile',component:Profile}
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

createApp(App)
  .use(store)    // <-- Add this line to use Vuex store
  .use(router)
  .mount('#app')
