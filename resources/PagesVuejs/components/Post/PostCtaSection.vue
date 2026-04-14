<script setup>
defineProps({
  stats: {
    type: Array,
    required: true,
  },
});
</script>

<template>
  <section class="section cta-section">
    <div class="container">
      <div class="cta-panel">
        <div class="cta-content">
          <small>Continue explorando o dicionário</small>
          <h2>Entender uma palavra leva a outras curiosidades.</h2>
          <p>
            Esse é o tipo de página ideal para responder rápido, aprofundar com clareza e ainda conduzir o visitante para novos conteúdos,
            fortalecendo SEO, retenção e monetização de forma natural.
          </p>

          <div class="cta-actions">
            <a href="/#posts" class="btn btn-primary">Ver mais posts</a>
            <a href="/#categorias" class="btn btn-outline">Explorar categorias</a>
          </div>
        </div>

        <div class="cta-stats">
          <div v-for="stat in stats" :key="stat.title" class="cta-stat">
            <strong>{{ stat.title }}</strong>
            <span>{{ stat.description }}</span>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
