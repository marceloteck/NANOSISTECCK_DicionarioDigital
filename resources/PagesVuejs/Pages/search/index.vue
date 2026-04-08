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
    <section class="container py-5">
      <h1 class="h3">Buscar</h1>
      <p class="text-secondary">Resultados para: <strong>{{ query || '...' }}</strong></p>
      <ul class="list-group mt-3">
        <li v-for="(item, index) in results" :key="`${item.type}-${index}`" class="list-group-item">
          <Link :href="item.url" class="fw-semibold text-decoration-none">{{ item.title }}</Link>
          <p class="small text-secondary mb-0">{{ item.excerpt }}</p>
        </li>
      </ul>
    </section>
  </HybridSiteLayout>
</template>
