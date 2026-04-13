<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const seo = computed(() => page.props.seo ?? {});
const post = computed(() => page.props.post ?? {});
const relatedPosts = computed(() => page.props.relatedPosts ?? []);
const showAdSlots = computed(() => page.props.showAdSlots ?? false);
</script>

<template>
  <AppHead v-bind="seo" />

  <ContentSiteLayout :title="post.title" page-type="post">
  <article class="container py-5 post-page">
    <nav class="small mb-3 text-secondary">
      <Link :href="route('index.home')">Início</Link> / <Link :href="route('posts.index')">Posts</Link>
      <template v-if="post.category"> / <Link :href="route('posts.category', post.category.slug)">{{ post.category.name }}</Link></template>
    </nav>

    <header class="mb-4">
      <h1 class="display-6 fw-bold mb-3">{{ post.title }}</h1>
      <p class="lead text-secondary" v-if="post.excerpt">{{ post.excerpt }}</p>
      <div class="d-flex gap-3 flex-wrap small text-muted">
        <span v-if="post.author_name">Por {{ post.author_name }}</span>
        <span v-if="post.published_at">Publicado em {{ new Date(post.published_at).toLocaleDateString('pt-BR') }}</span>
        <span>{{ post.reading_time }} min de leitura</span>
      </div>
      <div v-if="post.tags?.length" class="d-flex flex-wrap gap-2 mt-3">
        <Link v-for="tag in post.tags" :key="tag.id" :href="route('posts.tag', tag.slug)" class="badge text-bg-light text-decoration-none">
          #{{ tag.name }}
        </Link>
      </div>
    </header>

    <div v-if="showAdSlots" class="ad-slot ad-slot--top mb-4">Espaço reservado para monetização futura</div>

    <img
      v-if="post.featured_image"
      :src="post.featured_image"
      :alt="post.featured_image_alt || post.title"
      class="img-fluid rounded mb-4"
      loading="lazy"
    >

    <aside v-if="post.toc?.length" class="card mb-4 shadow-sm">
      <div class="card-body">
        <h2 class="h6">Neste artigo</h2>
        <ul class="list-unstyled mb-0">
          <li v-for="item in post.toc" :key="item.id" :class="item.level === 'h3' ? 'ms-3' : ''">
            <a :href="`#${item.id}`">{{ item.label }}</a>
          </li>
        </ul>
      </div>
    </aside>

    <section class="post-content" v-html="post.content_html" />

    <div v-if="showAdSlots" class="ad-slot ad-slot--middle mt-4">Espaço reservado para monetização futura</div>

    <section v-if="post.faq?.length" class="mt-5">
      <h2 class="h4 mb-3">Perguntas frequentes</h2>
      <div class="accordion" id="postFaq">
        <div v-for="(item, index) in post.faq" :key="item.question" class="accordion-item">
          <h3 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" :data-bs-target="`#faq-${index}`">
              {{ item.question }}
            </button>
          </h3>
          <div :id="`faq-${index}`" class="accordion-collapse collapse" data-bs-parent="#postFaq">
            <div class="accordion-body">{{ item.answer }}</div>
          </div>
        </div>
      </div>
    </section>

    <section v-if="post.title_suggestions?.length" class="mt-5">
      <h2 class="h5">Sugestões de título (otimização CTR)</h2>
      <ul class="mb-0">
        <li v-for="item in post.title_suggestions" :key="item">{{ item }}</li>
      </ul>
    </section>

    <section v-if="relatedPosts.length" class="mt-5">
      <h2 class="h4 mb-3">Continue lendo</h2>
      <div class="row g-3">
        <article v-for="item in relatedPosts" :key="item.id" class="col-12 col-md-6">
          <div class="card h-100 shadow-sm">
            <div class="card-body">
              <h3 class="h6"><Link :href="route('posts.show', item.slug)">{{ item.title }}</Link></h3>
              <p class="small text-secondary mb-0">{{ item.excerpt }}</p>
            </div>
          </div>
        </article>
      </div>
    </section>
  </article>
</ContentSiteLayout>
</template>
