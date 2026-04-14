<script setup>
defineProps({
  text: {
    type: String,
    default: '',
  },
});
</script>

<template>
  <div class="quick-answer">
    <strong>Resposta rápida</strong>
    <p>{{ text }}</p>
  </div>
</template>
