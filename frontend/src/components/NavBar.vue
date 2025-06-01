<template>
  <nav
    class="bg-green-500 text-white shadow-md"
    style="font-family: 'Poppins', sans-serif"
  >
    <div class="container mx-auto flex justify-between items-center py-4 px-6">
      <div class="text-2xl font-bold tracking-wide">Sportify</div>
      <div class="space-x-8 text-lg font-medium">
        <router-link
          to="/"
          class="hover:text-green-300 transition-colors duration-200"
          >Home</router-link
        >
        <router-link
          to="/register"
          class="hover:text-green-300 transition-colors duration-200"
          >Register</router-link
        >
        <router-link
          to="/login"
          class="hover:text-green-300 transition-colors duration-200"
          >Login</router-link
        >
      </div>
    </div>
  </nav>
</template>
