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
    <header class="dd-topbar">
      <div class="dd-container dd-topbar-inner">
        <Link :href="route('index.home')" class="dd-brand">
          <span class="dd-brand-mark">DD</span>
          <span>{{ project.name || 'Dicionário Digital' }}</span>
        </Link>

        <nav class="dd-main-nav" aria-label="Navegação principal">
          <Link v-if="modules.posts" :href="route('posts.index')">Categorias</Link>
          <Link v-if="modules.tools && route().has('tools.index')" :href="route('tools.index')">Ferramentas</Link>
          <Link v-if="modules.search_page && route().has('search.index')" :href="route('search.index')">Buscar termos</Link>
        </nav>
      </div>
    </header>

    <main>
      <slot />
    </main>

    <footer class="dd-footer">
      <div class="dd-container dd-footer-grid">
        <div>
          <h3>{{ project.name || 'Dicionário Digital' }}</h3>
          <p>
            Conteúdo atualizado com explicações claras, exemplos reais e navegação inteligente para você encontrar
            rapidamente o termo certo.
          </p>
        </div>

        <div>
          <h4>Institucional</h4>
          <ul>
            <template v-for="(item, key) in institutionalPages" :key="key">
              <li v-if="item.enabled && route().has('institutional.show')">
                <Link :href="route('institutional.show', item.slug)">{{ key }}</Link>
              </li>
            </template>
          </ul>
        </div>
      </div>
    </footer>
  </div>
</template>
