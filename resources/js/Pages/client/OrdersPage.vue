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
            <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Información del Pedido</h4>
            <p class="text-sm text-gray-900 mb-1"><span class="font-medium">Nº Pedido:</span> {{ selectedOrder?.order_number }}</p>
            <p class="text-sm text-gray-900 mb-1"><span class="font-medium">Fecha:</span> {{ selectedOrder?.created_at }}</p>
            <p class="text-sm text-gray-900 mb-2"><span class="font-medium">Método de Pago:</span> {{ selectedOrder?.payment_method === 'paypal' ? 'PayPal' : 'Transferencia / QR' }}</p>
            <div class="flex items-center gap-2">
              <StatusBadge v-if="selectedOrder?.status" :status="selectedOrder.status" />
              <span class="text-xs px-2 py-1 rounded-full font-medium" :class="selectedOrder?.payment_status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'">
                {{ selectedOrder?.payment_status === 'paid' ? '💰 Pagado' : '⏳ Pago Pendiente' }}
              </span>
            </div>
          </div>
          <div class="bg-gray-50 p-4 rounded-lg">
             <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Datos de Envío</h4>
             <p class="text-sm text-gray-900 mb-1"><span class="font-medium">Teléfono:</span> {{ selectedOrder?.phone }}</p>
             <p class="text-sm text-gray-900 mb-1"><span class="font-medium">Dirección:</span> {{ selectedOrder?.shipping_address }}</p>
             <p v-if="selectedOrder?.notes" class="text-sm text-gray-900 mt-2"><span class="font-medium">Notas:</span> {{ selectedOrder?.notes }}</p>
          </div>
        </div>

        <!-- Payment Proof Upload Section -->
        <div v-if="selectedOrder?.payment_method !== 'paypal'" class="mb-6 rounded-xl overflow-hidden border" :class="selectedOrder?.payment_proof_url ? 'border-green-200 bg-green-50' : 'border-amber-200 bg-amber-50'">
          
          <!-- Already uploaded -->
          <div v-if="selectedOrder?.payment_proof_url" class="p-5">
            <div class="flex items-center gap-3 mb-3">
              <div class="w-8 h-8 bg-green-100 text-green-600 rounded-full flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
              </div>
              <div>
                <h4 class="font-bold text-green-900 text-sm">Comprobante Enviado</h4>
                <p class="text-xs text-green-700">{{ selectedOrder?.payment_status === 'paid' ? 'Tu pago ha sido verificado.' : 'Estamos revisando tu comprobante. Te notificaremos pronto.' }}</p>
              </div>
            </div>
            <a :href="selectedOrder?.payment_proof_url" target="_blank" class="inline-flex items-center gap-2 bg-white border border-green-200 text-green-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-50 transition">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
              Ver mi comprobante
            </a>
          </div>

          <!-- Needs upload -->
          <div v-else class="p-5">
            <div class="flex items-center gap-3 mb-4">
              <div class="w-8 h-8 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
              </div>
              <div>
                <h4 class="font-bold text-amber-900 text-sm">Sube tu Comprobante de Pago</h4>
                <p class="text-xs text-amber-700">Realiza la transferencia o pago por QR y sube aquí la captura de pantalla.</p>
              </div>
            </div>

            <!-- Preview -->
            <div v-if="previewUrl" class="mb-4 flex justify-center">
              <div class="relative">
                <img :src="previewUrl" class="max-h-48 rounded-lg shadow-sm border border-gray-200">
                <button @click="clearFile" class="absolute -top-2 -right-2 bg-red-500 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs hover:bg-red-600 shadow">✕</button>
              </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-3">
              <label class="flex-1 w-full cursor-pointer">
                <div class="flex items-center justify-center gap-2 bg-white border-2 border-dashed border-amber-300 text-amber-700 rounded-lg py-3 px-4 hover:bg-amber-50 transition text-sm font-medium">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                  {{ selectedFile ? selectedFile.name : 'Seleccionar Archivo (hasta 20MB)' }}
                </div>
                <input type="file" @change="handleFileUpload" accept="image/*,.pdf,.heic" class="hidden">
              </label>
              <button v-if="selectedFile" @click="uploadProof" :disabled="uploading" class="w-full sm:w-auto bg-[#8B1A1A] hover:bg-[#6B1414] text-white px-6 py-3 rounded-lg text-sm font-bold transition flex items-center justify-center gap-2 shadow-lg">
                <svg v-if="uploading" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                {{ uploading ? 'Subiendo...' : 'Enviar Comprobante' }}
              </button>
            </div>
          </div>
        </div>

        <!-- PayPal paid message -->
        <div v-if="selectedOrder?.payment_method === 'paypal'" class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl flex items-center gap-3">
          <div class="w-8 h-8 bg-green-100 text-green-600 rounded-full flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
          </div>
          <div>
            <h4 class="font-bold text-green-900 text-sm">Pago procesado con PayPal</h4>
            <p class="text-xs text-green-700">Tu pago fue confirmado automáticamente.</p>
          </div>
        </div>

        <h4 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Artículos</h4>
        <ul class="divide-y divide-gray-200 mb-6">
          <li v-for="item in selectedOrder?.items" :key="item.id" class="py-3 flex justify-between">
            <div class="flex items-center gap-3">
              <img v-if="item.product?.image_url" :src="item.product.image_url" class="w-12 h-12 rounded object-cover">
              <div v-else class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center text-gray-400">
                 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
              </div>
              <div>
                <p class="text-sm font-bold text-gray-900">{{ item.product?.name || 'Producto Eliminado' }}</p>
                <p class="text-xs text-gray-500">{{ item.quantity }} x Bs. {{ item.unit_price }} {{ item.product?.weight ? '(' + item.product.weight + ')' : '' }}</p>
              </div>
            </div>
            <div class="font-bold text-gray-900">Bs. {{ item.subtotal }}</div>
          </li>
        </ul>

        <div class="flex justify-end text-xl border-t pt-4">
           <span class="font-medium mr-4">Total:</span>
           <span class="font-bold text-[#8B1A1A]">Bs. {{ selectedOrder?.total }}</span>
        </div>
      </div>
    </Modal>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { getClientOrders, getClientOrder, uploadPaymentProof } from '../../api/orders.js';
import StatusBadge from '../../components/common/StatusBadge.vue';
import Pagination from '../../components/common/Pagination.vue';
import Modal from '../../components/common/Modal.vue';

const orders = ref([]);
const meta = ref(null);
const loading = ref(true);

const showModal = ref(false);
const loadingDetails = ref(false);
const selectedOrder = ref(null);

const selectedFile = ref(null);
const uploading = ref(false);
const previewUrl = ref(null);

const handleFileUpload = (event) => {
    const file = event.target.files[0];
    selectedFile.value = file;
    if (file) {
        previewUrl.value = URL.createObjectURL(file);
    }
};

const clearFile = () => {
    selectedFile.value = null;
    if (previewUrl.value) {
        URL.revokeObjectURL(previewUrl.value);
        previewUrl.value = null;
    }
};

const uploadProof = async () => {
    if (!selectedFile.value || !selectedOrder.value) return;

    uploading.value = true;
    const formData = new FormData();
    formData.append('payment_proof', selectedFile.value);

    try {
        const { data } = await uploadPaymentProof(selectedOrder.value.id, formData);
        selectedOrder.value.payment_proof_url = data.payment_proof_url;
        clearFile();
        alert('¡Comprobante enviado correctamente! Lo revisaremos pronto.');
    } catch (e) {
        console.error(e);
        alert('Error al subir el comprobante. Inténtalo de nuevo.');
    } finally {
        uploading.value = false;
    }
};

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
    selectedFile.value = null;
    try {
        const { data } = await getClientOrder(id);
        selectedOrder.value = data;
    } catch (e) {
        console.error(e);
        alert('Ocurrió un error al intentar cargar los detalles del pedido. Por favor, actualiza la página.');
        showModal.value = false;
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
