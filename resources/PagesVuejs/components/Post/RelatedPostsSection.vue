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
          <span class="eyebrow">Continue navegando</span>
          <h2>Quem pesquisou POV costuma querer entender estes termos também</h2>
          <p>
            Esta área ajuda a aumentar retenção, profundidade de navegação e relevância temática da página.
          </p>
        </div>

        <div class="post-list">
          <RelatedPostCard v-for="post in posts" :key="post.title" :item="post" />
        </div>
      </div>

      <aside class="posts-sidebar">
        <div class="sidebar-card">
          <small>Mais buscados agora</small>
          <ul>
            <li v-for="item in topSearches" :key="item"><a href="#">{{ item }}</a></li>
          </ul>
        </div>

        <div class="sidebar-card">
          <small>Área nobre para monetização</small>
          <div class="ad-placeholder">
            Espaço ideal para AdSense responsivo, recomendação editorial ou bloco de links internos em destaque
          </div>
        </div>
      </aside>
    </div>
  </section>
</template>
