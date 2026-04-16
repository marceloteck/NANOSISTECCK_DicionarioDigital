<script setup>
import PostFaqCard from './PostFaqCard.vue';

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
        <h2>Tire suas principais dúvidas</h2>
        <p>
          Confira respostas rápidas, claras e úteis para entender melhor o assunto, esclarecer dúvidas comuns
          e encontrar as informações mais procuradas sobre este tema.
        </p>
      </div>

      <div class="faq-grid">
        <PostFaqCard v-for="item in items" :key="item.question" :item="item" />
      </div>
    </div>
  </section>
</template>