<script setup>
defineProps({
  stats: {
    type: Array,
    required: true,
  },
  title: {
    type: String,
    default: '',
  },
  text: {
    type: String,
    default: '',
  },
  buttonText: {
    type: String,
    default: '',
  },
  buttonUrl: {
    type: String,
    default: '',
  },
});
</script>

<template>
  <section class="section cta-section">
    <div class="container">
      <div class="cta-panel">
        <div class="cta-content">
          <small>Continue navegando</small>

          <h2>{{ title || 'Descobrir um significado pode levar você a aprender muito mais.' }}</h2>

          <p>
            {{
              text ||
              'Se este conteúdo ajudou, aproveite para explorar outros termos, expressões e curiosidades que também fazem parte do dia a dia na internet, nas redes sociais e nas conversas mais usadas do momento.'
            }}
          </p>

          <div class="cta-actions">
            <a :href="buttonUrl || '/posts'" class="btn btn-primary">
              {{ buttonText || 'Ver mais conteúdos' }}
            </a>

            <a href="/#categorias" class="btn btn-outline">
              Explorar categorias
            </a>
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