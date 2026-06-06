<template>
  <div>
    <h1 class="text-3xl font-playfair font-bold text-gray-900 mb-8">Mi Carrito</h1>

    <div v-if="state.items.length === 0" class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
      <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
      </div>
      <h2 class="text-2xl font-bold text-gray-900 mb-2">Tu carrito está vacío</h2>
      <p class="text-gray-500 mb-8">Aún no has agregado ningún producto a tu carrito.</p>
      <router-link :to="{ name: 'catalog' }" class="btn-primary">Ver Catálogo</router-link>
    </div>

    <div v-else class="flex flex-col lg:flex-row gap-8">
      <!-- Items List -->
      <div class="flex-1 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <ul class="divide-y divide-gray-200">
          <li v-for="item in state.items" :key="item.product_id" class="p-6 flex flex-col sm:flex-row gap-6">
            <div class="w-24 h-24 shrink-0 bg-gray-100 rounded-lg overflow-hidden relative">
              <img v-if="item.image_url" :src="item.image_url" :alt="item.name" class="w-full h-full object-cover">
              <div v-else class="w-full h-full flex items-center justify-center"><svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>
            </div>
            
            <div class="flex-1 flex flex-col justify-between">
              <div class="flex justify-between items-start">
                <div>
                  <h3 class="font-bold text-gray-900 text-lg">{{ item.name }}</h3>
                  <p class="text-sm text-gray-500">{{ item.weight }}</p>
                </div>
                <div class="text-right">
                  <div class="font-bold text-gray-900 text-lg">Bs. {{ Number(item.price).toFixed(2) }}</div>
                  <div class="text-sm text-gray-500 font-medium">Subtotal: Bs. {{ (item.price * item.quantity).toFixed(2) }}</div>
                </div>
              </div>
              
              <div class="mt-4 flex items-center justify-between">
                <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                  <button @click="updateQuantity(item.product_id, item.quantity - 1)" class="w-10 h-10 flex items-center justify-center bg-gray-50 hover:bg-gray-100 transition-colors text-gray-600">-</button>
                  <input type="number" :value="item.quantity" @change="e => updateQuantity(item.product_id, parseInt(e.target.value))" min="1" class="w-12 h-10 text-center border-x border-gray-300 focus:outline-none font-medium text-gray-900 p-0 m-0 shadow-none">
                  <button @click="updateQuantity(item.product_id, item.quantity + 1)" class="w-10 h-10 flex items-center justify-center bg-gray-50 hover:bg-gray-100 transition-colors text-gray-600">+</button>
                </div>
                
                <button @click="removeItem(item.product_id)" class="text-red-500 hover:text-red-700 flex items-center gap-1 text-sm font-medium transition-colors">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                  Eliminar
                </button>
              </div>
            </div>
          </li>
        </ul>
      </div>

      <!-- Summary -->
      <div class="w-full lg:w-80 shrink-0">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-24">
          <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-4">Resumen del Pedido</h3>
          
          <div class="flex justify-between items-center mb-4">
            <span class="text-gray-600">Total artículos</span>
            <span class="font-medium text-gray-900">{{ totalItems }}</span>
          </div>
          
          <div class="flex justify-between items-center border-t pt-4 mb-6">
            <span class="text-lg font-bold text-gray-900">Total</span>
            <span class="text-2xl font-bold text-[#8B1A1A]">Bs. {{ totalPrice.toFixed(2) }}</span>
          </div>
          
          <router-link :to="{ name: 'client.checkout' }" class="btn-primary w-full py-3 text-lg justify-center shadow-lg">
            Finalizar Compra
          </router-link>
          
          <div class="mt-4 flex justify-center">
             <button @click="clearCart" class="text-sm text-gray-500 hover:text-red-600 underline">Vaciar carrito</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useCart } from '../../stores/cart.js';

const { state, removeItem, updateQuantity, clearCart, totalItems, totalPrice } = useCart();
</script>
