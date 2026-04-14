<script setup>
defineProps({
  title: {
    type: String,
    required: true,
  },
  paragraph: {
    type: String,
    default: '',
  },
  list: {
    type: Array,
    default: () => [],
  },
  relatedTags: {
    type: Array,
    default: () => [],
  },
});
</script>

<template>
  <section>
    <h3>{{ title }}</h3>
    <p v-if="paragraph" v-html="paragraph" />

    <ul v-if="list.length">
      <li v-for="item in list" :key="item" v-html="item" />
    </ul>

    <div v-if="relatedTags.length" class="related-tags">
      <a v-for="tag in relatedTags" :key="tag" href="#">{{ tag }}</a>
    </div>
  </section>
</template>
