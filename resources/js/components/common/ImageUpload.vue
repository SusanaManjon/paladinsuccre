<template>
  <div>
    <label class="block text-sm font-medium text-gray-700 mb-2">{{ label }}</label>
    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-[#8B1A1A] transition-colors relative" :class="{'bg-gray-50': isDragging}" @dragover.prevent="isDragging = true" @dragleave.prevent="isDragging = false" @drop.prevent="handleDrop">
      <div class="space-y-1 text-center">
        <svg v-if="!preview" class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
          <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        <img v-else :src="preview" class="mx-auto max-h-48 rounded object-cover mb-4" />
        
        <div class="flex text-sm text-gray-600 justify-center">
          <label :for="id" class="relative cursor-pointer bg-white rounded-md font-medium text-[#8B1A1A] hover:text-[#B91C1C] focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-[#8B1A1A]">
            <span>{{ preview ? 'Cambiar imagen' : 'Sube un archivo' }}</span>
            <input :id="id" name="file-upload" type="file" class="sr-only" accept="image/*" @change="handleFileChange">
          </label>
          <p v-if="!preview" class="pl-1">o arrastra y suelta</p>
        </div>
        <p class="text-xs text-gray-500">PNG, JPG, WEBP hasta 2MB</p>
      </div>
      <button v-if="preview" @click.prevent="clearImage" class="absolute top-2 right-2 bg-red-100 text-red-600 rounded-full p-1 hover:bg-red-200">
         <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    id: { type: String, required: true },
    label: { type: String, default: 'Imagen' },
    currentImage: { type: String, default: null }
});

const emit = defineEmits(['update:file']);

const isDragging = ref(false);
const preview = ref(props.currentImage);

watch(() => props.currentImage, (newVal) => {
    if (!newVal || typeof newVal === 'string') {
        preview.value = newVal;
    }
});

const handleFile = (file) => {
    if (!file || !file.type.startsWith('image/')) return;
    
    emit('update:file', file);
    
    const reader = new FileReader();
    reader.onload = (e) => {
        preview.value = e.target.result;
    };
    reader.readAsDataURL(file);
};

const handleFileChange = (e) => {
    const file = e.target.files[0];
    handleFile(file);
};

const handleDrop = (e) => {
    isDragging.value = false;
    const file = e.dataTransfer.files[0];
    handleFile(file);
};

const clearImage = () => {
    preview.value = null;
    emit('update:file', null);
};
</script>
