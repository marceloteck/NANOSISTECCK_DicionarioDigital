<script setup>
import FeaturedCard from './FeaturedCard.vue';

defineProps({
  items: {
    type: Array,
    required: true,
  },
});
</script>

<template>
  <section class="section" id="destaques">
    <div class="container">
      <div class="section-head">
        <span class="eyebrow">Em destaque</span>
        <h2>Conteúdos que mais despertam curiosidade</h2>
        <p>
          Estes blocos mostram como os assuntos aparecem para o usuário: títulos claros, explicações rápidas e incentivo para continuar navegando.
        </p>
      </div>

      <div class="featured-grid">
        <FeaturedCard v-for="item in items" :key="item.title" :item="item" />
      </div>
    </div>
  </section>
</template>
