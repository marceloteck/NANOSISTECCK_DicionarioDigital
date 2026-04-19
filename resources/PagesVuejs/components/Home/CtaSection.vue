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
          <small>Seu guia rápido da internet</small>
          <h2>Descubra significados, gírias e tendências em um só lugar.</h2>
          <p>
            Aqui você encontra explicações claras, exemplos reais e conteúdos atualizados para entender o que está bombando nas redes.
            Tudo com leitura leve e navegação simples no celular ou desktop.
          </p>

          <div class="cta-actions">
            <a href="#" class="btn btn-primary">Pesquisar agora</a>
            <a href="#" class="btn btn-outline">Explorar conteúdos</a>
          </div>
        </div>

        <div class="cta-stats">
          <CtaStat v-for="item in stats" :key="item.title" :item="item" />
        </div>
      </div>
    </div>
  </section>
</template>
