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
          Plataforma criada para ajudar você a entender termos, descobrir curiosidades 
          e encontrar respostas de forma rápida, clara e confiável. Continue explorando 
          e aproveite todo o conteúdo disponível.
        </p>
      </div>

      <NavLinks :links="links" list-class="footer-links" />
    </div>
  </footer>
</template>