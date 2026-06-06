<template>
  <div class="min-h-screen flex flex-col bg-[#FDF8F4]">
    <!-- Navbar -->
    <header class="fixed w-full top-0 z-40 nav-blur transition-all duration-300 border-b border-white/10">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
          <!-- Logo -->
          <div class="flex-shrink-0 flex items-center">
            <router-link :to="{ name: 'home' }" class="flex items-center gap-2">
              <span class="font-playfair text-2xl font-bold text-white tracking-wide">
                <span class="text-[#D4A574]">Paladín</span> Sucre
              </span>
            </router-link>
          </div>

          <!-- Desktop Menu -->
          <nav class="hidden md:flex space-x-8">
            <router-link :to="{ name: 'home' }" class="text-gray-300 hover:text-white font-medium transition-colors">Inicio</router-link>
            <router-link :to="{ name: 'catalog' }" class="text-gray-300 hover:text-white font-medium transition-colors">Catálogo</router-link>
            <a href="/#nosotros" class="text-gray-300 hover:text-white font-medium transition-colors">Nosotros</a>
            <a href="/#contacto" class="text-gray-300 hover:text-white font-medium transition-colors">Contacto</a>
          </nav>

          <!-- Desktop Right (Auth/Cart) -->
          <div class="hidden md:flex items-center gap-4">
            <!-- Cart Icon -->
            <router-link v-if="isClient || !isAuthenticated" :to="{ name: 'client.cart' }" class="relative p-2 text-gray-300 hover:text-white transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
              <span v-if="totalItems > 0" class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-[#B91C1C] rounded-full">{{ totalItems }}</span>
            </router-link>

            <!-- Auth Buttons/Menu -->
            <template v-if="isLoading">
              <div class="w-20 h-8 bg-white/20 rounded animate-pulse"></div>
            </template>
            <template v-else-if="!isAuthenticated">
              <router-link :to="{ name: 'login' }" class="text-gray-300 hover:text-white font-medium">Ingresar</router-link>
              <router-link :to="{ name: 'register' }" class="bg-[#B91C1C] hover:bg-[#8B1A1A] text-white px-4 py-2 rounded-md font-medium transition-colors shadow-lg">Registrarse</router-link>
            </template>
            <template v-else>
              <div class="relative group">
                <button class="flex items-center gap-2 text-gray-300 hover:text-white focus:outline-none">
                  <div class="w-8 h-8 rounded-full bg-[#D4A574] text-[#1a0a00] flex items-center justify-center font-bold">
                    {{ state.user.name.charAt(0).toUpperCase() }}
                  </div>
                  <span class="font-medium">{{ state.user.name.split(' ')[0] }}</span>
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden group-hover:block border border-gray-100">
                  <router-link v-if="isAdmin" :to="{ name: 'admin.dashboard' }" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Panel Administrador</router-link>
                  <router-link v-if="isClient" :to="{ name: 'client.dashboard' }" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Mi Cuenta</router-link>
                  <router-link v-if="isClient" :to="{ name: 'client.orders' }" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Mis Pedidos</router-link>
                  <button @click="handleLogout" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Cerrar Sesión</button>
                </div>
              </div>
            </template>
          </div>

          <!-- Mobile menu button -->
          <div class="md:hidden flex items-center gap-4">
             <router-link v-if="isClient || !isAuthenticated" :to="{ name: 'client.cart' }" class="relative p-2 text-gray-300">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
              <span v-if="totalItems > 0" class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-[#B91C1C] rounded-full">{{ totalItems }}</span>
            </router-link>
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-300 hover:text-white focus:outline-none">
              <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path v-if="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Mobile Menu -->
      <div v-if="mobileMenuOpen" class="md:hidden bg-[#1a0a00] border-t border-white/10">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
          <router-link :to="{ name: 'home' }" @click="mobileMenuOpen = false" class="block px-3 py-2 text-base font-medium text-white hover:bg-white/10 rounded-md">Inicio</router-link>
          <router-link :to="{ name: 'catalog' }" @click="mobileMenuOpen = false" class="block px-3 py-2 text-base font-medium text-white hover:bg-white/10 rounded-md">Catálogo</router-link>
          <a href="/#nosotros" @click="mobileMenuOpen = false" class="block px-3 py-2 text-base font-medium text-white hover:bg-white/10 rounded-md">Nosotros</a>
          <a href="/#contacto" @click="mobileMenuOpen = false" class="block px-3 py-2 text-base font-medium text-white hover:bg-white/10 rounded-md">Contacto</a>
          
          <div class="border-t border-white/10 pt-4 mt-2">
            <template v-if="!isAuthenticated && !isLoading">
              <router-link :to="{ name: 'login' }" @click="mobileMenuOpen = false" class="block px-3 py-2 text-base font-medium text-white hover:bg-white/10 rounded-md">Ingresar</router-link>
              <router-link :to="{ name: 'register' }" @click="mobileMenuOpen = false" class="block px-3 py-2 text-base font-medium text-[#D4A574] hover:bg-white/10 rounded-md">Registrarse</router-link>
            </template>
            <template v-else-if="isAuthenticated">
               <div class="px-3 py-2 text-sm text-gray-400">Sesión iniciada como {{ state.user.name }}</div>
               <router-link v-if="isAdmin" :to="{ name: 'admin.dashboard' }" @click="mobileMenuOpen = false" class="block px-3 py-2 text-base font-medium text-white hover:bg-white/10 rounded-md">Panel Administrador</router-link>
               <router-link v-if="isClient" :to="{ name: 'client.dashboard' }" @click="mobileMenuOpen = false" class="block px-3 py-2 text-base font-medium text-white hover:bg-white/10 rounded-md">Mi Cuenta</router-link>
               <button @click="handleLogout(); mobileMenuOpen = false" class="block w-full text-left px-3 py-2 text-base font-medium text-red-400 hover:bg-white/10 rounded-md">Cerrar Sesión</button>
            </template>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow pt-20">
      <router-view></router-view>
    </main>

    <!-- Footer -->
    <footer class="bg-[#1a0a00] text-gray-300 py-12 border-t-4 border-[#8B1A1A]">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div>
            <h3 class="font-playfair text-2xl font-bold text-white mb-4"><span class="text-[#D4A574]">Paladín</span> Sucre</h3>
            <p class="text-sm text-gray-400 mb-4">
              Embutidos artesanales de la más alta calidad, manteniendo la tradición y el sabor auténtico de Chuquisaca desde 2009.
            </p>
          </div>
          <div>
            <h4 class="text-white font-semibold mb-4 uppercase tracking-wider text-sm">Enlaces Rápidos</h4>
            <ul class="space-y-2 text-sm">
              <li><router-link :to="{ name: 'home' }" class="hover:text-[#D4A574] transition-colors">Inicio</router-link></li>
              <li><router-link :to="{ name: 'catalog' }" class="hover:text-[#D4A574] transition-colors">Catálogo de Productos</router-link></li>
              <li><a href="/#nosotros" class="hover:text-[#D4A574] transition-colors">Sobre Nosotros</a></li>
              <li v-if="!isAuthenticated"><router-link :to="{ name: 'register' }" class="hover:text-[#D4A574] transition-colors">Crear Cuenta</router-link></li>
            </ul>
          </div>
          <div>
            <h4 class="text-white font-semibold mb-4 uppercase tracking-wider text-sm">Contacto</h4>
            <ul class="space-y-2 text-sm text-gray-400">
              <li class="flex items-start gap-2">
                <svg class="w-5 h-5 text-[#D4A574] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Calle Bolívar #456, Sucre, Bolivia
              </li>
              <li class="flex items-center gap-2">
                <svg class="w-5 h-5 text-[#D4A574] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                +591 70000000
              </li>
              <li class="flex items-center gap-2">
                <svg class="w-5 h-5 text-[#D4A574] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                info@paladinsucre.com
              </li>
            </ul>
          </div>
        </div>
        <div class="mt-8 pt-8 border-t border-white/10 text-center text-xs text-gray-500">
          &copy; {{ new Date().getFullYear() }} Embutidos Paladín Sucre. Todos los derechos reservados.
        </div>
      </div>
    </footer>

    <!-- Social Plugins Float -->
    <SocialFloat />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuth } from '../stores/auth.js';
import { useCart } from '../stores/cart.js';
import { logout } from '../api/auth.js';
import SocialFloat from '../components/common/SocialFloat.vue';

const router = useRouter();
const { state, initAuth, clearUser, isAuthenticated, isAdmin, isClient, isLoading } = useAuth();
const { totalItems } = useCart();

const mobileMenuOpen = ref(false);

onMounted(() => {
    initAuth();
});

const handleLogout = async () => {
    try {
        await logout();
    } catch (error) {
        console.error('Logout error', error);
    } finally {
        clearUser();
        router.push({ name: 'home' });
    }
};
</script>
