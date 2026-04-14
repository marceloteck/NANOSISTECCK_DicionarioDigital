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
        <h2>Dúvidas comuns sobre POV</h2>
        <p>
          Esta seção ajuda o usuário a encontrar respostas rápidas e ainda reforça a cobertura semântica da página.
        </p>
      </div>

      <div class="faq-grid">
        <PostFaqCard v-for="item in items" :key="item.question" :item="item" />
      </div>
    </div>
  </section>
</template>
