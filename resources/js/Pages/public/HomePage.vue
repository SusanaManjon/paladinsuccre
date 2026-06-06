<template>
  <div>
    <!-- Hero Section -->
    <section class="relative h-[80vh] min-h-[600px] flex items-center justify-center overflow-hidden">
      <!-- Background Video/Image (Using a dark gradient for now as placeholder) -->
      <div class="absolute inset-0 hero-gradient">
        <!-- Textura/Patrón sutil sobre el gradiente -->
        <div class="absolute inset-0 opacity-10 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjIiIGZpbGw9IiNmZmYiLz48L3N2Zz4=')]"></div>
      </div>
      
      <div class="relative z-10 text-center px-4 max-w-4xl mx-auto">
        <span class="block text-[#D4A574] font-semibold tracking-[0.2em] uppercase text-sm mb-4 animate-fadeInUp">Tradición Chuquisaqueña</span>
        <h1 class="font-playfair text-5xl md:text-7xl font-bold text-white mb-6 leading-tight animate-fadeInUp" style="animation-delay: 0.1s">
          El Auténtico Sabor<br />de los Embutidos
        </h1>
        <p class="text-lg md:text-xl text-gray-300 mb-10 max-w-2xl mx-auto animate-fadeInUp" style="animation-delay: 0.2s">
          Elaborados con las mejores carnes y especias naturales. Calidad y sabor inigualable en cada bocado desde 2009.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center animate-fadeInUp" style="animation-delay: 0.3s">
          <router-link :to="{ name: 'catalog' }" class="btn-primary text-lg px-8 py-4">Ver Catálogo</router-link>
          <a href="#destacados" class="btn-outline border-white text-white hover:bg-white hover:text-[#1a0a00] text-lg px-8 py-4">Productos Destacados</a>
        </div>
      </div>
    </section>

    <!-- Loader general -->
    <div v-if="loading" class="py-20 flex justify-center">
      <div class="w-12 h-12 border-4 border-gray-200 border-t-[#8B1A1A] rounded-full animate-spin"></div>
    </div>

    <template v-else>
      <!-- Categorías Section -->
      <section class="py-20 bg-white" v-if="categories.length">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="text-center mb-12">
            <h2 class="font-playfair text-3xl md:text-4xl font-bold text-gray-900 mb-4">Nuestras Especialidades</h2>
            <div class="w-24 h-1 bg-[#8B1A1A] mx-auto"></div>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <router-link :to="{ name: 'catalog', query: { category_slug: cat.slug } }" v-for="(cat, index) in categories.slice(0, 3)" :key="cat.id" class="group block relative overflow-hidden rounded-2xl aspect-[4/3] shadow-lg card-hover" :class="index === 0 ? 'md:col-span-2 lg:col-span-1 lg:row-span-2 aspect-auto' : ''">
              <img v-if="cat.image_url" :src="cat.image_url" :alt="cat.name" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
              <div v-else class="absolute inset-0 bg-gradient-to-br from-gray-700 to-gray-900"></div>
              
              <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
              <div class="absolute bottom-0 left-0 p-6">
                <h3 class="font-playfair text-2xl font-bold text-white mb-2">{{ cat.name }}</h3>
                <p class="text-gray-300 text-sm mb-4 line-clamp-2" :class="index === 0 ? 'md:max-w-md' : ''">{{ cat.description }}</p>
                <span class="inline-flex items-center text-[#D4A574] font-medium group-hover:text-white transition-colors">
                  Explorar <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </span>
              </div>
            </router-link>
          </div>
          
          <div class="mt-12 text-center">
            <router-link :to="{ name: 'catalog' }" class="inline-flex items-center text-[#8B1A1A] font-semibold hover:text-[#B91C1C] transition-colors">
              Ver todas las categorías <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </router-link>
          </div>
        </div>
      </section>

      <!-- Productos Destacados Section -->
      <section id="destacados" class="py-20 bg-[#FDF8F4]" v-if="featuredProducts.length">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
            <div>
              <span class="text-[#8B1A1A] font-semibold tracking-wider uppercase text-sm block mb-2">Selección Premium</span>
              <h2 class="font-playfair text-3xl md:text-4xl font-bold text-gray-900">Productos Destacados</h2>
            </div>
            <router-link :to="{ name: 'catalog' }" class="hidden md:inline-flex btn-secondary mt-4 md:mt-0">
              Ver Catálogo Completo
            </router-link>
          </div>
          
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <ProductCard v-for="product in featuredProducts" :key="product.id" :product="product" />
          </div>
          
          <div class="mt-10 text-center md:hidden">
             <router-link :to="{ name: 'catalog' }" class="btn-secondary w-full">
              Ver Catálogo Completo
            </router-link>
          </div>
        </div>
      </section>
    </template>

    <!-- CTA Section -->
    <section class="py-24 bg-[#1a0a00] relative overflow-hidden">
      <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 rounded-full bg-[#B91C1C] opacity-20 blur-3xl"></div>
      <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 rounded-full bg-[#D4A574] opacity-20 blur-3xl"></div>
      
      <div class="relative z-10 max-w-4xl mx-auto px-4 text-center">
        <h2 class="font-playfair text-3xl md:text-5xl font-bold text-white mb-6">¿Listo para probar el mejor sabor?</h2>
        <p class="text-gray-300 text-lg mb-10">Crea tu cuenta, haz tu pedido y recíbelo en la comodidad de tu hogar. ¡Es así de fácil!</p>
        <router-link v-if="!isAuthenticated" :to="{ name: 'register' }" class="btn-primary text-lg px-8 py-4">Regístrate Ahora</router-link>
        <router-link v-else :to="{ name: 'catalog' }" class="btn-primary text-lg px-8 py-4">Realizar Pedido</router-link>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { getHomeData } from '../../api/users.js';
import { useAuth } from '../../stores/auth.js';
import ProductCard from '../../components/product/ProductCard.vue';

const { isAuthenticated } = useAuth();

const featuredProducts = ref([]);
const categories = ref([]);
const loading = ref(true);

const fetchHomeData = async () => {
    loading.value = true;
    try {
        const { data } = await getHomeData();
        featuredProducts.value = data.featured_products;
        categories.value = data.categories;
    } catch (error) {
        console.error('Error fetching home data:', error);
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchHomeData();
});
</script>
