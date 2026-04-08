<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  links: {
    type: Array,
    default: () => [],
  },
});

const visibleLinks = computed(() => props.links.filter((item) => item.label !== '&laquo; Previous' && item.label !== 'Next &raquo;'));
</script>

<template>
  <nav v-if="links.length > 3" aria-label="Paginação de posts" class="mt-4">
    <ul class="pagination flex-wrap gap-1 mb-0">
      <li v-for="(link, index) in visibleLinks" :key="index" class="page-item" :class="{ active: link.active, disabled: !link.url }">
        <Link v-if="link.url" class="page-link" :href="link.url" v-html="link.label" />
        <span v-else class="page-link" v-html="link.label" />
      </li>
    </ul>
  </nav>
</template>
