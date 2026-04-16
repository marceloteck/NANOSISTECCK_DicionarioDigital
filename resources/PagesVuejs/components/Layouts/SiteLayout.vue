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
        <div class="container site-header__inner py-3">
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
        <div class="footer-brand">
          <BrandLogo subtitle="Projeto NANOSISTECCK" :use-icon="true" />
        </div>
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
<style scoped>
.brand{
    color: #000;
}
.site-layout {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

.site-layout main {
    flex: 1;
    width: 100%;
}

.site-header {
    width: 100%;
    position: relative;
    z-index: 10;
    background: #ffffff;
}

.site-header__inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
    flex-wrap: nowrap;
}

.site-header__inner > a {
    display: inline-flex;
    align-items: center;
    flex: 0 0 auto;
    min-width: 0;
}

.main-nav {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    flex: 1 1 auto;
    min-width: 0;
}

.nav-links {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    flex-wrap: wrap;
    gap: 12px 20px;
    list-style: none;
    padding: 0;
    margin: 0;
    min-width: 0;
}

.nav-links li {
    list-style: none;
    display: flex;
    align-items: center;
}

.nav-links a {
    display: inline-flex;
    align-items: center;
    min-height: 40px;
    text-decoration: none;
    color: #1f2937;
    font-weight: 600;
    transition: color 0.2s ease, opacity 0.2s ease;
    white-space: nowrap;
}

.nav-links a:hover {
    color: #0d6efd;
}

.site-footer {
    margin-top: auto;
    width: 100%;
    background: #ffffff;
}

.site-footer .container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;
}

.footer-brand {
    display: flex;
    align-items: center;
    min-width: 220px;
}

.site-footer .d-flex {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 12px 20px;
    flex-wrap: wrap;
    min-width: 0;
    flex: 1;
}

.site-footer a {
    color: #4b5563;
    font-weight: 500;
    transition: color 0.2s ease, opacity 0.2s ease;
    word-break: break-word;
}

.site-footer a:hover {
    color: #0d6efd;
}

@media (max-width: 991.98px) {
    .site-header__inner {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }

    .site-header__inner > a {
        width: 100%;
    }

    .main-nav {
        width: 100%;
        justify-content: flex-start;
    }

    .nav-links {
        width: 100%;
        justify-content: flex-start;
        gap: 10px 16px;
    }

    .site-footer .container {
        flex-direction: column;
        align-items: flex-start;
    }

    .site-footer .d-flex {
        width: 100%;
        justify-content: flex-start;
    }
}

@media (max-width: 575.98px) {
    .site-header__inner {
        padding-top: 16px;
        padding-bottom: 16px;
    }

    .nav-links {
        flex-direction: column;
        align-items: flex-start;
        gap: 4px;
    }

    .nav-links li,
    .nav-links a {
        width: 100%;
    }

    .nav-links a {
        min-height: auto;
        padding: 8px 0;
    }

    .footer-brand {
        min-width: auto;
        width: 100%;
    }

    .site-footer .d-flex {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }

    .site-footer a {
        display: inline-block;
    }
}
</style>