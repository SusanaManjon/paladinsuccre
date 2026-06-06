<template>
  <div class="min-h-[calc(100vh-80px)] flex bg-white">
    <!-- Image side -->
    <div class="hidden lg:block lg:w-1/2 relative">
      <div class="absolute inset-0 hero-gradient"></div>
      <div class="absolute inset-0 flex items-center justify-center p-12">
        <div class="text-center text-white">
          <h2 class="font-playfair text-4xl font-bold mb-4">Bienvenido a Paladín Sucre</h2>
          <p class="text-lg text-gray-300">Ingresa a tu cuenta para realizar pedidos, guardar tus direcciones y ver tu historial de compras.</p>
        </div>
      </div>
    </div>

    <!-- Form side -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12">
      <div class="w-full max-w-md">
        <div class="text-center mb-10">
          <h2 class="font-playfair text-3xl font-bold text-gray-900 mb-2">Iniciar Sesión</h2>
          <p class="text-gray-500">¿No tienes una cuenta? <router-link :to="{ name: 'register' }" class="text-[#8B1A1A] font-semibold hover:underline">Regístrate aquí</router-link></p>
        </div>

        <form @submit.prevent="handleLogin" class="space-y-6">
          <div v-if="errorMsg" class="bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
            <p class="text-sm text-red-700">{{ errorMsg }}</p>
          </div>

          <div>
            <label for="email" class="form-label">Correo Electrónico</label>
            <input id="email" type="email" v-model="form.email" required class="form-input" placeholder="tu@correo.com">
          </div>

          <div>
            <label for="password" class="form-label">Contraseña</label>
            <input id="password" type="password" v-model="form.password" required class="form-input" placeholder="••••••••">
          </div>

          <button type="submit" :disabled="loading" class="w-full btn-primary py-3 text-lg mt-4">
            <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ loading ? 'Iniciando...' : 'Ingresar' }}
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
import { login } from '../../api/auth.js';

const router = useRouter();
const { fetchUser } = useAuth();

const form = reactive({ email: '', password: '' });
const loading = ref(false);
const errorMsg = ref('');

const handleLogin = async () => {
    loading.value = true;
    errorMsg.value = '';
    
    try {
        const { data } = await login(form);
        // Guardar el token Bearer para futuras peticiones
        if (data.token) {
            localStorage.setItem('auth_token', data.token);
        }
        const { setUser, isAdmin, isClient } = useAuth();
        if (data.user) {
            setUser(data.user, data.token);
        } else {
            await fetchUser();
        }
        if (isAdmin.value) {
            router.push({ name: 'admin.dashboard' });
        } else if (isClient.value) {
            router.push({ name: 'client.dashboard' });
        } else {
            router.push({ name: 'home' });
        }
    } catch (error) {
        errorMsg.value = error.response?.data?.message || 'Error al iniciar sesión. Por favor, intenta de nuevo.';
    } finally {
        loading.value = false;
    }
};
</script>
