<script setup>
defineProps({
  item: {
    type: Object,
    required: true,
  },
});
</script>

<template>
  <article class="faq-card">
    <h3>{{ item.question }}</h3>
    <p>{{ item.answer }}</p>
  </article>
</template>
