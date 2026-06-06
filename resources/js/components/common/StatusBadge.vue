<template>
  <span :class="['badge', badgeClass]">
    {{ label }}
  </span>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    status: { type: String, required: true }
});

const statusMap = {
    'pending': { class: 'badge-pending', label: 'Pendiente' },
    'confirmed': { class: 'badge-confirmed', label: 'Confirmado' },
    'processing': { class: 'badge-processing', label: 'En Proceso' },
    'delivered': { class: 'badge-delivered', label: 'Entregado' },
    'cancelled': { class: 'badge-cancelled', label: 'Cancelado' }
};

const currentInfo = computed(() => statusMap[props.status] || { class: 'bg-gray-100 text-gray-800', label: props.status });
const badgeClass = computed(() => currentInfo.value.class);
const label = computed(() => currentInfo.value.label);
</script>
