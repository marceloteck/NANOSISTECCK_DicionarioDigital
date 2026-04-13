<script setup>
import { ref } from 'vue';
import BrandLogo from './BrandLogo.vue';
import NavLinks from './NavLinks.vue';

const isMenuOpen = ref(false);

const links = [
  { label: 'Início', href: '#inicio' },
  { label: 'Categorias', href: '#categorias' },
  { label: 'Destaques', href: '#destaques' },
  { label: 'Posts', href: '#posts' },
  { label: 'FAQ', href: '#faq' },
];

const closeMenu = () => {
  isMenuOpen.value = false;
};
</script>

<template>
  <header class="site-header" :class="{ 'menu-open': isMenuOpen }">
    <div class="container navbar">
      <a href="#" class="brand-link" aria-label="Ir para a página inicial" @click="closeMenu">
        <BrandLogo />
      </a>

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
        <NavLinks :links="links" @click="closeMenu" />
      </nav>

      <div class="nav-actions">
        <a href="#busca" class="btn btn-outline" @click="closeMenu">Buscar</a>
        <a href="#posts" class="btn btn-primary" @click="closeMenu">Explorar conteúdo</a>
      </div>
    </div>
  </header>
</template>
