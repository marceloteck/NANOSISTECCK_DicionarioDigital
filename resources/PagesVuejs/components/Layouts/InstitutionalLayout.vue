<script setup>
defineProps({ title: { type: String, default: '' }, pageType: { type: String, default: 'institutional' } });
</script>
<template>
  <SiteLayout :title="title" :page-type="pageType">
    <section class="container py-5">
      <slot />
    </section>
  </SiteLayout>
</template>
