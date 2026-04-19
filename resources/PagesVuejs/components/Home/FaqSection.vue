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
        <h2>Tudo o que você precisa para entender os termos da internet</h2>
        <p>Respostas rápidas, exemplos claros e navegação simples para você encontrar o significado certo sem perder tempo.</p>
      </div>

      <div class="faq-grid">
        <FaqCard v-for="item in items" :key="item.question" :item="item" />
      </div>
    </div>
  </section>
</template>
