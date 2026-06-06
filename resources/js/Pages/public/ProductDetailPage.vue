<template>
  <div class="bg-[#FDF8F4] min-h-screen py-10">
    <div v-if="loading" class="flex justify-center py-20">
      <div class="w-12 h-12 border-4 border-gray-200 border-t-[#8B1A1A] rounded-full animate-spin"></div>
    </div>
    
    <div v-else-if="error" class="max-w-7xl mx-auto px-4 text-center py-20">
      <h2 class="text-2xl font-bold text-gray-900 mb-4">Producto no encontrado</h2>
      <router-link :to="{ name: 'catalog' }" class="btn-primary">Volver al catálogo</router-link>
    </div>

    <div v-else-if="product" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Breadcrumbs -->
      <nav class="flex mb-8 text-sm text-gray-500">
        <router-link :to="{ name: 'home' }" class="hover:text-[#8B1A1A]">Inicio</router-link>
        <span class="mx-2">/</span>
        <router-link :to="{ name: 'catalog' }" class="hover:text-[#8B1A1A]">Catálogo</router-link>
        <span class="mx-2">/</span>
        <router-link v-if="product.category" :to="{ name: 'catalog', query: { category_slug: product.category.slug } }" class="hover:text-[#8B1A1A]">{{ product.category.name }}</router-link>
        <span v-if="product.category" class="mx-2">/</span>
        <span class="text-gray-900 font-medium truncate">{{ product.name }}</span>
      </nav>

      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-16">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-0">
          
          <!-- Image -->
          <div class="relative bg-gray-50 aspect-square md:aspect-auto md:h-full min-h-[400px]">
             <img v-if="product.image_url" :src="product.image_url" :alt="product.name" class="absolute inset-0 w-full h-full object-cover">
             <div v-else class="absolute inset-0 flex items-center justify-center text-gray-300">
               <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
             </div>
             
             <div class="absolute top-4 left-4 flex flex-col gap-2">
                <span v-if="product.is_featured" class="bg-[#B91C1C] text-white text-xs font-bold px-3 py-1.5 rounded shadow-sm uppercase tracking-wider">Destacado</span>
             </div>
          </div>

          <!-- Info -->
          <div class="p-8 md:p-12 flex flex-col">
            <div class="text-sm text-[#D4A574] font-bold tracking-widest uppercase mb-2">{{ product.category?.name }}</div>
            <h1 class="font-playfair text-3xl md:text-5xl font-bold text-gray-900 mb-4">{{ product.name }}</h1>
            
            <div class="text-3xl font-bold text-[#8B1A1A] mb-6">Bs. {{ Number(product.price).toFixed(2) }}</div>
            
            <div class="prose prose-sm text-gray-600 mb-8">
              <p>{{ product.description }}</p>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-8 py-6 border-y border-gray-100">
              <div>
                <span class="block text-xs text-gray-500 uppercase font-semibold mb-1">Peso / Unidad</span>
                <span class="font-medium text-gray-900">{{ product.weight }}</span>
              </div>
              <div>
                <span class="block text-xs text-gray-500 uppercase font-semibold mb-1">Disponibilidad</span>
                <span v-if="product.stock > 0" class="font-medium text-green-600">En stock ({{ product.stock }})</span>
                <span v-else class="font-medium text-red-600">Agotado</span>
              </div>
            </div>

            <!-- Actions -->
            <div class="mt-auto flex items-end gap-4">
              <div class="w-24">
                <label class="block text-xs text-gray-500 uppercase font-semibold mb-1">Cantidad</label>
                <input type="number" v-model="quantity" min="1" :max="product.stock" class="form-input text-center h-12 text-lg font-bold" :disabled="product.stock <= 0">
              </div>
              <button @click="handleAddToCart" :disabled="product.stock <= 0" class="flex-1 btn-primary h-12 text-lg shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Agregar al Carrito
              </button>
            </div>
            <p v-if="addedMsg" class="mt-3 text-sm text-green-600 font-medium text-center animate-fadeInUp">¡Producto agregado al carrito!</p>
          </div>
        </div>
      </div>

      <!-- Related -->
      <div v-if="related.length > 0" class="mt-16">
        <h2 class="font-playfair text-2xl md:text-3xl font-bold text-gray-900 mb-8 border-b pb-4">Productos Similares</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <ProductCard v-for="rel in related" :key="rel.id" :product="rel" />
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import { getProduct } from '../../api/products.js';
import { useCart } from '../../stores/cart.js';
import ProductCard from '../../components/product/ProductCard.vue';

const route = useRoute();
const { addItem } = useCart();

const product = ref(null);
const related = ref([]);
const loading = ref(true);
const error = ref(false);
const quantity = ref(1);
const addedMsg = ref(false);

const fetchProductData = async (slug) => {
    loading.value = true;
    error.value = false;
    quantity.value = 1;
    addedMsg.value = false;
    
    try {
        const { data } = await getProduct(slug);
        product.value = data;
        related.value = data.related || [];
    } catch (e) {
        error.value = true;
    } finally {
        loading.value = false;
    }
};

const handleAddToCart = () => {
    if (product.value.stock > 0 && quantity.value > 0) {
        addItem(product.value, Number(quantity.value));
        addedMsg.value = true;
        setTimeout(() => addedMsg.value = false, 3000);
    }
};

onMounted(() => fetchProductData(route.params.slug));

watch(() => route.params.slug, (newSlug) => {
    if (newSlug) fetchProductData(newSlug);
});
</script>
