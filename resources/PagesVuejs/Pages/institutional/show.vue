<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const seo = computed(() => page.props.seo ?? {});
const institutionalPage = computed(() => page.props.institutionalPage ?? {});
</script>

<template>
  <AppHead v-bind="seo" />
  <InstitutionalLayout :title="institutionalPage.title" page-type="institutional">
    <h1 class="h3 mb-3">{{ institutionalPage.title }}</h1>
    <p class="text-secondary">
      Conteúdo institucional base para {{ institutionalPage.title }}. Edite este template para refletir o projeto final.
    </p>
  </InstitutionalLayout>
</template>
