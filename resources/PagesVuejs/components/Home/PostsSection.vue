<script setup>
import PostCard from './PostCard.vue';
import SidebarCard from './SidebarCard.vue';

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
  <section class="section" id="posts">
    <div class="container posts-layout">
      <div class="posts-main">
        <div class="section-head">
          <span class="eyebrow">Posts recentes</span>
          <h2>Conteúdos novos para você continuar explorando</h2>
          <p>
            Descubra os conteúdos mais recentes publicados no site, com explicações claras, 
            diretas e pensadas para resolver dúvidas do dia a dia. Aqui você encontra 
            respostas rápidas, aprende algo novo e continua navegando por temas que realmente importam.
          </p>
        </div>

        <div class="post-list">
          <PostCard v-for="post in posts" :key="post.title" :item="post" />
        </div>
      </div>

      <aside class="posts-sidebar">
        <SidebarCard title="Mais buscados" :links="topSearches" />
        <SidebarCard
          title="Recomendado para você"
          ad-text="Conteúdos e oportunidades selecionadas para ajudar você a descobrir novas soluções, produtos e informações úteis"
        />
      </aside>
    </div>
  </section>
</template>