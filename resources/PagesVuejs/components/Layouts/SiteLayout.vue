<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import BrandLogo from '../Home/BrandLogo.vue';

const props = defineProps({
  title: { type: String, default: '' },
  pageType: { type: String, default: 'home' },
});

const page = usePage();
const project = computed(() => page.props.project ?? {});
const modules = computed(() => project.value.modules ?? {});
const institutionalLabels = {
  sobre: 'Sobre',
  contato: 'Contato',
  'politica-de-privacidade': 'Política de Privacidade',
  'termos-de-uso': 'Termos de Uso',
};

const institutionalPages = computed(() => Object.values(project.value.institutional_pages ?? {}));
</script>

<template>
  <div class="site-layout">
    <header class="site-header border-bottom bg-white">
      <div class="container navbar py-3">
        <Link :href="route('index.home')" class="text-decoration-none">
          <BrandLogo :use-icon="true" subtitle="Entenda a linguagem da internet" />
        </Link>

        <nav class="main-nav" aria-label="Menu público">
          <ul class="nav-links mb-0">
            <li v-if="modules.posts"><Link :href="route('posts.index')">Posts</Link></li>
            <li v-if="modules.taxonomy && route().has('posts.categories.index')"><Link :href="route('posts.categories.index')">Categorias</Link></li>
            <li v-if="modules.taxonomy && route().has('posts.tags.index')"><Link :href="route('posts.tags.index')">Tags</Link></li>
            <li v-if="modules.search_page && route().has('search.index')"><Link :href="route('search.index')">Buscar</Link></li>
            <li v-if="modules.tools && route().has('tools.index')"><Link :href="route('tools.index')">Tools</Link></li>
          </ul>
        </nav>
      </div>
    </header>

    <main>
      <slot />
    </main>

    <footer class="site-footer border-top mt-5">
      <div class="container py-4 small d-flex flex-wrap gap-3 align-items-center justify-content-between">
        <p class="mb-0 text-muted">{{ project.name || 'Site Engine' }} — conteúdo confiável e atualizado.</p>
        <div class="d-flex flex-wrap gap-3">
          <Link
            v-for="item in institutionalPages"
            :key="item.slug"
            v-show="item.enabled && route().has('institutional.show')"
            :href="route('institutional.show', item.slug)"
            class="text-decoration-none"
          >
            {{ institutionalLabels[item.slug] ?? item.slug }}
          </Link>
        </div>
      </div>
    </footer>
  </div>
</template>
