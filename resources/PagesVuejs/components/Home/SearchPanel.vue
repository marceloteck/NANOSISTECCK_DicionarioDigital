<script setup>
defineProps({
  suggestions: {
    type: Array,
    required: true,
  },
  previews: {
    type: Array,
    required: true,
  },
});
</script>

<template>
  <aside class="hero-panel">
    <div class="panel-inner">
      <div class="status-bar">
        <div class="status-block">
          <small>Busca</small>
          <strong>Rápida e inteligente</strong>
        </div>
        <div class="status-block">
          <small>Conteúdo</small>
          <strong>Direto ao ponto</strong>
        </div>
        <div class="status-block">
          <small>Experiência</small>
          <strong>Elegante e suave</strong>
        </div>
      </div>

      <div class="search-box" id="busca">
        <small class="search-label">Pesquise como quiser</small>

        <form class="smart-search-form" role="search">
          <input type="search" placeholder="Ex.: o que significa POV no TikTok?" aria-label="Campo de busca" />
          <button type="submit" class="btn btn-primary">Pesquisar</button>
        </form>

        <div class="search-suggestions">
          <button v-for="item in suggestions" :key="item" type="button">{{ item }}</button>
        </div>
      </div>

      <div class="search-preview">
        <div v-for="item in previews" :key="item.title" class="preview-card">
          <small>{{ item.label }}</small>
          <strong>{{ item.title }}</strong>
          <p>{{ item.description }}</p>
        </div>
      </div>
    </div>
  </aside>
</template>
