<template>
  <div>
    <h1 class="text-3xl font-playfair font-bold text-gray-900 mb-8">Mis Pedidos</h1>

    <div v-if="$route.query.success" class="mb-8 bg-green-50 border border-green-200 rounded-xl p-4 flex items-center gap-4 animate-slideInRight">
      <div class="w-10 h-10 bg-green-100 text-green-600 rounded-full flex items-center justify-center shrink-0">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
      </div>
      <div>
        <h4 class="text-green-800 font-bold">¡Pedido realizado con éxito!</h4>
        <p class="text-sm text-green-600">Tu número de pedido es <strong>{{ $route.query.success }}</strong>. Te contactaremos pronto.</p>
      </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <div v-if="loading" class="py-20 flex justify-center">
        <div class="w-10 h-10 border-4 border-gray-200 border-t-[#8B1A1A] rounded-full animate-spin"></div>
      </div>

      <div v-else-if="orders.length === 0" class="text-center py-20 px-4">
        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l12H4L5 9z"></path></svg>
        </div>
        <h2 class="text-xl font-bold text-gray-900 mb-2">No tienes pedidos</h2>
        <p class="text-gray-500 mb-6">Aún no has realizado ninguna compra con nosotros.</p>
        <router-link :to="{ name: 'catalog' }" class="btn-primary">Ver Productos</router-link>
      </div>

      <div v-else>
        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Pedido #</th>
                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Fecha</th>
                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Artículos</th>
                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Total</th>
                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Estado</th>
                <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="order in orders" :key="order.id" class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ order.order_number }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ order.created_at }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ order.items_count }} item(s)</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">Bs. {{ order.total }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <StatusBadge :status="order.status" />
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <button @click="openOrderDetails(order.id)" class="text-[#8B1A1A] hover:text-[#B91C1C]">Ver Detalles</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Mobile List -->
        <div class="md:hidden divide-y divide-gray-200">
          <div v-for="order in orders" :key="order.id" class="p-4 bg-white" @click="openOrderDetails(order.id)">
            <div class="flex justify-between items-start mb-2">
              <span class="font-bold text-gray-900">{{ order.order_number }}</span>
              <StatusBadge :status="order.status" />
            </div>
            <div class="flex justify-between items-center text-sm">
              <span class="text-gray-500">{{ order.created_at }}</span>
              <span class="font-bold text-gray-900">Bs. {{ order.total }}</span>
            </div>
          </div>
        </div>

        <div class="p-4 border-t border-gray-200">
          <Pagination :meta="meta" @page-changed="fetchOrders" />
        </div>
      </div>
    </div>

    <!-- Modal Detalles -->
    <Modal :show="showModal" title="Detalles del Pedido" maxWidth="3xl" @close="closeModal">
      <div v-if="loadingDetails" class="py-10 flex justify-center">
         <div class="w-8 h-8 border-4 border-gray-200 border-t-[#8B1A1A] rounded-full animate-spin"></div>
      </div>
      <div v-else-if="selectedOrder">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
          <div class="bg-gray-50 p-4 rounded-lg">
            <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Información</h4>
            <p class="text-sm text-gray-900"><span class="font-medium">Nº Pedido:</span> {{ selectedOrder.order_number }}</p>
            <p class="text-sm text-gray-900"><span class="font-medium">Fecha:</span> {{ selectedOrder.created_at }}</p>
            <div class="mt-2"><StatusBadge :status="selectedOrder.status" /></div>
          </div>
          <div class="bg-gray-50 p-4 rounded-lg">
             <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Envío</h4>
             <p class="text-sm text-gray-900"><span class="font-medium">Teléfono:</span> {{ selectedOrder.phone }}</p>
             <p class="text-sm text-gray-900"><span class="font-medium">Dirección:</span> {{ selectedOrder.shipping_address }}</p>
             <p v-if="selectedOrder.notes" class="text-sm text-gray-900 mt-2"><span class="font-medium">Notas:</span> {{ selectedOrder.notes }}</p>
          </div>
        </div>

        <h4 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Artículos</h4>
        <ul class="divide-y divide-gray-200 mb-6">
          <li v-for="item in selectedOrder.items" :key="item.id" class="py-3 flex justify-between">
            <div class="flex items-center gap-3">
              <img v-if="item.product.image_url" :src="item.product.image_url" class="w-12 h-12 rounded object-cover">
              <div v-else class="w-12 h-12 bg-gray-200 rounded"></div>
              <div>
                <p class="text-sm font-bold text-gray-900">{{ item.product.name }}</p>
                <p class="text-xs text-gray-500">{{ item.quantity }} x Bs. {{ item.unit_price }} ({{ item.product.weight }})</p>
              </div>
            </div>
            <div class="font-bold text-gray-900">Bs. {{ item.subtotal }}</div>
          </li>
        </ul>

        <div class="flex justify-end text-xl">
           <span class="font-medium mr-4">Total:</span>
           <span class="font-bold text-[#8B1A1A]">Bs. {{ selectedOrder.total }}</span>
        </div>
      </div>
    </Modal>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { getClientOrders, getClientOrder } from '../../api/orders.js';
import StatusBadge from '../../components/common/StatusBadge.vue';
import Pagination from '../../components/common/Pagination.vue';
import Modal from '../../components/common/Modal.vue';

const orders = ref([]);
const meta = ref(null);
const loading = ref(true);

const showModal = ref(false);
const loadingDetails = ref(false);
const selectedOrder = ref(null);

const fetchOrders = async (page = 1) => {
    loading.value = true;
    try {
        const { data } = await getClientOrders({ page });
        orders.value = data.data;
        meta.value = data.meta;
    } catch (e) {
        console.error(e);
    } finally {
        loading.value = false;
    }
};

const openOrderDetails = async (id) => {
    showModal.value = true;
    loadingDetails.value = true;
    selectedOrder.value = null;
    try {
        const { data } = await getClientOrder(id);
        selectedOrder.value = data;
    } catch (e) {
        console.error(e);
    } finally {
        loadingDetails.value = false;
    }
};

const closeModal = () => {
    showModal.value = false;
    setTimeout(() => selectedOrder.value = null, 300);
};

onMounted(() => fetchOrders());
</script>
