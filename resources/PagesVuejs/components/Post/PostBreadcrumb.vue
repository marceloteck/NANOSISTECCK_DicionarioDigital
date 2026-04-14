<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  items: {
    type: Array,
    default: () => [],
  },
});
</script>

<template>
  <div class="breadcrumb" v-if="items.length">
    <template v-for="(item, index) in items" :key="`${item.name}-${index}`">
      <span v-if="index < items.length - 1"><Link :href="item.url">{{ item.name }}</Link></span>
      <span v-else>{{ item.name }}</span>
      <span v-if="index < items.length - 1">›</span>
    </template>
  </div>
</template>
