<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
  <div class="breadcrumb">
    <span><Link :href="route('index.home')">Início</Link></span>
    <span>›</span>
    <span><a href="#">Gírias da Internet</a></span>
    <span>›</span>
    <span>POV</span>
  </div>
</template>
