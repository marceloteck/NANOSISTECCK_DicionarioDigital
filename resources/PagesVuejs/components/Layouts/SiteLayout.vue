<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const props = defineProps({
  title: { type: String, default: '' },
  pageType: { type: String, default: 'home' },
});

const page = usePage();
const project = computed(() => page.props.project ?? {});
const modules = computed(() => project.value.modules ?? {});
const institutionalPages = computed(() => project.value.institutional_pages ?? {});
</script>

<template>
  <div class="site-layout">
    <header class="border-bottom bg-white sticky-top">
      <div class="container py-3 d-flex justify-content-between align-items-center">
        <Link :href="route('index.home')" class="fw-bold text-decoration-none">{{ project.name || 'Site Engine' }}</Link>
        <nav class="d-flex gap-3 small">
          <Link v-if="modules.posts" :href="route('posts.index')">Posts</Link>
          <Link v-if="modules.tools && route().has('tools.index')" :href="route('tools.index')">Tools</Link>
          <Link v-if="modules.search_page && route().has('search.index')" :href="route('search.index')">Buscar</Link>
        </nav>
      </div>
    </header>

    <main>
      <slot />
    </main>

    <footer class="border-top mt-5 py-4 bg-light">
      <div class="container small d-flex flex-wrap gap-3">
        <template v-for="(item, key) in institutionalPages" :key="key">
          <Link
            v-if="item.enabled && route().has('institutional.show')"
            :href="route('institutional.show', item.slug)"
            class="text-decoration-none"
          >
            {{ key }}
          </Link>
        </template>
      </div>
    </footer>
  </div>
</template>
