<script setup>
defineProps({
  item: {
    type: Object,
    required: true,
  },
});
</script>

<template>
  <div class="mini-info-card">
    <small>{{ item.label }}</small>
    <strong>{{ item.title }}</strong>
    <p>{{ item.description }}</p>
  </div>
</template>
