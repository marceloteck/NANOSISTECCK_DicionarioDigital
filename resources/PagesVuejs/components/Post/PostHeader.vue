<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import BrandLogo from '../Home/BrandLogo.vue';

const isMenuOpen = ref(false);

const links = [
  { label: 'Início', href: '/' },
  { label: 'Categorias', href: '/#categorias' },
  { label: 'Conteúdo', href: '#conteudo' },
  { label: 'FAQ', href: '#faq' },
  { label: 'Relacionados', href: '#relacionados' },
];

const closeMenu = () => {
  isMenuOpen.value = false;
};
</script>

<template>
  <header class="site-header" :class="{ 'menu-open': isMenuOpen }">
    <div class="container navbar">
      <Link :href="route('index.home')" class="brand-link" aria-label="Ir para a página inicial" @click="closeMenu">
        <BrandLogo />
      </Link>

      <button
        class="mobile-menu-toggle"
        type="button"
        aria-label="Abrir menu"
        :aria-expanded="String(isMenuOpen)"
        aria-controls="main-nav"
        @click="isMenuOpen = !isMenuOpen"
      >
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
          <path d="M4 7h16" />
          <path d="M4 12h16" />
          <path d="M4 17h16" />
        </svg>
      </button>

      <nav class="main-nav" id="main-nav" aria-label="Menu principal">
        <ul class="nav-links">
          <li v-for="link in links" :key="link.href">
            <a :href="link.href" @click="closeMenu">{{ link.label }}</a>
          </li>
        </ul>
      </nav>

      <div class="nav-actions">
        <a href="/#busca" class="btn btn-outline" @click="closeMenu">Buscar</a>
        <a href="#relacionados" class="btn btn-primary" @click="closeMenu">Continuar lendo</a>
      </div>
    </div>
  </header>
</template>
