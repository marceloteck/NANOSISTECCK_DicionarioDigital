<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const seo = computed(() => page.props.seo ?? {});
const institutionalPage = computed(() => page.props.institutionalPage ?? {});

const contentByKey = {
  about: {
    lead: 'Somos um projeto editorial focado em explicar termos da internet com linguagem clara e útil para o usuário final.',
    blocks: [
      'Publicamos conteúdos orientados a intenção de busca, escaneabilidade e boa experiência de leitura.',
      'Nossa missão é facilitar o entendimento de gírias, siglas, emojis e termos digitais sem complicação.',
    ],
  },
  contact: {
    lead: 'Use esta página para entrar em contato com nossa equipe editorial.',
    blocks: [
      'Se quiser sugerir um termo, reportar ajuste ou enviar parceria, utilize nossos canais oficiais.',
      'Respondemos conforme a fila e prioridade de cada solicitação.',
    ],
  },
  privacy: {
    lead: 'Valorizamos privacidade e transparência no tratamento de dados.',
    blocks: [
      'Aqui você encontra informações sobre coleta, uso e proteção de dados de navegação.',
      'Recomendamos revisar periodicamente para acompanhar atualizações de compliance.',
    ],
  },
  terms: {
    lead: 'Os termos de uso estabelecem regras para acesso e utilização do conteúdo.',
    blocks: [
      'Ao navegar pelo site, você concorda com as diretrizes de uso e responsabilidade previstas nesta seção.',
      'As regras podem ser atualizadas para refletir mudanças legais e operacionais.',
    ],
  },
};

const pageContent = computed(() => contentByKey[institutionalPage.value.key] ?? {
  lead: `Informações institucionais de ${institutionalPage.value.title}.`,
  blocks: ['Conteúdo institucional em atualização.', 'Volte em breve para novas informações.'],
});
</script>

<template>
  <AppHead v-bind="seo" />
  <InstitutionalLayout :title="institutionalPage.title" page-type="institutional">
    <h1 class="h3 mb-3">{{ institutionalPage.title }}</h1>
    <p class="text-secondary">{{ pageContent.lead }}</p>

    <div class="d-grid gap-3 mt-4">
      <p v-for="(block, index) in pageContent.blocks" :key="index" class="mb-0">{{ block }}</p>
    </div>

    <div class="mt-4 d-flex gap-2 flex-wrap">
      <Link :href="route('index.home')" class="btn btn-outline-secondary btn-sm">Voltar para a Home</Link>
      <Link v-if="route().has('search.index')" :href="route('search.index')" class="btn btn-primary btn-sm">Ir para busca</Link>
    </div>
  </InstitutionalLayout>
</template>
