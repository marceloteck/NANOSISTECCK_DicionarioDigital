<script setup>
import FaqCard from './FaqCard.vue';

defineProps({
  items: {
    type: Array,
    required: true,
  },
});
</script>

<template>
  <section class="section" id="faq">
    <div class="container">
      <div class="section-head">
        <span class="eyebrow">Perguntas frequentes</span>
        <h2>Uma base feita para crescer com clareza e elegância</h2>
        <p>O projeto já nasce com uma estrutura visual e editorial pensada para facilitar o desenvolvimento futuro.</p>
      </div>

      <div class="faq-grid">
        <FaqCard v-for="item in items" :key="item.question" :item="item" />
      </div>
    </div>
  </section>
</template>
