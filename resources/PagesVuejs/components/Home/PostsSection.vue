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
          <h2>Uma estrutura pronta para centenas de conteúdos</h2>
          <p>
            A home foi planejada para mostrar novos posts, posts em alta e caminhos de navegação que fazem o usuário continuar explorando.
          </p>
        </div>

        <div class="post-list">
          <PostCard v-for="post in posts" :key="post.title" :item="post" />
        </div>
      </div>

      <aside class="posts-sidebar">
        <SidebarCard title="Mais buscados" :links="topSearches" />
        <SidebarCard
          title="Área nobre para monetização"
          ad-text="Espaço ideal para AdSense responsivo ou bloco editorial em destaque"
        />
      </aside>
    </div>
  </section>
</template>
