<template>
  <transition name="modal">
    <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true" @click="$emit('close')"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal panel -->
        <div class="relative z-10 inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle" :class="maxWidthClass">
          <!-- Header -->
          <div class="bg-gray-50 px-4 py-4 sm:px-6 flex justify-between items-center border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900 font-playfair" id="modal-title">
              {{ title }}
            </h3>
            <button @click="$emit('close')" type="button" class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#8B1A1A]">
              <span class="sr-only">Cerrar</span>
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <!-- Content -->
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
            <slot></slot>
          </div>

          <!-- Footer -->
          <div v-if="$slots.footer" class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200">
            <slot name="footer"></slot>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    show: { type: Boolean, default: false },
    title: { type: String, required: true },
    maxWidth: { type: String, default: '2xl' }
});

defineEmits(['close']);

const maxWidthClass = computed(() => {
    const maxWs = {
        'sm': 'sm:max-w-sm w-full',
        'md': 'sm:max-w-md w-full',
        'lg': 'sm:max-w-lg w-full',
        'xl': 'sm:max-w-xl w-full',
        '2xl': 'sm:max-w-2xl w-full',
        '3xl': 'sm:max-w-3xl w-full',
        '4xl': 'sm:max-w-4xl w-full',
        '5xl': 'sm:max-w-5xl w-full',
    };
    return maxWs[props.maxWidth] || 'sm:max-w-2xl w-full';
});
</script>

<style scoped>
.modal-enter-active, .modal-leave-active {
  transition: all 0.3s ease;
}
.modal-enter-from, .modal-leave-to {
  opacity: 0;
  transform: scale(0.95);
}
</style>
