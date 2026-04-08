<script setup>
defineProps({ title: { type: String, default: '' }, pageType: { type: String, default: '' } });
</script>
<template>
  <SiteLayout :title="title" :page-type="pageType">
    <slot />
  </SiteLayout>
</template>
