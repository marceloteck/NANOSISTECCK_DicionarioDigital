<script setup>
defineProps({
  item: {
    type: Object,
    required: true,
  },
});
</script>

<template>
  <article class="post-card hero-panel" style="padding:24px; min-height:auto;">
    <div class="post-meta" style="display:flex; gap:10px; flex-wrap:wrap; color:var(--primary-strong); font-size:0.9rem; font-weight:700; margin-bottom:10px;">
      <span>{{ item.category }}</span>
      <span>•</span>
      <span>{{ item.badge }}</span>
    </div>
    <h3 style="font-size:1.24rem; margin-bottom:10px; letter-spacing:-0.03em;">{{ item.title }}</h3>
    <p style="color:var(--muted); line-height:1.8; margin-bottom:16px;">
      {{ item.description }}
    </p>
    <a :href="item.href || '#'" style="color:var(--primary); font-weight:700;">Ler explicação</a>
  </article>
</template>
