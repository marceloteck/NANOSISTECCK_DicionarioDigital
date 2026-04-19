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
        <h2>Descubra conteúdos que estão chamando atenção agora</h2>
        <p>
          Explore temas populares com explicações claras, diretas e fáceis de entender. 
          Navegue pelos conteúdos mais acessados e encontre rapidamente respostas, 
          curiosidades e informações úteis que fazem parte do dia a dia digital.
        </p>
      </div>

      <div class="featured-grid">
        <FeaturedCard v-for="item in items" :key="item.title" :item="item" />
      </div>
    </div>
  </section>
</template>