<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const seo = computed(() => page.props.seo ?? {});
const results = computed(() => page.props.results ?? []);
const query = computed(() => page.props.query ?? '');
</script>

<template>
  <AppHead v-bind="seo" />

  <HybridSiteLayout title="Buscar" page-type="search">
    <section class="dd-section">
      <div class="dd-container">
        <h1>Busca no Dicionário Digital</h1>
        <form class="dd-search" method="get" :action="route('search.index')">
          <input name="q" type="search" :value="query" placeholder="Ex: POV, FYP, ratio..." aria-label="Buscar termo" />
          <button type="submit">Buscar</button>
        </form>

        <p class="dd-subtitle">{{ query ? `Resultados para "${query}"` : 'Digite um termo para buscar.' }}</p>

        <div class="dd-grid dd-grid-2" v-if="results.length">
          <article v-for="(item, index) in results" :key="`${item.type}-${index}`" class="dd-card">
            <small>{{ item.type === 'post' ? 'Post' : 'Ferramenta' }}</small>
            <h2><Link :href="item.url">{{ item.title }}</Link></h2>
            <p>{{ item.excerpt }}</p>
          </article>
        </div>

        <p v-else class="dd-subtitle">Nenhum resultado encontrado.</p>
      </div>
    </section>
  </HybridSiteLayout>
</template>
