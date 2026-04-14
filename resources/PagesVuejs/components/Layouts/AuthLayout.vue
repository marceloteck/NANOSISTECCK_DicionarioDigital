<script setup>
import { computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';

const props = defineProps({
  title: { type: String, required: true },
  subtitle: { type: String, default: '' },
});

const page = usePage();
const projectName = computed(() => page.props?.project?.name || 'NANOSISTECCK');
</script>

<template>
  <Head :title="title" />

  <div class="auth-shell">
    <div class="auth-bg"></div>
    <main class="auth-container container">
      <section class="auth-brand-panel" aria-label="Informações da plataforma">
        <Link :href="route('index.home')" class="auth-brand text-decoration-none">
          <span class="brand-mark">N</span>
          <span class="brand-text">
            <strong>{{ projectName }}</strong>
            <small>Dicionário Digital</small>
          </span>
        </Link>

        <h1 class="auth-title">Acesse sua conta com segurança.</h1>
        <p class="auth-subtitle">{{ subtitle }}</p>

        <ul class="auth-trust-list">
          <li>Fluxo de autenticação protegido com Laravel + Inertia</li>
          <li>Experiência otimizada para desktop e mobile</li>
          <li>Design consistente com a identidade visual do projeto</li>
        </ul>
      </section>

      <section class="auth-card" aria-label="Formulário de autenticação">
        <slot />
      </section>
    </main>
  </div>
</template>

<style scoped>
.auth-shell {
  position: relative;
  min-height: 100vh;
  padding: 2rem 0;
  display: flex;
  align-items: center;
}

.auth-bg {
  position: absolute;
  inset: 0;
  background:
    radial-gradient(circle at 10% 10%, rgba(109, 40, 217, 0.14), transparent 28%),
    radial-gradient(circle at 90% 20%, rgba(192, 138, 43, 0.14), transparent 26%),
    radial-gradient(circle at 50% 85%, rgba(147, 51, 234, 0.12), transparent 32%);
  pointer-events: none;
}

.auth-container {
  position: relative;
  z-index: 1;
  width: min(95%, 1200px);
  display: grid;
  grid-template-columns: 1.1fr 0.9fr;
  gap: 1.5rem;
}

.auth-brand-panel,
.auth-card {
  border: 1px solid var(--border);
  border-radius: 24px;
  background: linear-gradient(180deg, var(--surface-strong), var(--surface));
  box-shadow: var(--shadow-lg);
}

.auth-brand-panel {
  padding: 2rem;
}

.auth-brand {
  display: inline-flex;
  align-items: center;
  gap: 0.9rem;
  margin-bottom: 2rem;
  color: var(--text);
}

.brand-mark {
  width: 46px;
  height: 46px;
  border-radius: 14px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, var(--primary), var(--accent));
  color: #fff;
  font-weight: 800;
}

.brand-text strong {
  display: block;
  line-height: 1.1;
}

.brand-text small {
  color: var(--muted);
  text-transform: uppercase;
  letter-spacing: 0.08em;
  font-size: 0.72rem;
}

.auth-title {
  font-size: clamp(1.8rem, 3.8vw, 2.8rem);
  line-height: 1.1;
  margin-bottom: 1rem;
}

.auth-subtitle {
  color: var(--muted);
  margin-bottom: 1.5rem;
}

.auth-trust-list {
  padding-left: 1.2rem;
  color: var(--muted);
  display: grid;
  gap: 0.6rem;
}

.auth-card {
  padding: 2rem;
}

@media (max-width: 992px) {
  .auth-container {
    grid-template-columns: 1fr;
  }

  .auth-brand-panel {
    padding: 1.5rem;
  }

  .auth-card {
    padding: 1.5rem;
  }
}
</style>
