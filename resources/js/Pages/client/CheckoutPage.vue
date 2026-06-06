<template>
  <div>
    <h1 class="text-3xl font-playfair font-bold text-gray-900 mb-8">Checkout</h1>

    <div v-if="state.items.length === 0" class="bg-white rounded-xl p-8 text-center border border-gray-100">
      <p class="text-gray-500 mb-4">Tu carrito está vacío.</p>
      <router-link :to="{ name: 'catalog' }" class="btn-primary">Volver al catálogo</router-link>
    </div>

    <div v-else class="flex flex-col lg:flex-row gap-8">
      <!-- Checkout Form -->
      <div class="flex-1 bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Detalles de Envío</h2>
        
        <form @submit.prevent="submitOrder" class="space-y-6">
          <div v-if="errorMsg" class="bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
            <p class="text-sm text-red-700">{{ errorMsg }}</p>
          </div>

          <div>
            <label for="phone" class="form-label">Teléfono de contacto *</label>
            <input type="text" id="phone" v-model="form.phone" required class="form-input" :class="{'border-red-500': errors.phone}">
            <span v-if="errors.phone" class="text-xs text-red-500 mt-1">{{ errors.phone[0] }}</span>
          </div>

          <div>
            <label for="shipping_address" class="form-label">Dirección de envío completa *</label>
            <textarea id="shipping_address" v-model="form.shipping_address" required rows="3" class="form-input" placeholder="Calle, Número, Zona, Referencias..." :class="{'border-red-500': errors.shipping_address}"></textarea>
            <span v-if="errors.shipping_address" class="text-xs text-red-500 mt-1">{{ errors.shipping_address[0] }}</span>
          </div>

          <div>
            <label for="notes" class="form-label">Notas adicionales (Opcional)</label>
            <textarea id="notes" v-model="form.notes" rows="2" class="form-input" placeholder="Horarios de entrega, etc."></textarea>
          </div>

          <div>
            <label class="form-label font-bold mb-3">Método de Pago *</label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <label class="flex items-center gap-3 p-4 border rounded-xl cursor-pointer transition-all" :class="form.payment_method === 'cash' ? 'border-[#8B1A1A] bg-red-50/10 ring-1 ring-[#8B1A1A]' : 'border-gray-200 hover:bg-gray-50'">
                <input type="radio" v-model="form.payment_method" value="cash" class="w-4 h-4 text-[#8B1A1A] focus:ring-[#8B1A1A]">
                <div>
                  <div class="font-semibold text-sm text-gray-900">Efectivo / Transferencia</div>
                  <div class="text-xs text-gray-500">Paga al recibir o vía QR</div>
                </div>
              </label>
              <label class="flex items-center gap-3 p-4 border rounded-xl cursor-pointer transition-all" :class="form.payment_method === 'paypal' ? 'border-[#8B1A1A] bg-red-50/10 ring-1 ring-[#8B1A1A]' : 'border-gray-200 hover:bg-gray-50'">
                <input type="radio" v-model="form.payment_method" value="paypal" class="w-4 h-4 text-[#8B1A1A] focus:ring-[#8B1A1A]">
                <div>
                  <div class="font-semibold text-sm text-gray-900">PayPal</div>
                  <div class="text-xs text-gray-500">Tarjeta de Crédito / Débito</div>
                </div>
              </label>
            </div>
          </div>

          <div v-show="form.payment_method === 'cash'">
            <button type="submit" :disabled="loading" class="w-full btn-primary py-4 text-lg mt-4 shadow-lg flex justify-center">
              <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              {{ loading ? 'Procesando...' : 'Confirmar Pedido' }}
            </button>
          </div>

          <div v-show="form.payment_method === 'paypal'" class="mt-6">
            <div id="paypal-button-container" class="w-full min-h-[150px]"></div>
          </div>
        </form>
      </div>

      <!-- Order Summary -->
      <div class="w-full lg:w-96 shrink-0">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-24">
          <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-4">Resumen</h3>
          
          <ul class="space-y-4 mb-6 max-h-96 overflow-y-auto pr-2">
            <li v-for="item in state.items" :key="item.product_id" class="flex justify-between">
              <div class="flex gap-3">
                <div class="relative">
                  <img v-if="item.image_url" :src="item.image_url" class="w-12 h-12 rounded object-cover border border-gray-200">
                  <div v-else class="w-12 h-12 bg-gray-100 rounded border border-gray-200"></div>
                  <span class="absolute -top-2 -right-2 bg-gray-500 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full">{{ item.quantity }}</span>
                </div>
                <div>
                  <h4 class="text-sm font-medium text-gray-900 line-clamp-2">{{ item.name }}</h4>
                  <p class="text-xs text-gray-500">Bs. {{ item.price }}</p>
                </div>
              </div>
              <div class="text-sm font-medium text-gray-900">
                Bs. {{ (item.price * item.quantity).toFixed(2) }}
              </div>
            </li>
          </ul>

          <div class="border-t pt-4 mb-2 flex justify-between text-gray-600 text-sm">
            <span>Subtotal</span>
            <span>Bs. {{ totalPrice.toFixed(2) }}</span>
          </div>
          <div class="flex justify-between text-gray-600 text-sm mb-4">
            <span>Envío</span>
            <span class="text-green-600 font-medium">Gratis</span>
          </div>
          
          <div class="border-t pt-4 flex justify-between items-center">
            <span class="text-lg font-bold text-gray-900">Total</span>
            <span class="text-2xl font-bold text-[#8B1A1A]">Bs. {{ totalPrice.toFixed(2) }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref, onMounted, watch } from 'vue';
import { useRouter } from 'vue-router';
import { useCart } from '../../stores/cart.js';
import { useAuth } from '../../stores/auth.js';
import { createOrder } from '../../api/orders.js';

const router = useRouter();
const { state: cartState, totalPrice, clearCart } = useCart();
const { state: authState } = useAuth();

const state = cartState;
const loading = ref(false);
const errorMsg = ref('');
const errors = ref({});

const form = reactive({
    shipping_address: '',
    phone: '',
    notes: '',
    payment_method: 'cash',
});

onMounted(() => {
    if (authState.user) {
        form.phone = authState.user.phone || '';
        form.shipping_address = authState.user.address || '';
    }
});

const submitOrder = async () => {
    if (state.items.length === 0) return;
    
    loading.value = true;
    errorMsg.value = '';
    errors.value = {};

    const payload = {
        ...form,
        items: state.items.map(i => ({ product_id: i.product_id, quantity: i.quantity }))
    };

    try {
        const { data } = await createOrder(payload);
        clearCart();
        router.push({ name: 'client.orders', query: { success: data.order_number } });
    } catch (error) {
        if (error.response?.status === 422) {
            errors.value = error.response.data.errors || {};
            if (error.response.data.message) {
                 errorMsg.value = error.response.data.message;
            }
        } else {
            errorMsg.value = 'Error al procesar el pedido. Inténtalo de nuevo.';
        }
    } finally {
        loading.value = false;
    }
};

const sdkLoaded = ref(false);

const loadPaypalSdk = () => {
    return new Promise((resolve, reject) => {
        if (window.paypal) {
            resolve(window.paypal);
            return;
        }
        const script = document.createElement('script');
        script.src = `https://www.paypal.com/sdk/js?client-id=${window.PAYPAL_CLIENT_ID || 'sb'}&currency=USD`;
        script.onload = () => resolve(window.paypal);
        script.onerror = (err) => reject(err);
        document.body.appendChild(script);
    });
};

const renderPaypalButtons = async () => {
    try {
        const paypal = await loadPaypalSdk();
        sdkLoaded.value = true;
        
        const container = document.getElementById('paypal-button-container');
        if (container) {
            container.innerHTML = '';
        }

        paypal.Buttons({
            createOrder: (data, actions) => {
                // Tipo de cambio promedio 6.96 de Bs a USD
                const usdAmount = (totalPrice.value / 6.96).toFixed(2);
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            currency_code: 'USD',
                            value: usdAmount
                        },
                        description: 'Embutidos Paladín Sucre — Pedido'
                    }]
                });
            },
            onApprove: async (data, actions) => {
                loading.value = true;
                errorMsg.value = '';
                try {
                    const details = await actions.order.capture();
                    await submitPaypalOrder(details.id);
                } catch (error) {
                    console.error('Error capturando pago PayPal:', error);
                    errorMsg.value = 'El pago se procesó en PayPal pero falló la creación del pedido. Por favor contáctanos.';
                } finally {
                    loading.value = false;
                }
            },
            onError: (err) => {
                console.error('PayPal Error:', err);
                errorMsg.value = 'Ocurrió un error con la pasarela de PayPal. Inténtalo de nuevo.';
            }
        }).render('#paypal-button-container');
    } catch (error) {
        console.error('Error cargando PayPal SDK:', error);
        errorMsg.value = 'No se pudo cargar la pasarela de PayPal. Revisa tu conexión.';
    }
};

const submitPaypalOrder = async (transactionId) => {
    if (state.items.length === 0) return;

    const payload = {
        ...form,
        payment_method: 'paypal',
        paypal_transaction_id: transactionId,
        items: state.items.map(i => ({ product_id: i.product_id, quantity: i.quantity }))
    };

    try {
        const { data } = await createOrder(payload);
        clearCart();
        router.push({ name: 'client.orders', query: { success: data.order_number } });
    } catch (error) {
        if (error.response?.status === 422) {
            errors.value = error.response.data.errors || {};
            if (error.response.data.message) {
                 errorMsg.value = error.response.data.message;
            }
        } else {
            errorMsg.value = 'Error al registrar tu pedido pagado. Por favor, toma nota de tu ID de PayPal y contáctanos.';
        }
    }
};

watch(() => form.payment_method, (newVal) => {
    if (newVal === 'paypal') {
        setTimeout(() => {
            renderPaypalButtons();
        }, 100);
    }
});
</script>
