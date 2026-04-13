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
    <strong>{{ item.title }}</strong>
    <p>{{ item.description }}</p>
  </div>
</template>
