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

  <ContentSiteLayout title="Taxonomia" page-type="listing">
    <section class="dd-section">
      <div class="dd-container">
        <h1>{{ taxonomy.type === 'category' ? 'Categoria' : 'Tag' }}: {{ taxonomy.name }}</h1>

        <div class="dd-grid dd-grid-3">
          <article v-for="post in posts.data" :key="post.id" class="dd-card">
            <h2><Link :href="route('posts.show', post.slug)">{{ post.title }}</Link></h2>
            <p>{{ post.excerpt }}</p>
          </article>
        </div>

        <PostPagination :links="posts.links || []" />
      </div>
    </section>
  </ContentSiteLayout>
</template>
