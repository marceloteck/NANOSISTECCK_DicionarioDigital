<script setup>
import CtaStat from './CtaStat.vue';

defineProps({
  stats: {
    type: Array,
    required: true,
  },
});
</script>

<template>
  <section class="section cta-section">
    <div class="container">
      <div class="cta-panel">
        <div class="cta-content">
          <small>Base premium para um projeto real</small>
          <h2>Seu Dicionário Digital já pode nascer com aparência de produto grande.</h2>
          <p>
            Este modelo foi pensado para parecer profissional, confiável e preparado para escalar com conteúdo, SEO e monetização.
            É uma base forte para transformar em um sistema completo no futuro.
          </p>

          <div class="cta-actions">
            <a href="#" class="btn btn-primary">Começar desenvolvimento</a>
            <a href="#" class="btn btn-outline">Planejar páginas internas</a>
          </div>
        </div>

        <div class="cta-stats">
          <CtaStat v-for="item in stats" :key="item.title" :item="item" />
        </div>
      </div>
    </div>
  </section>
</template>
