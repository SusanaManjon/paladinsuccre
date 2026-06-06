<template>
  <div>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
      <h1 class="text-3xl font-playfair font-bold text-gray-900">Gestión de Pedidos</h1>
      
      <div class="w-full sm:w-64 shrink-0">
        <select v-model="statusFilter" @change="fetchOrders(1)" class="form-input">
          <option value="">Todos los estados</option>
          <option value="pending">Pendientes</option>
          <option value="confirmed">Confirmados</option>
          <option value="processing">En Proceso</option>
          <option value="delivered">Entregados</option>
          <option value="cancelled">Cancelados</option>
        </select>
      </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <div v-if="loading" class="py-20 flex justify-center">
        <div class="w-10 h-10 border-4 border-gray-200 border-t-[#8B1A1A] rounded-full animate-spin"></div>
      </div>
      
      <div v-else-if="orders.length === 0" class="text-center py-20 text-gray-500">
        No se encontraron pedidos.
      </div>

      <div v-else class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Pedido #</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Cliente</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Fecha</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Total</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Estado</th>
              <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="order in orders" :key="order.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ order.order_number }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                <div class="font-medium text-gray-900">{{ order.user?.name || 'Cliente Eliminado' }}</div>
                <div class="text-xs">{{ order.user?.email }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ order.created_at }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">Bs. {{ order.total }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <StatusBadge :status="order.status" />
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <button @click="openOrderDetails(order.id)" class="text-[#8B1A1A] hover:text-[#B91C1C]">Gestionar</button>
              </td>
            </tr>
          </tbody>
        </table>
        <div class="p-4 border-t border-gray-200">
          <Pagination :meta="meta" @page-changed="fetchOrders" />
        </div>
      </div>
    </div>

    <!-- Modal Detalles -->
    <Modal :show="showModal" title="Gestión del Pedido" maxWidth="3xl" @close="closeModal">
      <div v-if="loadingDetails" class="py-10 flex justify-center">
         <div class="w-8 h-8 border-4 border-gray-200 border-t-[#8B1A1A] rounded-full animate-spin"></div>
      </div>
      <div v-else-if="selectedOrder">
        <!-- Status Updater -->
        <div class="mb-6 p-4 bg-[#FDF8F4] border border-[#D4A574] rounded-xl flex flex-col sm:flex-row items-center justify-between gap-4">
          <div>
            <h4 class="font-bold text-gray-900">Actualizar Estado</h4>
            <p class="text-xs text-gray-600">Modifica el estado actual del pedido para notificar al cliente.</p>
          </div>
          <div class="flex items-center gap-2">
            <select v-model="updateStatusValue" class="form-input w-40">
              <option value="pending">Pendiente</option>
              <option value="confirmed">Confirmado</option>
              <option value="processing">En Proceso</option>
              <option value="delivered">Entregado</option>
              <option value="cancelled">Cancelado</option>
            </select>
            <button @click="updateStatus" :disabled="updatingStatus || updateStatusValue === selectedOrder.status" class="btn-primary text-sm py-2">
               {{ updatingStatus ? '...' : 'Guardar' }}
            </button>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
          <div class="bg-gray-50 p-4 rounded-lg">
            <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Información del Cliente</h4>
            <p class="text-sm text-gray-900"><span class="font-medium">Nombre:</span> {{ selectedOrder.user?.name }}</p>
            <p class="text-sm text-gray-900"><span class="font-medium">Email:</span> {{ selectedOrder.user?.email }}</p>
            <p class="text-sm text-gray-900"><span class="font-medium">Teléfono Provisto:</span> {{ selectedOrder.phone }}</p>
          </div>
          <div class="bg-gray-50 p-4 rounded-lg">
             <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Datos de Envío</h4>
             <p class="text-sm text-gray-900"><span class="font-medium">Dirección:</span> {{ selectedOrder.shipping_address }}</p>
             <p v-if="selectedOrder.notes" class="text-sm text-gray-900 mt-2"><span class="font-medium">Notas:</span> {{ selectedOrder.notes }}</p>
          </div>
        </div>

        <h4 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Artículos ({{ selectedOrder.items?.length }})</h4>
        <ul class="divide-y divide-gray-200 mb-6 max-h-64 overflow-y-auto pr-2">
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

        <div class="flex justify-end text-xl border-t border-gray-200 pt-4">
           <span class="font-medium mr-4">Total:</span>
           <span class="font-bold text-[#8B1A1A]">Bs. {{ selectedOrder.total }}</span>
        </div>
      </div>
    </Modal>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { getAdminOrders, getAdminOrder, updateOrderStatus } from '../../api/orders.js';
import StatusBadge from '../../components/common/StatusBadge.vue';
import Pagination from '../../components/common/Pagination.vue';
import Modal from '../../components/common/Modal.vue';

const orders = ref([]);
const meta = ref(null);
const loading = ref(true);
const statusFilter = ref('');

const showModal = ref(false);
const loadingDetails = ref(false);
const selectedOrder = ref(null);

const updateStatusValue = ref('');
const updatingStatus = ref(false);

const fetchOrders = async (page = 1) => {
    loading.value = true;
    try {
        const { data } = await getAdminOrders({ page, status: statusFilter.value });
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
        const { data } = await getAdminOrder(id);
        selectedOrder.value = data;
        updateStatusValue.value = data.status;
    } catch (e) {
        console.error(e);
    } finally {
        loadingDetails.value = false;
    }
};

const closeModal = () => {
    showModal.value = false;
    setTimeout(() => {
        selectedOrder.value = null;
        updateStatusValue.value = '';
    }, 300);
};

const updateStatus = async () => {
    updatingStatus.value = true;
    try {
        await updateOrderStatus(selectedOrder.value.id, updateStatusValue.value);
        // Refresh local data
        selectedOrder.value.status = updateStatusValue.value;
        // Refresh list
        fetchOrders(meta.value?.current_page || 1);
    } catch (e) {
        console.error(e);
        alert('Error al actualizar el estado');
    } finally {
        updatingStatus.value = false;
    }
};

onMounted(() => fetchOrders());
</script>
