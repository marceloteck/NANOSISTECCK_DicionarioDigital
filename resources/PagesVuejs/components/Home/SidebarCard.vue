<script setup>
defineProps({
  title: {
    type: String,
    required: true,
  },
  links: {
    type: Array,
    default: () => [],
  },
  adText: {
    type: String,
    default: '',
  },
});
</script>

<template>
  <div class="sidebar-card">
    <small>{{ title }}</small>

    <ul v-if="links.length">
      <li v-for="link in links" :key="link">
        <a href="#">{{ link }}</a>
      </li>
    </ul>

    <div v-else class="ad-placeholder">
      {{ adText }}
    </div>
  </div>
</template>
