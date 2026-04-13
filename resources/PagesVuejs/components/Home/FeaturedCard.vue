<script setup>
defineProps({
  item: {
    type: Object,
    required: true,
  },
});
</script>

<template>
  <article class="featured-card">
    <small>{{ item.category }}</small>
    <h3>{{ item.title }}</h3>
    <p>{{ item.description }}</p>
    <a href="#">{{ item.action }}</a>
  </article>
</template>
