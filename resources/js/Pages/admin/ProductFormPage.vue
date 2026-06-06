<template>
  <div class="max-w-4xl mx-auto">
    <div class="flex items-center mb-6">
      <router-link :to="{ name: 'admin.products' }" class="text-gray-500 hover:text-gray-900 mr-4">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
      </router-link>
      <h1 class="text-3xl font-playfair font-bold text-gray-900">{{ isEdit ? 'Editar Producto' : 'Nuevo Producto' }}</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <div v-if="loading" class="py-20 flex justify-center">
        <div class="w-10 h-10 border-4 border-gray-200 border-t-[#8B1A1A] rounded-full animate-spin"></div>
      </div>
      <div v-else class="p-6 sm:p-8">
        <form @submit.prevent="handleSubmit" class="space-y-6">
          <div v-if="errorMsg" class="bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
            <p class="text-sm text-red-700">{{ errorMsg }}</p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label for="name" class="form-label">Nombre del producto *</label>
              <input type="text" id="name" v-model="form.name" required class="form-input" :class="{'border-red-500': errors.name}">
              <span v-if="errors.name" class="text-xs text-red-500 mt-1">{{ errors.name[0] }}</span>
            </div>

            <div>
              <label for="category_id" class="form-label">Categoría *</label>
              <select id="category_id" v-model="form.category_id" required class="form-input" :class="{'border-red-500': errors.category_id}">
                <option value="">Seleccione una categoría...</option>
                <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
              </select>
              <span v-if="errors.category_id" class="text-xs text-red-500 mt-1">{{ errors.category_id[0] }}</span>
            </div>

            <div class="md:col-span-2">
              <label for="description" class="form-label">Descripción *</label>
              <textarea id="description" v-model="form.description" required rows="4" class="form-input" :class="{'border-red-500': errors.description}"></textarea>
              <span v-if="errors.description" class="text-xs text-red-500 mt-1">{{ errors.description[0] }}</span>
            </div>

            <div>
              <label for="price" class="form-label">Precio (Bs) *</label>
              <input type="number" id="price" v-model="form.price" required min="0" step="0.1" class="form-input" :class="{'border-red-500': errors.price}">
              <span v-if="errors.price" class="text-xs text-red-500 mt-1">{{ errors.price[0] }}</span>
            </div>

            <div>
              <label for="stock" class="form-label">Stock inicial *</label>
              <input type="number" id="stock" v-model="form.stock" required min="0" class="form-input" :class="{'border-red-500': errors.stock}">
              <span v-if="errors.stock" class="text-xs text-red-500 mt-1">{{ errors.stock[0] }}</span>
            </div>

            <div>
              <label for="weight" class="form-label">Peso / Unidad *</label>
              <input type="text" id="weight" v-model="form.weight" required placeholder="Ej: 500g, 1Kg, Paquete..." class="form-input" :class="{'border-red-500': errors.weight}">
              <span v-if="errors.weight" class="text-xs text-red-500 mt-1">{{ errors.weight[0] }}</span>
            </div>
            
            <div class="md:col-span-2">
              <ImageUpload id="product-image" label="Imagen del Producto" :currentImage="form.image_url" @update:file="f => form.image = f" />
               <span v-if="errors.image" class="text-xs text-red-500 mt-1">{{ errors.image[0] }}</span>
            </div>

            <div class="md:col-span-2 flex items-center gap-6">
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" v-model="form.is_active" class="w-5 h-5 text-[#8B1A1A] rounded focus:ring-[#8B1A1A]">
                <span class="text-sm font-medium text-gray-700">Producto Activo</span>
              </label>

              <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" v-model="form.is_featured" class="w-5 h-5 text-[#8B1A1A] rounded focus:ring-[#8B1A1A]">
                <span class="text-sm font-medium text-gray-700">Destacar en Inicio</span>
              </label>
            </div>
          </div>

          <div class="flex justify-end pt-6 border-t border-gray-100">
            <router-link :to="{ name: 'admin.products' }" class="btn-outline mr-4">Cancelar</router-link>
            <button type="submit" :disabled="saving" class="btn-primary">
              <svg v-if="saving" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
              {{ saving ? 'Guardando...' : 'Guardar Producto' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref, onMounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { getAdminProduct, createProduct, updateProduct } from '../../api/products.js';
import { getAdminCategories } from '../../api/categories.js';
import ImageUpload from '../../components/common/ImageUpload.vue';

const route = useRoute();
const router = useRouter();
const isEdit = computed(() => !!route.params.id);

const categories = ref([]);
const loading = ref(false);
const saving = ref(false);
const errorMsg = ref('');
const errors = ref({});

const form = reactive({
    name: '', category_id: '', description: '', price: '', stock: 0, weight: '', is_active: true, is_featured: false, image: null, image_url: null
});

onMounted(async () => {
    loading.value = true;
    try {
        const catRes = await getAdminCategories({ page: 1, limit: 100 }); // Ajuste para traer todas
        categories.value = catRes.data.data || catRes.data;

        if (isEdit.value) {
            const { data } = await getAdminProduct(route.params.id);
            Object.assign(form, data);
            // reset image file, keep url for preview
            form.image = null; 
        }
    } catch (e) {
        console.error(e);
        errorMsg.value = 'Error al cargar los datos.';
    } finally {
        loading.value = false;
    }
});

const handleSubmit = async () => {
    saving.value = true;
    errorMsg.value = '';
    errors.value = {};

    const formData = new FormData();
    Object.keys(form).forEach(key => {
        if (key === 'image' && form[key]) {
            formData.append('image', form[key]);
        } else if (key !== 'image' && key !== 'image_url' && form[key] !== null) {
            // Convert boolean to 1/0 for FormData
            if (typeof form[key] === 'boolean') {
                 formData.append(key, form[key] ? '1' : '0');
            } else {
                 formData.append(key, form[key]);
            }
        }
    });

    try {
        if (isEdit.value) {
            await updateProduct(route.params.id, formData);
        } else {
            await createProduct(formData);
        }
        router.push({ name: 'admin.products' });
    } catch (error) {
        if (error.response?.status === 422) {
            errors.value = error.response.data.errors;
        } else {
            errorMsg.value = error.response?.data?.message || 'Error al guardar.';
        }
    } finally {
        saving.value = false;
    }
};
</script>
