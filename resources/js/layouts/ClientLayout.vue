<template>
  <div class="min-h-screen bg-[#FDF8F4] flex flex-col md:flex-row font-sans">
    <!-- Sidebar / Topbar mobile -->
    <aside class="w-full md:w-64 bg-white shadow-xl flex-shrink-0 md:min-h-screen z-20 flex flex-col border-r border-gray-100">
      <!-- Logo Area -->
      <div class="h-20 flex items-center justify-between md:justify-center px-6 border-b border-gray-100 hero-gradient relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjIiIGZpbGw9IiNmZmYiLz48L3N2Zz4=')]"></div>
        <router-link :to="{ name: 'home' }" class="flex items-center gap-2 relative z-10">
          <span class="font-playfair text-xl font-bold text-white">Mi <span class="text-[#D4A574]">Cuenta</span></span>
        </router-link>
        <button @click="menuOpen = !menuOpen" class="md:hidden text-white focus:outline-none">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
      </div>

      <!-- Navigation -->
      <nav :class="[menuOpen ? 'block' : 'hidden md:block', 'flex-1 py-6 px-4 space-y-2 bg-white']">
        <router-link :to="{ name: 'client.dashboard' }" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 text-gray-600 hover:bg-orange-50 hover:text-[#8B1A1A] group" active-class="!bg-gradient-to-r !from-[#8B1A1A] !to-[#B91C1C] !text-white shadow-md shadow-red-900/20">
          <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
          <span class="ml-3 font-medium">Dashboard</span>
        </router-link>
        
        <router-link :to="{ name: 'client.cart' }" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 text-gray-600 hover:bg-orange-50 hover:text-[#8B1A1A] group" active-class="!bg-gradient-to-r !from-[#8B1A1A] !to-[#B91C1C] !text-white shadow-md shadow-red-900/20">
          <div class="relative shrink-0">
            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            <span v-if="totalItems > 0" class="absolute -top-2 -right-2 w-4 h-4 bg-[#B91C1C] text-white text-[10px] font-bold rounded-full flex items-center justify-center border border-white">{{ totalItems }}</span>
          </div>
          <span class="ml-3 font-medium">Mi Carrito</span>
        </router-link>

        <router-link :to="{ name: 'client.orders' }" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 text-gray-600 hover:bg-orange-50 hover:text-[#8B1A1A] group" active-class="!bg-gradient-to-r !from-[#8B1A1A] !to-[#B91C1C] !text-white shadow-md shadow-red-900/20">
          <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
          <span class="ml-3 font-medium">Mis Pedidos</span>
        </router-link>

        <router-link :to="{ name: 'client.profile' }" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 text-gray-600 hover:bg-orange-50 hover:text-[#8B1A1A] group" active-class="!bg-gradient-to-r !from-[#8B1A1A] !to-[#B91C1C] !text-white shadow-md shadow-red-900/20">
          <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
          <span class="ml-3 font-medium">Mi Perfil</span>
        </router-link>

        <div class="pt-6 mt-6 border-t border-gray-100">
           <router-link :to="{ name: 'catalog' }" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group text-gray-600 hover:text-[#8B1A1A] hover:bg-red-50">
            <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            <span class="ml-3 font-medium">Ver Catálogo</span>
          </router-link>
          
          <button @click="handleLogout" class="w-full mt-2 flex items-center px-4 py-3 rounded-xl transition-all duration-200 group text-red-600 hover:bg-red-50">
            <svg class="w-5 h-5 shrink-0 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            <span class="ml-3 font-medium">Cerrar Sesión</span>
          </button>
        </div>
      </nav>
    </aside>
 
     <!-- Main Content Area -->
     <div class="flex-1 flex flex-col min-h-screen overflow-hidden">
       <!-- Topbar Header -->
       <header class="h-20 bg-white/80 backdrop-blur-md shadow-sm border-b border-gray-200/60 px-6 flex items-center justify-between sticky top-0 z-10 hidden md:flex">
         <h1 class="text-2xl font-playfair font-bold text-gray-800">
           Hola, <span class="text-[#8B1A1A]">{{ state.user?.name?.split(' ')[0] }}</span>
         </h1>
        <div class="flex items-center gap-4">
          <div class="text-sm text-gray-500 font-medium">{{ new Date().toLocaleDateString('es-ES', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}</div>
        </div>
      </header>

      <!-- Content -->
      <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 sm:p-8 animate-fadeInUp">
        <div class="max-w-5xl mx-auto">
          <router-view></router-view>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuth } from '../stores/auth.js';
import { useCart } from '../stores/cart.js';
import { logout } from '../api/auth.js';

const router = useRouter();
const { state, clearUser } = useAuth();
const { totalItems } = useCart();
const menuOpen = ref(false);

const handleLogout = async () => {
    try {
        await logout();
    } catch (error) {
        console.error(error);
    } finally {
        clearUser();
        router.push({ name: 'home' });
    }
};
</script>

<style scoped>
</style>
