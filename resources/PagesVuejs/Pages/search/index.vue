<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const seo = computed(() => page.props.seo ?? {});
const results = computed(() => page.props.results ?? { data: [] });
const query = computed(() => page.props.query ?? '');
const tools = computed(() => page.props.tools ?? []);
</script>

<template>
  <AppHead v-bind="seo" />
  <HybridSiteLayout title="Buscar" page-type="search">
    <section class="container py-5">
      <h1 class="h3">Buscar</h1>
      <form class="row g-2 mt-1" :action="route('search.index')" method="get" role="search">
        <div class="col-12 col-md-9">
          <input type="search" name="q" :value="query" class="form-control" placeholder="Digite o que você quer encontrar..." />
        </div>
        <div class="col-12 col-md-3">
          <button type="submit" class="btn btn-primary w-100">Pesquisar</button>
        </div>
      </form>

      <p class="text-secondary mt-3">Resultados para: <strong>{{ query || 'tudo' }}</strong></p>

      <ul class="list-group mt-3" v-if="results.data.length > 0">
        <li v-for="item in results.data" :key="`post-${item.id}`" class="list-group-item">
          <Link :href="item.url" class="fw-semibold text-decoration-none">{{ item.title }}</Link>
          <p class="small text-secondary mb-2">{{ item.excerpt }}</p>
          <div class="d-flex flex-wrap gap-2">
            <Link v-if="item.category_url" :href="item.category_url" class="badge text-bg-light text-decoration-none">{{ item.category }}</Link>
            <Link v-for="tag in item.tags || []" :key="tag.name" :href="tag.url" class="badge text-bg-light text-decoration-none">#{{ tag.name }}</Link>
          </div>
        </li>
      </ul>

      <p v-else class="alert alert-light border mt-3">Nenhum resultado encontrado para sua busca.</p>

      <PostPagination :links="results.links || []" />

      <div v-if="tools.length > 0" class="mt-4">
        <h2 class="h5">Ferramentas relacionadas</h2>
        <ul class="list-group">
          <li v-for="(tool, index) in tools" :key="`tool-${index}`" class="list-group-item">
            <Link :href="tool.url" class="fw-semibold text-decoration-none">{{ tool.title }}</Link>
            <p class="small text-secondary mb-0">{{ tool.excerpt }}</p>
          </li>
        </ul>
      </div>
    </section>
  </HybridSiteLayout>
</template>
