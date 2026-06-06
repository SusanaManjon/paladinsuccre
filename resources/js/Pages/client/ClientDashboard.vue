<template>
  <div>
    <h1 class="text-3xl font-playfair font-bold text-gray-900 mb-6">Resumen de Cuenta</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
      <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
        </div>
        <div>
          <h3 class="text-sm font-medium text-gray-500">Mi Perfil</h3>
          <router-link :to="{ name: 'client.profile' }" class="text-[#8B1A1A] font-semibold text-sm hover:underline">Ver detalles</router-link>
        </div>
      </div>

      <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 bg-green-50 text-green-600 rounded-lg flex items-center justify-center">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
        </div>
        <div>
          <h3 class="text-sm font-medium text-gray-500">Mis Pedidos</h3>
          <router-link :to="{ name: 'client.orders' }" class="text-[#8B1A1A] font-semibold text-sm hover:underline">Ver historial</router-link>
        </div>
      </div>

      <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-lg flex items-center justify-center">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        </div>
        <div>
          <h3 class="text-sm font-medium text-gray-500">Carrito</h3>
          <router-link :to="{ name: 'client.cart' }" class="text-[#8B1A1A] font-semibold text-sm hover:underline">{{ totalItems }} artículos</router-link>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
      <h2 class="text-xl font-bold text-gray-900 mb-6">Últimos Pedidos</h2>
      
      <div v-if="loading" class="py-10 flex justify-center">
        <div class="w-8 h-8 border-4 border-gray-200 border-t-[#8B1A1A] rounded-full animate-spin"></div>
      </div>
      
      <div v-else-if="orders.length === 0" class="text-center py-10">
        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
        <p class="text-gray-500 mb-4">Aún no has realizado ningún pedido.</p>
        <router-link :to="{ name: 'catalog' }" class="btn-primary">Hacer mi primer pedido</router-link>
      </div>

      <div v-else class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead>
            <tr>
              <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pedido #</th>
              <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
              <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
              <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="order in orders" :key="order.id" class="hover:bg-gray-50">
              <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ order.order_number }}</td>
              <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">{{ order.created_at }}</td>
              <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 font-bold">Bs. {{ order.total }}</td>
              <td class="px-4 py-4 whitespace-nowrap text-sm">
                <StatusBadge :status="order.status" />
              </td>
            </tr>
          </tbody>
        </table>
        <div class="mt-4 text-right">
          <router-link :to="{ name: 'client.orders' }" class="text-sm font-medium text-[#8B1A1A] hover:underline">Ver todos &rarr;</router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { getClientOrders } from '../../api/orders.js';
import { useCart } from '../../stores/cart.js';
import StatusBadge from '../../components/common/StatusBadge.vue';

const { totalItems } = useCart();
const orders = ref([]);
const loading = ref(true);

onMounted(async () => {
    try {
        const { data } = await getClientOrders({ page: 1 });
        orders.value = data.data.slice(0, 5); // Últimos 5
    } catch (e) {
        console.error(e);
    } finally {
        loading.value = false;
    }
});
</script>
