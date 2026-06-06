<template>
  <div class="bg-[#FDF8F4] min-h-screen pb-20">
    <!-- Header banner -->
    <div class="bg-[#1a0a00] py-16 relative overflow-hidden">
      <div class="absolute inset-0 opacity-10 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjIiIGZpbGw9IiNmZmYiLz48L3N2Zz4=')]"></div>
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <h1 class="font-playfair text-4xl md:text-5xl font-bold text-white mb-4">Catálogo de Productos</h1>
        <p class="text-gray-400 max-w-2xl mx-auto">Encuentra los mejores embutidos artesanales para tu mesa.</p>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20">
      <div class="flex flex-col md:flex-row gap-8">
        
        <!-- Filters Sidebar -->
        <div class="w-full md:w-64 shrink-0">
          <div class="bg-white rounded-xl shadow-sm p-6 sticky top-24 border border-gray-100">
            <h3 class="font-playfair text-lg font-bold text-gray-900 mb-4 border-b pb-2">Filtros</h3>
            
            <!-- Búsqueda -->
            <div class="mb-6">
              <label class="form-label">Buscar producto</label>
              <div class="relative">
                <input type="text" v-model="filters.search" @input="debouncedFetch" placeholder="Ej: Chorizo..." class="form-input pl-10">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
              </div>
            </div>

            <!-- Categorías -->
            <div class="mb-6">
              <label class="form-label">Categorías</label>
              <div class="space-y-2 max-h-48 overflow-y-auto pr-2">
                <label class="flex items-center gap-2 cursor-pointer">
                  <input type="radio" v-model="filters.category_slug" value="" @change="fetchProducts(1)" class="text-[#8B1A1A] focus:ring-[#8B1A1A]">
                  <span class="text-sm text-gray-700">Todas</span>
                </label>
                <label v-for="cat in categories" :key="cat.id" class="flex items-center justify-between cursor-pointer group">
                  <div class="flex items-center gap-2">
                    <input type="radio" v-model="filters.category_slug" :value="cat.slug" @change="fetchProducts(1)" class="text-[#8B1A1A] focus:ring-[#8B1A1A]">
                    <span class="text-sm text-gray-700 group-hover:text-[#8B1A1A] transition-colors">{{ cat.name }}</span>
                  </div>
                  <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">{{ cat.products_count }}</span>
                </label>
              </div>
            </div>

            <button @click="resetFilters" class="w-full btn-outline text-sm py-2">Limpiar Filtros</button>
          </div>
        </div>

        <!-- Products Grid -->
        <div class="flex-1">
          <!-- Active filters info -->
          <div class="flex items-center justify-between mb-6 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
             <div class="text-sm text-gray-600">
                <span v-if="loading">Cargando...</span>
                <span v-else>Mostrando <strong class="text-gray-900">{{ meta?.total || 0 }}</strong> productos</span>
             </div>
          </div>

          <div v-if="loading" class="py-20 flex justify-center">
            <div class="w-10 h-10 border-4 border-gray-200 border-t-[#8B1A1A] rounded-full animate-spin"></div>
          </div>
          
          <div v-else-if="products.length === 0" class="bg-white p-12 rounded-xl text-center shadow-sm border border-gray-100">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">No se encontraron productos</h3>
            <p class="text-gray-500 mb-4">Intenta ajustar los filtros de búsqueda.</p>
            <button @click="resetFilters" class="btn-primary">Ver todos los productos</button>
          </div>

          <div v-else>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
              <ProductCard v-for="product in products" :key="product.id" :product="product" />
            </div>
            
            <div class="mt-8">
              <Pagination :meta="meta" @page-changed="fetchProducts" />
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { getProducts } from '../../api/products.js';
import { getCategories } from '../../api/categories.js';
import ProductCard from '../../components/product/ProductCard.vue';
import Pagination from '../../components/common/Pagination.vue';

const route = useRoute();
const router = useRouter();

const products = ref([]);
const categories = ref([]);
const meta = ref(null);
const loading = ref(true);

const filters = reactive({
    search: '',
    category_slug: route.query.category_slug || '',
});

// Debounce for search
let timeout;
const debouncedFetch = () => {
    clearTimeout(timeout);
    timeout = setTimeout(() => fetchProducts(1), 500);
};

const fetchCategories = async () => {
    try {
        const { data } = await getCategories();
        categories.value = data;
    } catch (error) {
        console.error(error);
    }
};

const fetchProducts = async (page = 1) => {
    loading.value = true;
    try {
        // Sync url
        const query = {};
        if (filters.category_slug) query.category_slug = filters.category_slug;
        router.replace({ query });

        const params = {
            page,
            search: filters.search,
            category_slug: filters.category_slug
        };
        
        const { data } = await getProducts(params);
        products.value = data.data;
        meta.value = data.meta;
    } catch (error) {
        console.error(error);
    } finally {
        loading.value = false;
    }
};

const resetFilters = () => {
    filters.search = '';
    filters.category_slug = '';
    fetchProducts(1);
};

onMounted(() => {
    fetchCategories();
    fetchProducts();
});
</script>
