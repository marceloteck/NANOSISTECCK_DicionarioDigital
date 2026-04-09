<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const seo = computed(() => page.props.seo ?? {});
const institutionalPage = computed(() => page.props.institutionalPage ?? {});

const contentByKey = {
  about: {
    lead: 'O Dicionário Digital é uma plataforma SEO-first para explicar termos da internet com profundidade, clareza e experiência profissional.',
  },
  contact: {
    lead: 'Envie sugestões de termos, correções ou parcerias para contato@dicionariodigital.com.br.',
  },
  privacy: {
    lead: 'Respeitamos sua privacidade e utilizamos dados de navegação apenas para melhorar experiência, conteúdo e segurança.',
  },
  terms: {
    lead: 'Ao usar o site, você concorda com as diretrizes de uso, propriedade intelectual e boas práticas da plataforma.',
  },
};

const lead = computed(() => contentByKey[institutionalPage.value.key]?.lead ?? 'Conteúdo institucional oficial do Dicionário Digital.');
</script>

<template>
  <AppHead v-bind="seo" />

  <InstitutionalLayout :title="institutionalPage.title" page-type="institutional">
    <section class="dd-section">
      <div class="dd-container dd-institutional">
        <h1>{{ institutionalPage.title }}</h1>
        <p class="dd-subtitle">{{ lead }}</p>
      </div>
    </section>
  </InstitutionalLayout>
</template>
