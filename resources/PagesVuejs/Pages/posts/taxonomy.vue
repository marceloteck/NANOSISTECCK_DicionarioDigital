<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const seo = computed(() => page.props.seo ?? {});
const taxonomy = computed(() => page.props.taxonomy ?? {});
const posts = computed(() => page.props.posts ?? { data: [] });
</script>

<template>
  <AppHead v-bind="seo" />

  <ContentSiteLayout title="Posts" page-type="listing">
    <section class="container py-5">
      <h1 class="display-6 fw-bold mb-2">{{ taxonomy.type === 'category' ? 'Categoria' : 'Tag' }}: {{ taxonomy.name }}</h1>
      <p class="text-secondary mb-4">
        Veja os posts relacionados a {{ taxonomy.type === 'category' ? 'esta categoria' : 'esta tag' }}.
      </p>

      <div class="row g-4" v-if="posts.data.length > 0">
        <article v-for="post in posts.data" :key="post.id" class="col-12 col-lg-6">
          <div class="card h-100 shadow-sm">
            <div class="card-body">
              <h2 class="h5 mb-2"><Link :href="route('posts.show', post.slug)">{{ post.title }}</Link></h2>
              <p class="text-secondary mb-0">{{ post.excerpt }}</p>
            </div>
          </div>
        </article>
      </div>

      <p v-else class="alert alert-light border">Nenhum post encontrado para este filtro.</p>
      <PostPagination :links="posts.links || []" />
    </section>
  </ContentSiteLayout>
</template>
