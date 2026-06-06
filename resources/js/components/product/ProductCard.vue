<template>
  <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden card-hover group flex flex-col h-full">
    <div class="relative aspect-square overflow-hidden bg-gray-100">
      <img v-if="product.image_url" :src="product.image_url" :alt="product.name" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
      <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
      </div>
      
      <!-- Tags -->
      <div class="absolute top-2 left-2 flex flex-col gap-1">
        <span v-if="product.is_featured" class="bg-[#B91C1C] text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm uppercase tracking-wider">Destacado</span>
        <span v-if="product.stock <= 0" class="bg-gray-800 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm uppercase tracking-wider">Agotado</span>
      </div>
    </div>
    
    <div class="p-4 flex flex-col flex-grow">
      <div class="text-xs text-[#D4A574] font-semibold tracking-wider uppercase mb-1">{{ product.category?.name }}</div>
      <h3 class="font-playfair text-lg font-bold text-gray-900 mb-1 leading-tight line-clamp-2">
        <router-link :to="{ name: 'product-detail', params: { slug: product.slug } }" class="hover:text-[#8B1A1A] transition-colors">
          {{ product.name }}
        </router-link>
      </h3>
      <p class="text-sm text-gray-500 mb-4">{{ product.weight }}</p>
      
      <div class="mt-auto flex items-center justify-between">
        <span class="text-xl font-bold text-[#8B1A1A]">Bs. {{ Number(product.price).toFixed(2) }}</span>
        
        <button 
          @click.prevent="addToCart" 
          :disabled="product.stock <= 0"
          class="w-10 h-10 rounded-full flex items-center justify-center transition-all shadow-sm"
          :class="product.stock > 0 ? 'bg-gray-100 text-gray-600 hover:bg-[#B91C1C] hover:text-white hover:shadow-md' : 'bg-gray-100 text-gray-400 cursor-not-allowed'"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useCart } from '../../stores/cart.js';
import { useRouter } from 'vue-router';

const props = defineProps({
    product: { type: Object, required: true }
});

const { addItem } = useCart();
const router = useRouter();

const addToCart = () => {
    if (props.product.stock > 0) {
        addItem(props.product, 1);
        // Opcional: mostrar un toast
    }
};
</script>
