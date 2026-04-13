<script setup>
defineProps({
  links: {
    type: Array,
    required: true,
  },
  listClass: {
    type: String,
    default: 'nav-links',
  },
});
</script>

<template>
  <ul :class="listClass">
    <li v-for="link in links" :key="link.href">
      <a :href="link.href">{{ link.label }}</a>
    </li>
  </ul>
</template>
