<script setup>
import RelatedPostCard from './RelatedPostCard.vue';

defineProps({
  posts: {
    type: Array,
    required: true,
  },
  topSearches: {
    type: Array,
    required: true,
  },
});
</script>

<template>
  <section class="section" id="relacionados">
    <div class="container posts-layout">
      <div class="posts-main">
        <div class="section-head">
          <span class="eyebrow">Continue explorando</span>
          <h2>Veja também outros conteúdos que podem interessar</h2>
          <p>
            Descubra mais explicações, significados, dúvidas frequentes e conteúdos relacionados
            para ampliar seu entendimento sobre este assunto e encontrar novas respostas.
          </p>
        </div>

        <div class="post-list">
          <RelatedPostCard v-for="post in posts" :key="post.title" :item="post" />
        </div>
      </div>

      <aside class="posts-sidebar">
        <div class="sidebar-card">
          <small>Pesquisas populares</small>
          <ul>
            <li v-for="item in topSearches" :key="item"><a href="#">{{ item }}</a></li>
          </ul>
        </div>

        <div class="sidebar-card">
          <small>Conteúdo patrocinado</small>
          <div class="ad-placeholder">
            Neste espaço você pode encontrar recomendações, sugestões úteis e conteúdos patrocinados
            relacionados aos assuntos mais buscados do momento.
          </div>
        </div>
      </aside>
    </div>
  </section>
</template>