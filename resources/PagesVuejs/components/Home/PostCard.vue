<script setup>
defineProps({
  item: {
    type: Object,
    required: true,
  },
});
</script>

<template>
  <article class="post-card">
    <div class="post-meta">
      <span>{{ item.category }}</span>
      <span>•</span>
      <span>{{ item.badge }}</span>
    </div>
    <h3>{{ item.title }}</h3>
    <p>{{ item.description }}</p>
    <a :href="item.href || '#'">Ler explicação</a>
  </article>
</template>
