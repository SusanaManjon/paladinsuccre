<template>
  <div class="min-h-[calc(100vh-80px)] flex bg-white">
    <!-- Image side -->
    <div class="hidden lg:block lg:w-1/2 relative">
      <div class="absolute inset-0 bg-[#1a0a00]"></div>
      <div class="absolute inset-0 opacity-10 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjIiIGZpbGw9IiNmZmYiLz48L3N2Zz4=')]"></div>
      <div class="absolute inset-0 flex items-center justify-center p-12">
        <div class="text-center text-white">
          <h2 class="font-playfair text-4xl font-bold mb-4">Únete a la Familia</h2>
          <p class="text-lg text-gray-300">Descubre el auténtico sabor de los embutidos artesanales.</p>
        </div>
      </div>
    </div>

    <!-- Form side -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 overflow-y-auto max-h-[calc(100vh-80px)]">
      <div class="w-full max-w-md py-8">
        <div class="text-center mb-8">
          <h2 class="font-playfair text-3xl font-bold text-gray-900 mb-2">Crear Cuenta</h2>
          <p class="text-gray-500">¿Ya tienes cuenta? <router-link :to="{ name: 'login' }" class="text-[#8B1A1A] font-semibold hover:underline">Ingresa aquí</router-link></p>
        </div>

        <form @submit.prevent="handleRegister" class="space-y-5">
          <div v-if="errorMsg" class="bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
            <p class="text-sm text-red-700">{{ errorMsg }}</p>
          </div>

          <div>
            <label for="name" class="form-label">Nombre Completo *</label>
            <input id="name" type="text" v-model="form.name" required class="form-input" :class="{'border-red-500': errors.name}">
            <span v-if="errors.name" class="text-xs text-red-500 mt-1">{{ errors.name[0] }}</span>
          </div>

          <div>
            <label for="email" class="form-label">Correo Electrónico *</label>
            <input id="email" type="email" v-model="form.email" required class="form-input" :class="{'border-red-500': errors.email}">
            <span v-if="errors.email" class="text-xs text-red-500 mt-1">{{ errors.email[0] }}</span>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label for="password" class="form-label">Contraseña *</label>
              <input id="password" type="password" v-model="form.password" required class="form-input" :class="{'border-red-500': errors.password}">
            </div>
            <div>
              <label for="password_confirmation" class="form-label">Confirmar *</label>
              <input id="password_confirmation" type="password" v-model="form.password_confirmation" required class="form-input">
            </div>
          </div>
          <span v-if="errors.password" class="text-xs text-red-500 mt-1 block">{{ errors.password[0] }}</span>

          <div>
            <label for="phone" class="form-label">Teléfono</label>
            <input id="phone" type="text" v-model="form.phone" class="form-input">
          </div>

          <div>
            <label for="address" class="form-label">Dirección</label>
            <input id="address" type="text" v-model="form.address" class="form-input">
          </div>

          <button type="submit" :disabled="loading" class="w-full btn-primary py-3 text-lg mt-6">
            <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ loading ? 'Registrando...' : 'Registrarse' }}
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuth } from '../../stores/auth.js';
import { register } from '../../api/auth.js';

const router = useRouter();
const { fetchUser } = useAuth();

const form = reactive({
    name: '', email: '', password: '', password_confirmation: '', phone: '', address: ''
});
const loading = ref(false);
const errorMsg = ref('');
const errors = ref({});

const handleRegister = async () => {
    loading.value = true;
    errorMsg.value = '';
    errors.value = {};
    
    try {
        await register(form);
        await fetchUser();
        const { isAdmin, isClient } = useAuth();
        if (isAdmin.value) {
            router.push({ name: 'admin.dashboard' });
        } else if (isClient.value) {
            router.push({ name: 'client.dashboard' });
        } else {
            router.push({ name: 'home' });
        }
    } catch (error) {
        if (error.response?.status === 422) {
            errors.value = error.response.data.errors;
        } else {
            errorMsg.value = error.response?.data?.message || 'Error al registrarse.';
        }
    } finally {
        loading.value = false;
    }
};
</script>
