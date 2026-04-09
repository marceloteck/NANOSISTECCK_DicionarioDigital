<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();

const seo = computed(() => page.props.seo ?? {});
const featuredTerms = computed(() => page.props.featuredTerms ?? []);
const trendingTerms = computed(() => page.props.trendingTerms ?? []);
const recentTerms = computed(() => page.props.recentTerms ?? []);
const categories = computed(() => page.props.categories ?? []);
const faq = computed(() => page.props.faq ?? []);
const popularSearches = computed(() => page.props.popularSearches ?? []);
</script>

<template>
  <AppHead v-bind="seo" />

  <HybridSiteLayout title="Dicionário Digital" page-type="home">
    <section class="dd-hero">
      <div class="dd-container">
        <p class="dd-eyebrow">Dicionário Digital</p>
        <h1>Entenda termos da internet com respostas rápidas e conteúdo completo.</h1>
        <p class="dd-subtitle">
          Plataforma profissional para gírias, abreviações, emojis e tecnologia com arquitetura SEO avançada,
          interlinkagem inteligente e foco em retenção.
        </p>

        <form class="dd-search" method="get" :action="route('search.index')">
          <input name="q" type="search" placeholder="Busque: POV, FYP, IYKYK..." aria-label="Buscar termo" />
          <button type="submit">Buscar</button>
        </form>

        <div class="dd-tags">
          <Link v-for="tag in popularSearches" :key="tag" :href="`${route('search.index')}?q=${encodeURIComponent(tag)}`">{{ tag }}</Link>
        </div>
      </div>
    </section>

    <section class="dd-section">
      <div class="dd-container">
        <div class="dd-section-head">
          <h2>Mais buscados</h2>
          <Link :href="route('posts.index')">Ver todos</Link>
        </div>
        <div class="dd-grid dd-grid-4">
          <article v-for="item in featuredTerms" :key="item.title" class="dd-card">
            <h3><Link :href="item.url">{{ item.title }}</Link></h3>
            <p>{{ item.excerpt }}</p>
          </article>
        </div>
      </div>
    </section>

    <section class="dd-section dd-section-alt">
      <div class="dd-container">
        <div class="dd-section-head">
          <h2>Categorias principais</h2>
        </div>
        <div class="dd-grid dd-grid-3">
          <article v-for="category in categories" :key="category.name" class="dd-card">
            <h3><Link :href="category.url">{{ category.name }}</Link></h3>
            <p>{{ category.description }}</p>
          </article>
        </div>
      </div>
    </section>

    <section class="dd-section" id="em-alta">
      <div class="dd-container">
        <div class="dd-section-head">
          <h2>Em alta</h2>
        </div>
        <div class="dd-grid dd-grid-3">
          <article v-for="item in trendingTerms" :key="item.title" class="dd-card">
            <h3><Link :href="item.url">{{ item.title }}</Link></h3>
            <p>{{ item.excerpt }}</p>
          </article>
        </div>
      </div>
    </section>

    <section class="dd-section" id="recentes">
      <div class="dd-container">
        <div class="dd-section-head">
          <h2>Recentes</h2>
        </div>
        <div class="dd-grid dd-grid-3">
          <article v-for="item in recentTerms" :key="item.title" class="dd-card">
            <small>{{ item.category || 'Sem categoria' }}</small>
            <h3><Link :href="item.url">{{ item.title }}</Link></h3>
            <p>{{ item.excerpt }}</p>
          </article>
        </div>
      </div>
    </section>

    <section class="dd-section dd-section-alt">
      <div class="dd-container">
        <div class="dd-section-head">
          <h2>FAQ resumido</h2>
        </div>
        <div class="dd-grid dd-grid-2">
          <article v-for="item in faq" :key="item.question" class="dd-faq-item">
            <h3>{{ item.question }}</h3>
            <p>{{ item.answer }}</p>
          </article>
        </div>
      </div>
    </section>
  </HybridSiteLayout>
</template>
