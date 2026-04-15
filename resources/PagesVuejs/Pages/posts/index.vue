<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const seo = computed(() => page.props.seo ?? {});
const posts = computed(() => page.props.posts ?? { data: [] });
</script>

<template>
  <AppHead v-bind="seo" />

  <ContentSiteLayout title="Posts" page-type="listing">
    <section class="container py-5">
      <header class="mb-4">
        <h1 class="display-6 fw-bold">Posts</h1>
        <p class="text-secondary mb-0">Conteúdos publicados e organizados para facilitar sua navegação.</p>
      </header>

      <div class="row g-4">
        <article v-for="post in posts.data" :key="post.id" class="col-12 col-lg-6">
          <div class="card h-100 shadow-sm">
            <div class="card-body">
              <p class="small text-muted mb-2">{{ post.category?.name || 'Sem categoria' }}</p>
              <h2 class="h5"><Link :href="route('posts.show', post.slug)">{{ post.title }}</Link></h2>
              <p class="text-secondary">{{ post.excerpt }}</p>
              <div class="d-flex gap-2 flex-wrap">
                <span v-for="tag in post.tags" :key="tag.id" class="badge text-bg-light">#{{ tag.name }}</span>
              </div>
            </div>
          </div>
        </article>
      </div>
      <PostPagination :links="posts.links || []" />
    </section>
  </ContentSiteLayout>
</template>
