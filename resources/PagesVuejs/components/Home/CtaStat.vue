<script setup>
defineProps({
  item: {
    type: Object,
    required: true,
  },
});
</script>

<template>
  <div class="cta-stat">
    <strong>{{ item.title }}</strong>
    <span>{{ item.description }}</span>
  </div>
</template>
