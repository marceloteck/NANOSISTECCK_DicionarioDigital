<script setup>
import BrandLogo from './BrandLogo.vue';
import NavLinks from './NavLinks.vue';

const links = [
  { label: 'Início', href: '#inicio' },
  { label: 'Categorias', href: '#categorias' },
  { label: 'Posts', href: '#posts' },
  { label: 'FAQ', href: '#faq' },
];
</script>

<template>
  <footer class="site-footer">
    <div class="container footer-shell">
      <div class="footer-brand">
        <BrandLogo subtitle="Projeto NANOSISTECCK" :use-icon="true" />

        <p>
          Um modelo premium criado para transformar curiosidades da internet em uma experiência clara, <br> elegante e pronta para crescer.
        </p>
      </div>

      <NavLinks :links="links" list-class="footer-links" />
    </div>
  </footer>
</template>
