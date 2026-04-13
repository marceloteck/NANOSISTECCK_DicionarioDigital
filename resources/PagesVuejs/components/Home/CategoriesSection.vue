<script setup>
import CategoryCard from './CategoryCard.vue';

defineProps({
  items: {
    type: Array,
    required: true,
  },
});
</script>

<template>
  <section class="section" id="categorias">
    <div class="container">
      <div class="section-head">
        <span class="eyebrow">Categorias principais</span>
        <h2>Encontre rapidamente o assunto que você quer entender</h2>
        <p>
          O site foi organizado em categorias que facilitam a navegação e ajudam você a descobrir novos significados com mais facilidade.
        </p>
      </div>

      <div class="categories-grid">
        <CategoryCard v-for="item in items" :key="item.title" :item="item" />
      </div>
    </div>
  </section>
</template>
