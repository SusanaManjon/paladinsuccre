<template>
  <div>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
      <div class="relative w-full sm:w-96">
        <input type="text" v-model="search" @input="debouncedFetch" placeholder="Buscar categorías..." class="form-input pl-10 w-full">
        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
      </div>
      <router-link :to="{ name: 'admin.categories.create' }" class="btn-primary shrink-0">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Nueva Categoría
      </router-link>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <div v-if="loading" class="py-20 flex justify-center">
        <div class="w-10 h-10 border-4 border-gray-200 border-t-[#8B1A1A] rounded-full animate-spin"></div>
      </div>
      
      <div v-else-if="categories.length === 0" class="text-center py-20 text-gray-500">
        No se encontraron categorías.
      </div>

      <div v-else class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Categoría</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Productos</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Estado</th>
              <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="cat in categories" :key="cat.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="h-10 w-10 shrink-0 bg-gray-100 rounded-md overflow-hidden">
                    <img v-if="cat.image_url" :src="cat.image_url" class="h-10 w-10 object-cover">
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ cat.name }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                <span class="bg-gray-100 text-gray-800 py-1 px-2 rounded-full text-xs font-medium">{{ cat.products_count }}</span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span v-if="cat.is_active" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Activo</span>
                <span v-else class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Inactivo</span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <router-link :to="{ name: 'admin.categories.edit', params: { id: cat.id } }" class="text-indigo-600 hover:text-indigo-900 mr-4">Editar</router-link>
                <button @click="confirmDelete(cat)" class="text-red-600 hover:text-red-900">Eliminar</button>
              </td>
            </tr>
          </tbody>
        </table>
        <div class="p-4 border-t border-gray-200">
          <Pagination :meta="meta" @page-changed="fetchCategories" />
        </div>
      </div>
    </div>

    <!-- Confirm Modal -->
    <Modal :show="showDeleteModal" title="Confirmar Eliminación" @close="showDeleteModal = false">
      <div class="mb-4 text-gray-600">
        ¿Estás seguro de que deseas eliminar la categoría <strong>{{ catToDelete?.name }}</strong>? Se eliminarán todos los productos asociados.
      </div>
      <template #footer>
        <button type="button" @click="showDeleteModal = false" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancelar</button>
        <button type="button" @click="handleDelete" :disabled="deleting" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:w-auto sm:text-sm">
          {{ deleting ? 'Eliminando...' : 'Sí, eliminar' }}
        </button>
      </template>
    </Modal>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { getAdminCategories, deleteCategory } from '../../api/categories.js';
import Pagination from '../../components/common/Pagination.vue';
import Modal from '../../components/common/Modal.vue';

const categories = ref([]);
const meta = ref(null);
const loading = ref(true);
const search = ref('');

const showDeleteModal = ref(false);
const catToDelete = ref(null);
const deleting = ref(false);

let timeout;
const debouncedFetch = () => {
    clearTimeout(timeout);
    timeout = setTimeout(() => fetchCategories(1), 500);
};

const fetchCategories = async (page = 1) => {
    loading.value = true;
    try {
        const { data } = await getAdminCategories({ page, search: search.value });
        categories.value = data.data;
        meta.value = data.meta;
    } catch (e) {
        console.error(e);
    } finally {
        loading.value = false;
    }
};

const confirmDelete = (cat) => {
    catToDelete.value = cat;
    showDeleteModal.value = true;
};

const handleDelete = async () => {
    deleting.value = true;
    try {
        await deleteCategory(catToDelete.value.id);
        showDeleteModal.value = false;
        fetchCategories(meta.value?.current_page || 1);
    } catch (e) {
        console.error(e);
    } finally {
        deleting.value = false;
    }
};

onMounted(() => fetchCategories());
</script>
