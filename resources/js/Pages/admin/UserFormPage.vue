<template>
  <div class="max-w-2xl mx-auto">
    <div class="flex items-center mb-6">
      <router-link :to="{ name: 'admin.users' }" class="text-gray-500 hover:text-gray-900 mr-4">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
      </router-link>
      <h1 class="text-3xl font-playfair font-bold text-gray-900">Registrar Cliente Manualmente</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="p-6 sm:p-8">
        <form @submit.prevent="handleSubmit" class="space-y-6">
          <div v-if="errorMsg" class="bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
            <p class="text-sm text-red-700">{{ errorMsg }}</p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
              <label for="name" class="form-label">Nombre Completo *</label>
              <input type="text" id="name" v-model="form.name" required class="form-input" :class="{'border-red-500': errors.name}">
              <span v-if="errors.name" class="text-xs text-red-500 mt-1">{{ errors.name[0] }}</span>
            </div>

            <div>
              <label for="email" class="form-label">Correo Electrónico *</label>
              <input type="email" id="email" v-model="form.email" required class="form-input" :class="{'border-red-500': errors.email}">
              <span v-if="errors.email" class="text-xs text-red-500 mt-1">{{ errors.email[0] }}</span>
            </div>
            
            <div>
              <label for="phone" class="form-label">Teléfono</label>
              <input type="text" id="phone" v-model="form.phone" class="form-input" :class="{'border-red-500': errors.phone}">
              <span v-if="errors.phone" class="text-xs text-red-500 mt-1">{{ errors.phone[0] }}</span>
            </div>

            <div class="md:col-span-2">
              <label for="password" class="form-label">Contraseña (Mínimo 8 caracteres) *</label>
              <input type="password" id="password" v-model="form.password" required class="form-input" :class="{'border-red-500': errors.password}">
              <span v-if="errors.password" class="text-xs text-red-500 mt-1">{{ errors.password[0] }}</span>
            </div>

            <div class="md:col-span-2">
              <label for="address" class="form-label">Dirección (Opcional)</label>
              <textarea id="address" v-model="form.address" rows="3" class="form-input" :class="{'border-red-500': errors.address}"></textarea>
              <span v-if="errors.address" class="text-xs text-red-500 mt-1">{{ errors.address[0] }}</span>
            </div>
          </div>

          <div class="flex justify-end pt-6 border-t border-gray-100">
            <router-link :to="{ name: 'admin.users' }" class="btn-outline mr-4">Cancelar</router-link>
            <button type="submit" :disabled="saving" class="btn-primary">
              <svg v-if="saving" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
              {{ saving ? 'Registrando...' : 'Registrar Cliente' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { createUser } from '../../api/users.js';

const router = useRouter();
const saving = ref(false);
const errorMsg = ref('');
const errors = ref({});

const form = reactive({
    name: '', email: '', password: '', phone: '', address: ''
});

const handleSubmit = async () => {
    saving.value = true;
    errorMsg.value = '';
    errors.value = {};

    try {
        await createUser(form);
        router.push({ name: 'admin.users' });
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
