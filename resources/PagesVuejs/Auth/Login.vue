<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AuthLayout from '../components/Layouts/AuthLayout.vue';

defineProps({
  canResetPassword: { type: Boolean, default: false },
  status: { type: String, default: '' },
});

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

const submit = () => {
  form.post(route('login'), {
    onFinish: () => form.reset('password'),
  });
};
</script>

<template>
  <AuthLayout
    title="Entrar"
    subtitle="Entre para continuar sua experiência no NANOSISTECCK Dicionário Digital com autenticação segura e rápida."
  >
    <header class="mb-4">
      <h2 class="form-title">Bem-vindo de volta</h2>
      <p class="form-subtitle mb-0">Preencha seus dados para acessar sua conta.</p>
    </header>

    <div v-if="status" class="alert alert-success py-2" role="status">
      {{ status }}
    </div>

    <form @submit.prevent="submit" class="d-grid gap-3" novalidate>
      <div>
        <label for="email" class="form-label">E-mail</label>
        <input
          id="email"
          v-model="form.email"
          type="email"
          class="form-control form-control-lg"
          autocomplete="username"
          required
          autofocus
          :class="{ 'is-invalid': form.errors.email }"
        >
        <div v-if="form.errors.email" class="invalid-feedback d-block">{{ form.errors.email }}</div>
      </div>

      <div>
        <label for="password" class="form-label">Senha</label>
        <input
          id="password"
          v-model="form.password"
          type="password"
          class="form-control form-control-lg"
          autocomplete="current-password"
          required
          :class="{ 'is-invalid': form.errors.password }"
        >
        <div v-if="form.errors.password" class="invalid-feedback d-block">{{ form.errors.password }}</div>
      </div>

      <label class="form-check d-flex align-items-center gap-2 text-muted small">
        <input v-model="form.remember" class="form-check-input" type="checkbox" name="remember">
        <span>Lembrar meu acesso neste dispositivo</span>
      </label>

      <button type="submit" class="btn auth-btn-primary w-100" :disabled="form.processing">
        <span v-if="form.processing">Entrando...</span>
        <span v-else>Entrar na conta</span>
      </button>
    </form>

    <div class="mt-4 d-flex flex-column flex-sm-row justify-content-between gap-2 small">
      <Link :href="route('register')" class="auth-link">Não possui conta? Criar cadastro</Link>
      <Link v-if="canResetPassword" :href="route('password.request')" class="auth-link">Esqueci minha senha</Link>
    </div>
  </AuthLayout>
</template>

<style scoped>
.form-title {
  font-size: 1.7rem;
  letter-spacing: -0.02em;
}

.form-subtitle {
  color: var(--muted);
}

.form-control {
  border-radius: 14px;
  border: 1px solid var(--border-strong);
  background: rgba(255, 255, 255, 0.95);
}

.form-control:focus {
  box-shadow: 0 0 0 0.2rem rgba(109, 40, 217, 0.15);
  border-color: rgba(109, 40, 217, 0.5);
}

.auth-btn-primary {
  min-height: 52px;
  border-radius: 14px;
  background: linear-gradient(135deg, var(--primary), var(--accent));
  color: #fff;
  font-weight: 700;
  border: none;
}

.auth-btn-primary:disabled {
  opacity: 0.8;
}

.auth-link {
  color: var(--primary-strong);
  font-weight: 600;
}
</style>
