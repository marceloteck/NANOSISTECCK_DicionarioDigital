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
      <div class="dd-container dd-hero-shell">
        <div class="dd-panel">
          <p class="dd-eyebrow">Dicionário Digital</p>
          <h1>Descubra o significado de cada termo da internet com confiança.</h1>
          <p class="dd-subtitle">
            Aprenda gírias, abreviações, emojis e expressões virais com explicações diretas, exemplos reais e conteúdos
            atualizados para o seu dia a dia online.
          </p>

          <form class="dd-search" method="get" :action="route('search.index')">
            <input name="q" type="search" placeholder="Busque por: POV, FYP, IYKYK, NPC..." aria-label="Buscar termo" />
            <button type="submit">Buscar agora</button>
          </form>

          <div class="dd-tags">
            <Link v-for="tag in popularSearches" :key="tag" :href="`${route('search.index')}?q=${encodeURIComponent(tag)}`">
              {{ tag }}
            </Link>
          </div>
        </div>

        <aside class="dd-soft-card">
          <h2>Comece por categoria</h2>
          <ul class="dd-category-list">
            <li v-for="category in categories" :key="category.name">
              <Link :href="category.url">
                <strong>{{ category.name }}</strong>
                <span>{{ category.description }}</span>
              </Link>
            </li>
          </ul>
        </aside>
      </div>
    </section>

    <section class="dd-section">
      <div class="dd-container">
        <div class="dd-section-head">
          <h2>Termos mais buscados</h2>
          <Link :href="route('posts.index')">Explorar todos</Link>
        </div>
        <div class="dd-grid dd-grid-4">
          <article v-for="item in featuredTerms" :key="item.title" class="dd-card">
            <h3><Link :href="item.url">{{ item.title }}</Link></h3>
            <p>{{ item.excerpt }}</p>
          </article>
        </div>
      </div>
    </section>

    <section class="dd-section dd-section-alt" id="categorias">
      <div class="dd-container">
        <div class="dd-section-head">
          <h2>Navegue por categorias</h2>
        </div>
        <div class="dd-grid dd-grid-3">
          <article v-for="category in categories" :key="`grid-${category.name}`" class="dd-card">
            <h3><Link :href="category.url">{{ category.name }}</Link></h3>
            <p>{{ category.description }}</p>
          </article>
        </div>
      </div>
    </section>

    <section class="dd-section" id="em-alta">
      <div class="dd-container">
        <div class="dd-section-head">
          <h2>Em alta agora</h2>
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
          <h2>Novidades do dicionário</h2>
        </div>
        <div class="dd-grid dd-grid-3">
          <article v-for="item in recentTerms" :key="item.title" class="dd-card">
            <small>{{ item.category || 'Termos digitais' }}</small>
            <h3><Link :href="item.url">{{ item.title }}</Link></h3>
            <p>{{ item.excerpt }}</p>
          </article>
        </div>
      </div>
    </section>

    <section class="dd-section dd-section-alt">
      <div class="dd-container">
        <div class="dd-section-head">
          <h2>Perguntas frequentes</h2>
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
