<script setup>
import PostBreadcrumb from './PostBreadcrumb.vue';
import QuickAnswerBox from './QuickAnswerBox.vue';
import PostSectionBlock from './PostSectionBlock.vue';

defineProps({
  sections: {
    type: Array,
    required: true,
  },
});
</script>

<template>
  <article class="post-preview">
    <PostBreadcrumb />

    <small class="preview-label">Significado completo</small>
    <h2>O que significa POV?</h2>

    <p class="preview-intro">
      <strong>POV</strong> é a sigla de <strong>point of view</strong>, uma expressão em inglês que significa
      <strong>ponto de vista</strong>. Na internet, ela é usada para indicar que a cena, a piada, o vídeo ou o meme
      está sendo mostrado pela perspectiva de uma pessoa em determinada situação.
    </p>

    <QuickAnswerBox />

    <div class="post-sections">
      <PostSectionBlock
        v-for="section in sections"
        :key="section.title"
        :title="section.title"
        :paragraph="section.paragraph"
        :list="section.list"
        :related-tags="section.relatedTags"
      />
    </div>
  </article>
</template>
