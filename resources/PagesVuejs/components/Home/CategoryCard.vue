<script setup>
defineProps({
  item: {
    type: Object,
    required: true,
  },
});
</script>

<template>
  <article class="category-card">
    <div class="category-icon">{{ item.icon }}</div>
    <h3>{{ item.title }}</h3>
    <p>{{ item.description }}</p>
    <a href="#">Explorar categoria</a>
  </article>
</template>
