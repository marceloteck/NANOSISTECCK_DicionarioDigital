<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const seo = computed(() => page.props.seo ?? {});
const post = computed(() => page.props.post ?? {});
const relatedPosts = computed(() => page.props.relatedPosts ?? []);
const showAdSlots = computed(() => page.props.showAdSlots ?? false);

const contextualLinks = computed(() => relatedPosts.value.slice(0, 3));
const whereUsed = computed(() => {
  const categoryName = post.value.category?.name ?? 'redes sociais';

  return [
    `Conteúdos em ${categoryName}`,
    'Legendas de vídeos curtos e memes',
    'Comentários em redes como TikTok, X e Instagram',
  ];
});

const sampleExamples = computed(() => [
  `"${post.value.title}: exemplo prático em conversa online."`,
  `"Quando usar ${post.value.title} sem perder contexto."`,
]);

const termVariations = computed(() => {
  const tags = (post.value.tags ?? []).map((tag) => tag.name);
  const relatedKeywords = post.value.related_keywords ?? [];
  return [...new Set([...tags, ...relatedKeywords])].slice(0, 8);
});
</script>

<template>
  <AppHead v-bind="seo" />

  <ContentSiteLayout :title="post.title" page-type="post">
    <article class="dd-container dd-post">
      <nav class="dd-breadcrumb" aria-label="Breadcrumb">
        <Link :href="route('index.home')">Início</Link>
        <span>/</span>
        <Link :href="route('posts.index')">Posts</Link>
        <template v-if="post.category">
          <span>/</span>
          <Link :href="route('posts.category', post.category.slug)">{{ post.category.name }}</Link>
        </template>
      </nav>

      <h1>{{ post.title }}</h1>
      <p v-if="post.excerpt" class="dd-post-lead">{{ post.excerpt }}</p>

      <div class="dd-meta">
        <span v-if="post.author_name">Por {{ post.author_name }}</span>
        <span v-if="post.published_at">Publicado em {{ new Date(post.published_at).toLocaleDateString('pt-BR') }}</span>
        <span>{{ post.reading_time }} min de leitura</span>
      </div>

      <div v-if="showAdSlots" class="dd-ad-slot">Espaço AdSense — topo do conteúdo</div>

      <section class="dd-quick-answer">
        <h2>Resposta rápida</h2>
        <p>{{ post.excerpt || `Resumo objetivo sobre ${post.title}.` }}</p>
      </section>

      <section>
        <h2>Explicação detalhada</h2>
        <div class="dd-rich-content" v-html="post.content_html" />
      </section>

      <section>
        <h2>Onde é usado</h2>
        <ul>
          <li v-for="item in whereUsed" :key="item">{{ item }}</li>
        </ul>
      </section>

      <section>
        <h2>Exemplos reais</h2>
        <ul>
          <li v-for="item in sampleExamples" :key="item">{{ item }}</li>
        </ul>
      </section>

      <div v-if="showAdSlots" class="dd-ad-slot">Espaço AdSense — meio do conteúdo</div>

      <section v-if="termVariations.length">
        <h2>Variações e termos relacionados</h2>
        <div class="dd-tags">
          <Link v-for="item in termVariations" :key="item" :href="route('search.index', { q: item })">{{ item }}</Link>
        </div>
      </section>

      <section v-if="contextualLinks.length">
        <h2>Links internos no conteúdo</h2>
        <p>
          Continue aprendendo:
          <template v-for="(item, index) in contextualLinks" :key="item.id">
            <Link :href="route('posts.show', item.slug)">{{ item.title }}</Link><span v-if="index < contextualLinks.length - 1"> • </span>
          </template>
        </p>
      </section>

      <section v-if="post.faq?.length">
        <h2>FAQ (perguntas frequentes)</h2>
        <div class="dd-faq-list">
          <article v-for="item in post.faq" :key="item.question" class="dd-faq-item">
            <h3>{{ item.question }}</h3>
            <p>{{ item.answer }}</p>
          </article>
        </div>
      </section>

      <section v-if="relatedPosts.length">
        <h2>Posts relacionados</h2>
        <div class="dd-grid dd-grid-3">
          <article v-for="item in relatedPosts" :key="item.id" class="dd-card">
            <h3><Link :href="route('posts.show', item.slug)">{{ item.title }}</Link></h3>
            <p>{{ item.excerpt }}</p>
          </article>
        </div>
      </section>

      <div v-if="showAdSlots" class="dd-ad-slot">Espaço AdSense — final do conteúdo</div>
    </article>
  </ContentSiteLayout>
</template>
