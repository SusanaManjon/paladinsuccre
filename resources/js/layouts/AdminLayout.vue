<template>
  <div class="flex h-screen bg-gray-100 overflow-hidden font-sans">
    <!-- Sidebar -->
    <aside :class="['admin-sidebar text-white transition-all duration-300 flex flex-col z-20 shadow-xl', sidebarOpen ? 'w-64' : 'w-20 sm:w-64 -translate-x-full sm:translate-x-0 absolute sm:relative h-full']">
      <!-- Sidebar Header -->
      <div class="h-16 flex items-center justify-between px-4 border-b border-white/10 bg-black/20">
        <div class="flex items-center gap-3 overflow-hidden">
          <div class="w-8 h-8 rounded bg-[#B91C1C] flex items-center justify-center font-bold text-lg shrink-0 shadow-inner">
            P
          </div>
          <span class="font-playfair font-bold text-xl tracking-wider whitespace-nowrap transition-opacity" :class="!sidebarOpen && 'sm:opacity-100 opacity-0'">Admin</span>
        </div>
        <button @click="sidebarOpen = false" class="sm:hidden text-gray-400 hover:text-white">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
      </div>

      <!-- Navigation -->
      <nav class="flex-1 overflow-y-auto py-4 px-2 space-y-1 scrollbar-hide">
        <router-link :to="{ name: 'admin.dashboard' }" class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 group" active-class="bg-[#B91C1C] text-white shadow-md" :class="[route.name === 'admin.dashboard' ? 'bg-[#B91C1C]' : 'hover:bg-white/10 text-gray-300']">
          <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
          <span class="ml-3 font-medium truncate">Dashboard</span>
        </router-link>
        
        <router-link :to="{ name: 'admin.products' }" class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 group" active-class="bg-[#B91C1C] text-white shadow-md" :class="[route.name?.startsWith('admin.products') ? 'bg-[#B91C1C]' : 'hover:bg-white/10 text-gray-300']">
          <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
          <span class="ml-3 font-medium truncate">Productos</span>
        </router-link>

        <router-link :to="{ name: 'admin.categories' }" class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 group" active-class="bg-[#B91C1C] text-white shadow-md" :class="[route.name?.startsWith('admin.categories') ? 'bg-[#B91C1C]' : 'hover:bg-white/10 text-gray-300']">
          <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
          <span class="ml-3 font-medium truncate">Categorías</span>
        </router-link>

        <router-link :to="{ name: 'admin.orders' }" class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 group" active-class="bg-[#B91C1C] text-white shadow-md" :class="[route.name?.startsWith('admin.orders') ? 'bg-[#B91C1C]' : 'hover:bg-white/10 text-gray-300']">
          <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 002-2h2a2 2 0 002-2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
          <span class="ml-3 font-medium truncate">Pedidos</span>
        </router-link>

        <router-link :to="{ name: 'admin.users' }" class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 group" active-class="bg-[#B91C1C] text-white shadow-md" :class="[route.name?.startsWith('admin.users') ? 'bg-[#B91C1C]' : 'hover:bg-white/10 text-gray-300']">
          <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
          <span class="ml-3 font-medium truncate">Clientes</span>
        </router-link>
      </nav>

      <!-- User/Logout bottom -->
      <div class="p-4 border-t border-white/10 bg-black/20">
        <button @click="handleLogout" class="flex items-center w-full px-2 py-2 text-red-400 hover:text-white hover:bg-red-900/50 rounded-lg transition-colors group">
          <svg class="w-5 h-5 shrink-0 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
          <span class="ml-3 font-medium">Cerrar Sesión</span>
        </button>
      </div>
    </aside>

    <!-- Main Wrapper -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Topbar -->
      <header class="h-16 bg-white shadow-sm border-b border-gray-200 flex items-center justify-between px-4 sm:px-6 z-10">
        <div class="flex items-center">
          <button @click="sidebarOpen = !sidebarOpen" class="sm:hidden text-gray-500 hover:text-gray-700 mr-4 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
          </button>
          <h1 class="text-xl font-semibold text-gray-800 capitalize truncate">{{ routeName }}</h1>
        </div>
        
        <div class="flex items-center gap-4">
          <router-link :to="{ name: 'home' }" target="_blank" class="text-sm text-[#8B1A1A] hover:underline font-medium hidden sm:block">
            Ver tienda
          </router-link>
          <div class="flex items-center gap-3 pl-4 border-l border-gray-200">
            <div class="hidden md:block text-right">
              <div class="text-sm font-medium text-gray-900">{{ state.user?.name }}</div>
              <div class="text-xs text-gray-500">Administrador</div>
            </div>
            <div class="w-9 h-9 rounded-full bg-[#111827] text-white flex items-center justify-center font-bold shadow-sm">
              {{ state.user?.name?.charAt(0).toUpperCase() || 'A' }}
            </div>
          </div>
        </div>
      </header>

      <!-- Main Content Area -->
      <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-4 sm:p-6">
        <div class="max-w-7xl mx-auto animate-fadeInUp">
          <router-view></router-view>
        </div>
      </main>
    </div>
    
    <!-- Overlay for mobile sidebar -->
    <div v-if="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-gray-900/50 z-10 sm:hidden backdrop-blur-sm transition-opacity"></div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useAuth } from '../stores/auth.js';
import { logout } from '../api/auth.js';

const route = useRoute();
const router = useRouter();
const { state, clearUser } = useAuth();

const sidebarOpen = ref(false);

const routeName = computed(() => {
    const map = {
        'admin.dashboard': 'Dashboard',
        'admin.products': 'Productos',
        'admin.products.create': 'Nuevo Producto',
        'admin.products.edit': 'Editar Producto',
        'admin.categories': 'Categorías',
        'admin.categories.create': 'Nueva Categoría',
        'admin.categories.edit': 'Editar Categoría',
        'admin.users': 'Clientes',
        'admin.users.create': 'Nuevo Cliente',
        'admin.orders': 'Pedidos',
    };
    return map[route.name] || 'Administración';
});

const handleLogout = async () => {
    try {
        await logout();
        clearUser();
        router.push({ name: 'login' });
    } catch (error) {
        console.error(error);
    }
};
</script>

<style scoped>
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
