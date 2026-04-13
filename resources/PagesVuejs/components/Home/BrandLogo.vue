<script setup>
defineProps({
  subtitle: {
    type: String,
    default: 'Entenda a linguagem da internet',
  },
  useIcon: {
    type: Boolean,
    default: true,
  },
});
</script>

<template>
  <div class="brand">
    <div v-if="useIcon" class="brand-mark brand-mark-icon" aria-hidden="true">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" fill="none">
        <defs>
          <linearGradient id="ddIconBg" x1="8" y1="8" x2="56" y2="56" gradientUnits="userSpaceOnUse">
            <stop stop-color="#6d28d9" />
            <stop offset="0.5" stop-color="#9333ea" />
            <stop offset="1" stop-color="#c08a2b" />
          </linearGradient>

          <linearGradient id="ddIconGlass" x1="20" y1="18" x2="46" y2="44" gradientUnits="userSpaceOnUse">
            <stop stop-color="#ffffff" stop-opacity="0.98" />
            <stop offset="1" stop-color="#f4ecff" stop-opacity="0.92" />
          </linearGradient>
        </defs>

        <rect x="4" y="4" width="56" height="56" rx="18" fill="url(#ddIconBg)" />

        <path d="M20 18.5C20 16.567 21.567 15 23.5 15H36.5C41.747 15 46 19.253 46 24.5V37.5C46 39.433 44.433 41 42.5 41H29.5C24.253 41 20 36.747 20 31.5V18.5Z" fill="url(#ddIconGlass)" />

        <path d="M24 21.5H36" stroke="#6d28d9" stroke-width="2.2" stroke-linecap="round" />
        <path d="M24 26.5H39" stroke="#9333ea" stroke-width="2.2" stroke-linecap="round" />
        <path d="M24 31.5H34" stroke="#c08a2b" stroke-width="2.2" stroke-linecap="round" />

        <circle cx="40.5" cy="40.5" r="6.5" fill="none" stroke="#ffffff" stroke-width="3" />
        <path d="M45.3 45.3L50 50" stroke="#ffffff" stroke-width="3" stroke-linecap="round" />

        <path d="M17 47.5C17 46.119 18.119 45 19.5 45H31.5" stroke="#ffffff" stroke-width="2.2" stroke-linecap="round" opacity="0.92" />
        <path d="M17 42.5C17 41.119 18.119 40 19.5 40H24" stroke="#ffffff" stroke-width="2.2" stroke-linecap="round" opacity="0.72" />
      </svg>
    </div>

    <div v-else class="brand-mark" aria-hidden="true">DD</div>

    <div class="brand-text">
      <strong>Dicionário Digital</strong>
      <span>{{ subtitle }}</span>
    </div>
  </div>
</template>
