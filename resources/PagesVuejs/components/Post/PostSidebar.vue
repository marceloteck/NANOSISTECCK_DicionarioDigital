<script setup>
import PostInfoCard from './PostInfoCard.vue';

defineProps({
  cards: {
    type: Array,
    required: true,
  },
});
</script>

<template>
  <aside class="system-sidebar">
    <PostInfoCard v-for="card in cards" :key="card.title" :item="card" />
  </aside>
</template>
