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
    <section class="dd-section">
      <div class="dd-container">
        <div class="dd-section-head">
          <h1>Dicionário Digital — Todos os termos</h1>
        </div>

        <div class="dd-grid dd-grid-3">
          <article v-for="post in posts.data" :key="post.id" class="dd-card">
            <small>{{ post.category?.name || 'Sem categoria' }}</small>
            <h2><Link :href="route('posts.show', post.slug)">{{ post.title }}</Link></h2>
            <p>{{ post.excerpt }}</p>
            <div v-if="post.tags?.length" class="dd-tags">
              <Link v-for="tag in post.tags" :key="tag.id" :href="route('posts.tag', tag.slug)">#{{ tag.name }}</Link>
            </div>
          </article>
        </div>

        <PostPagination :links="posts.links || []" />
      </div>
    </section>
  </ContentSiteLayout>
</template>
