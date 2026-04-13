<script setup>
import MiniInfoCard from './MiniInfoCard.vue';

defineProps({
  infos: {
    type: Array,
    required: true,
  },
});
</script>

<template>
  <section class="section" id="sistema-post">
    <div class="container system-grid">
      <article class="post-preview">
        <div class="breadcrumb">
          <span>Início</span>
          <span>›</span>
          <span>Gírias da Internet</span>
          <span>›</span>
          <span>POV</span>
        </div>

        <small class="preview-label">Exemplo de página de post</small>
        <h2>O que significa POV?</h2>

        <p class="preview-intro">
          POV é a sigla de <strong>point of view</strong>, que em português significa <strong>ponto de vista</strong>.
          Na internet, esse termo é usado para mostrar uma situação como se o espectador estivesse vivendo aquela cena.
        </p>

        <div class="quick-answer">
          <strong>Resposta rápida</strong>
          <p>POV significa “ponto de vista” e costuma aparecer em vídeos, memes e conteúdos curtos para indicar a perspectiva de alguém.</p>
        </div>

        <div class="post-sections">
          <section>
            <h3>Onde esse termo é usado?</h3>
            <p>Principalmente no TikTok, no Instagram Reels, em memes e em vídeos que simulam situações do dia a dia.</p>
          </section>

          <section>
            <h3>Exemplo simples</h3>
            <p><strong>POV:</strong> você chega atrasado e tenta entrar sem ser visto.</p>
          </section>

          <section>
            <h3>Termos relacionados</h3>
            <div class="related-tags">
              <a href="#">FYP</a>
              <a href="#">cringe</a>
              <a href="#">trend</a>
              <a href="#">random</a>
            </div>
          </section>
        </div>
      </article>

      <aside class="system-sidebar">
        <MiniInfoCard v-for="item in infos" :key="item.title" :item="item" />
      </aside>
    </div>
  </section>
</template>
