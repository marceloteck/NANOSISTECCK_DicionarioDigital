<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AuthLayout from '../components/Layouts/AuthLayout.vue';

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
});

const submit = () => {
  form.post(route('register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};
</script>

<template>
  <AuthLayout
    title="Criar conta"
    subtitle="Cadastre-se para salvar preferências, acompanhar conteúdos e acessar recursos exclusivos com segurança."
  >
    <header class="mb-4">
      <h2 class="form-title">Criar novo cadastro</h2>
      <p class="form-subtitle mb-0">Leva menos de 1 minuto para começar.</p>
    </header>

    <form @submit.prevent="submit" class="d-grid gap-3" novalidate>
      <div>
        <label for="name" class="form-label">Nome</label>
        <input
          id="name"
          v-model="form.name"
          type="text"
          class="form-control form-control-lg"
          autocomplete="name"
          required
          autofocus
          :class="{ 'is-invalid': form.errors.name }"
        >
        <div v-if="form.errors.name" class="invalid-feedback d-block">{{ form.errors.name }}</div>
      </div>

      <div>
        <label for="email" class="form-label">E-mail</label>
        <input
          id="email"
          v-model="form.email"
          type="email"
          class="form-control form-control-lg"
          autocomplete="username"
          required
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
          autocomplete="new-password"
          required
          :class="{ 'is-invalid': form.errors.password }"
        >
        <div v-if="form.errors.password" class="invalid-feedback d-block">{{ form.errors.password }}</div>
      </div>

      <div>
        <label for="password_confirmation" class="form-label">Confirmar senha</label>
        <input
          id="password_confirmation"
          v-model="form.password_confirmation"
          type="password"
          class="form-control form-control-lg"
          autocomplete="new-password"
          required
        >
      </div>

      <button type="submit" class="btn auth-btn-primary w-100" :disabled="form.processing">
        <span v-if="form.processing">Criando conta...</span>
        <span v-else>Criar conta</span>
      </button>
    </form>

    <div class="mt-4 small">
      <Link :href="route('login')" class="auth-link">Já possui conta? Fazer login</Link>
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
