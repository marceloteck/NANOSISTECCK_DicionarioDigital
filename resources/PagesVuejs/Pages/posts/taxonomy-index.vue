<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const seo = computed(() => page.props.seo ?? {});
const taxonomyIndex = computed(() => page.props.taxonomyIndex ?? {});
const items = computed(() => page.props.items ?? { data: [] });
</script>

<template>
  <AppHead v-bind="seo" />

  <ContentSiteLayout :title="taxonomyIndex.title || 'Taxonomia'" page-type="listing">
    <section class="container py-5">
      <h1 class="display-6 fw-bold mb-2">{{ taxonomyIndex.title || 'Taxonomia' }}</h1>
      <p class="text-secondary mb-4">{{ taxonomyIndex.description }}</p>

      <div class="row g-3" v-if="items.data?.length">
        <article v-for="item in items.data" :key="item.id" class="col-12 col-md-6 col-lg-4">
          <div class="card h-100 shadow-sm">
            <div class="card-body d-flex flex-column gap-2">
              <h2 class="h5 mb-0">
                <Link :href="item.url" class="text-decoration-none">{{ item.name }}</Link>
              </h2>
              <p class="small text-secondary mb-0">{{ item.posts_count }} post(s) publicado(s).</p>
              <Link :href="item.url" class="btn btn-outline-primary btn-sm mt-auto">Explorar</Link>
            </div>
          </div>
        </article>
      </div>

      <p v-else class="alert alert-light border">Nenhum item disponível no momento.</p>

      <PostPagination :links="items.links || []" />
    </section>
  </ContentSiteLayout>
</template>
