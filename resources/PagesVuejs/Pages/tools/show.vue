<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const seo = computed(() => page.props.seo ?? {});
const tool = computed(() => page.props.tool ?? {});
const relatedItems = computed(() => page.props.relatedItems ?? []);
const showAdSlots = computed(() => page.props.showAdSlots ?? false);
</script>

<template>
  <AppHead v-bind="seo" />

  <ToolsSiteLayout :title="tool.title" page-type="tool">
    <article class="container py-5">
      <nav class="small mb-3 text-secondary">
        <Link :href="route('index.home')">Início</Link> / <Link :href="route('tools.index')">Ferramentas</Link>
      </nav>

      <h1 class="display-6 fw-bold">{{ tool.title }}</h1>
      <p class="lead text-secondary" v-if="tool.excerpt">{{ tool.excerpt }}</p>

      <div v-if="showAdSlots" class="ad-slot ad-slot--top mb-4">Espaço reservado para monetização futura</div>

      <section class="mt-4" v-if="tool.description" v-html="tool.description" />

      <section v-if="tool.how_to_steps?.length" class="mt-5">
        <h2 class="h4">Como usar</h2>
        <ol>
          <li v-for="(step, index) in tool.how_to_steps" :key="index">{{ step.text || step.name }}</li>
        </ol>
      </section>

      <section v-if="tool.faq_json?.length" class="mt-5">
        <h2 class="h4">FAQ</h2>
        <div v-for="item in tool.faq_json" :key="item.question" class="mb-3">
          <h3 class="h6">{{ item.question }}</h3>
          <p class="mb-0 text-secondary">{{ item.answer }}</p>
        </div>
      </section>

      <section v-if="relatedItems.length" class="mt-5">
        <h2 class="h4">Ferramentas relacionadas</h2>
        <ul class="mb-0">
          <li v-for="item in relatedItems" :key="item.id">
            <Link :href="item.url">{{ item.title }}</Link>
          </li>
        </ul>
      </section>
    </article>
  </ToolsSiteLayout>
</template>
