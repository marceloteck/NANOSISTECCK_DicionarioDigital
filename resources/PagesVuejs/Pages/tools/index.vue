<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const seo = computed(() => page.props.seo ?? {});
const tools = computed(() => page.props.tools ?? { data: [] });
</script>

<template>
  <AppHead v-bind="seo" />
  <ToolsSiteLayout title="Ferramentas" page-type="listing">
    <section class="container py-5">
      <h1 class="h3 mb-4">Ferramentas</h1>
      <div class="row g-3">
        <article v-for="item in tools.data" :key="item.id" class="col-12 col-md-6">
          <div class="card h-100 shadow-sm">
            <div class="card-body">
              <h2 class="h5"><Link :href="route('tools.show', item.slug)">{{ item.title }}</Link></h2>
              <p class="text-secondary mb-0">{{ item.excerpt }}</p>
            </div>
          </div>
        </article>
      </div>
    </section>
  </ToolsSiteLayout>
</template>
