<script setup>
import SearchPanel from './SearchPanel.vue';

defineProps({
  suggestions: {
    type: Array,
    required: true,
  },
  previews: {
    type: Array,
    required: true,
  },
});
</script>

<template>
  <section class="hero" id="inicio">
    <div class="container hero-grid">
      <div class="hero-copy">
        <div class="hero-pill">Significados rápidos • Busca inteligente • Conteúdo claro e confiável</div>

        <h1 class="hero-title">
          Descubra o significado de
          <span class="gradient">gírias, emojis, siglas e termos digitais</span>
          com uma experiência premium.
        </h1>

        <p class="hero-desc">
          O Dicionário Digital foi criado para ajudar você a entender o que as palavras da internet realmente significam.
          Aqui você encontra explicações claras, exemplos práticos, conteúdos relacionados e uma navegação feita para facilitar sua busca por informação.
        </p>

        <div class="hero-actions">
          <a href="#busca" class="btn btn-primary">Usar busca inteligente</a>
          <a href="#sistema-post" class="btn btn-outline">Ver exemplo de post</a>
        </div>

        <div class="hero-trust">
          <span>Leitura confortável</span>
          <span>Conteúdo fácil de entender</span>
          <span>Estrutura pronta para SEO</span>
          <span>Base ideal para expansão</span>
        </div>
      </div>

      <SearchPanel :suggestions="suggestions" :previews="previews" />
    </div>
  </section>
</template>
