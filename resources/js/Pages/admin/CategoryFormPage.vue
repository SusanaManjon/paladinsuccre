<template>
  <div class="max-w-4xl mx-auto">
    <div class="flex items-center mb-6">
      <router-link :to="{ name: 'admin.categories' }" class="text-gray-500 hover:text-gray-900 mr-4">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
      </router-link>
      <h1 class="text-3xl font-playfair font-bold text-gray-900">{{ isEdit ? 'Editar Categoría' : 'Nueva Categoría' }}</h1>
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
            <div class="md:col-span-2">
              <label for="name" class="form-label">Nombre de la categoría *</label>
              <input type="text" id="name" v-model="form.name" required class="form-input" :class="{'border-red-500': errors.name}">
              <span v-if="errors.name" class="text-xs text-red-500 mt-1">{{ errors.name[0] }}</span>
            </div>

            <div class="md:col-span-2">
              <label for="description" class="form-label">Descripción</label>
              <textarea id="description" v-model="form.description" rows="3" class="form-input" :class="{'border-red-500': errors.description}"></textarea>
              <span v-if="errors.description" class="text-xs text-red-500 mt-1">{{ errors.description[0] }}</span>
            </div>
            
            <div class="md:col-span-2">
              <ImageUpload id="category-image" label="Imagen de la Categoría" :currentImage="form.image_url" @update:file="f => form.image = f" />
               <span v-if="errors.image" class="text-xs text-red-500 mt-1">{{ errors.image[0] }}</span>
            </div>

            <div class="md:col-span-2 flex items-center gap-6">
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" v-model="form.is_active" class="w-5 h-5 text-[#8B1A1A] rounded focus:ring-[#8B1A1A]">
                <span class="text-sm font-medium text-gray-700">Categoría Activa</span>
              </label>
            </div>
          </div>

          <div class="flex justify-end pt-6 border-t border-gray-100">
            <router-link :to="{ name: 'admin.categories' }" class="btn-outline mr-4">Cancelar</router-link>
            <button type="submit" :disabled="saving" class="btn-primary">
              <svg v-if="saving" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
              {{ saving ? 'Guardando...' : 'Guardar Categoría' }}
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
import { getAdminCategory, createCategory, updateCategory } from '../../api/categories.js';
import ImageUpload from '../../components/common/ImageUpload.vue';

const route = useRoute();
const router = useRouter();
const isEdit = computed(() => !!route.params.id);

const loading = ref(false);
const saving = ref(false);
const errorMsg = ref('');
const errors = ref({});

const form = reactive({
    name: '', description: '', is_active: true, image: null, image_url: null
});

onMounted(async () => {
    if (isEdit.value) {
        loading.value = true;
        try {
            const { data } = await getAdminCategory(route.params.id);
            Object.assign(form, data);
            form.image = null; 
        } catch (e) {
            console.error(e);
            errorMsg.value = 'Error al cargar los datos.';
        } finally {
            loading.value = false;
        }
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
            if (typeof form[key] === 'boolean') {
                 formData.append(key, form[key] ? '1' : '0');
            } else {
                 formData.append(key, form[key]);
            }
        }
    });

    try {
        if (isEdit.value) {
            await updateCategory(route.params.id, formData);
        } else {
            await createCategory(formData);
        }
        router.push({ name: 'admin.categories' });
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
