<template>
  <div class="max-w-3xl">
    <h1 class="text-3xl font-playfair font-bold text-gray-900 mb-8">Mi Perfil</h1>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="p-6 sm:p-8">
        <form @submit.prevent="saveProfile" class="space-y-6">
          <div v-if="successMsg" class="bg-green-50 text-green-700 p-4 rounded-md border border-green-200">
            {{ successMsg }}
          </div>
          <div v-if="errorMsg" class="bg-red-50 text-red-700 p-4 rounded-md border border-red-200">
            {{ errorMsg }}
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label for="name" class="form-label">Nombre Completo *</label>
              <input type="text" id="name" v-model="form.name" required class="form-input" :class="{'border-red-500': errors.name}">
              <span v-if="errors.name" class="text-xs text-red-500 mt-1">{{ errors.name[0] }}</span>
            </div>
            
            <div>
              <label for="email" class="form-label">Correo Electrónico (Solo lectura)</label>
              <input type="email" :value="form.email" disabled class="form-input bg-gray-50 text-gray-500 cursor-not-allowed">
            </div>

            <div>
              <label for="phone" class="form-label">Teléfono</label>
              <input type="text" id="phone" v-model="form.phone" class="form-input" :class="{'border-red-500': errors.phone}">
              <span v-if="errors.phone" class="text-xs text-red-500 mt-1">{{ errors.phone[0] }}</span>
            </div>

            <div class="md:col-span-2">
              <label for="address" class="form-label">Dirección Principal</label>
              <textarea id="address" v-model="form.address" rows="3" class="form-input" :class="{'border-red-500': errors.address}"></textarea>
              <span v-if="errors.address" class="text-xs text-red-500 mt-1">{{ errors.address[0] }}</span>
            </div>
          </div>

          <div class="border-t border-gray-100 pt-6 mt-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Cambiar Contraseña</h3>
            <p class="text-sm text-gray-500 mb-4">Deja estos campos en blanco si no deseas cambiar tu contraseña.</p>

            <div class="space-y-4 max-w-md">
              <div>
                <label for="current_password" class="form-label">Contraseña Actual</label>
                <input type="password" id="current_password" v-model="form.current_password" class="form-input">
              </div>
              <div>
                <label for="password" class="form-label">Nueva Contraseña</label>
                <input type="password" id="password" v-model="form.password" class="form-input" :class="{'border-red-500': errors.password}">
                 <span v-if="errors.password" class="text-xs text-red-500 mt-1">{{ errors.password[0] }}</span>
              </div>
              <div>
                <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                <input type="password" id="password_confirmation" v-model="form.password_confirmation" class="form-input">
              </div>
            </div>
          </div>

          <div class="flex justify-end pt-4 border-t border-gray-100">
            <button type="submit" :disabled="saving" class="btn-primary">
              <svg v-if="saving" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
              {{ saving ? 'Guardando...' : 'Guardar Cambios' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue';
import { getProfile, updateProfile } from '../../api/users.js';
import { useAuth } from '../../stores/auth.js';

const { setUser } = useAuth();
const form = reactive({
    name: '', email: '', phone: '', address: '',
    current_password: '', password: '', password_confirmation: ''
});

const saving = ref(false);
const successMsg = ref('');
const errorMsg = ref('');
const errors = ref({});

onMounted(async () => {
    try {
        const { data } = await getProfile();
        form.name = data.name;
        form.email = data.email;
        form.phone = data.phone || '';
        form.address = data.address || '';
    } catch (e) {
        console.error(e);
    }
});

const saveProfile = async () => {
    saving.value = true;
    successMsg.value = '';
    errorMsg.value = '';
    errors.value = {};

    try {
        const payload = {
            name: form.name,
            phone: form.phone,
            address: form.address,
        };
        if (form.password) {
            payload.current_password = form.current_password;
            payload.password = form.password;
            payload.password_confirmation = form.password_confirmation;
        }

        const { data } = await updateProfile(payload);
        setUser(data.user); // Actualiza store global
        
        successMsg.value = data.message;
        form.current_password = '';
        form.password = '';
        form.password_confirmation = '';
        
        setTimeout(() => successMsg.value = '', 4000);
    } catch (error) {
        if (error.response?.status === 422) {
            if (error.response.data.errors) {
                errors.value = error.response.data.errors;
            } else {
                errorMsg.value = error.response.data.message;
            }
        } else {
            errorMsg.value = 'Ocurrió un error al guardar el perfil.';
        }
    } finally {
        saving.value = false;
    }
};
</script>
