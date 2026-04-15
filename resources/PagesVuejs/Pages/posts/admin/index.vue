<script setup>
import { computed, ref } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';

const page = usePage();
const deletingPostId = ref(null);
const posts = computed(() => page.props.posts);
const flash = computed(() => page.props.flash ?? {});

const hasPosts = computed(() => (posts.value?.data?.length ?? 0) > 0);
const paginationLinks = computed(() => posts.value?.links ?? []);

const formatDateTime = (value) => {
  if (!value) return '—';

  const parsedDate = new Date(value);

  if (Number.isNaN(parsedDate.getTime())) {
    return value;
  }

  return new Intl.DateTimeFormat('pt-BR', {
    dateStyle: 'short',
    timeStyle: 'short',
  }).format(parsedDate);
};

const normalizePaginationLabel = (label) => {
  if (label.includes('Previous')) return 'Anterior';
  if (label.includes('Next')) return 'Próxima';
  return label.replace('&laquo;', '').replace('&raquo;', '').trim();
};

const statusBadgeClass = (post) => {
  if (post.status === 'published' || post.is_published) {
    return 'status-badge status-badge--published';
  }

  if (post.status === 'archived') {
    return 'status-badge status-badge--archived';
  }

  return 'status-badge status-badge--draft';
};

const statusLabel = (status) => {
  if (!status) return 'Sem status';

  const map = {
    published: 'Publicado',
    draft: 'Rascunho',
    archived: 'Arquivado',
  };

  return map[status] ?? status;
};

const confirmAndDelete = (post) => {
  if (deletingPostId.value !== null) return;

  const shouldDelete = window.confirm(`Tem certeza que deseja excluir o post "${post.title}"? Essa ação não pode ser desfeita.`);

  if (!shouldDelete) return;

  deletingPostId.value = post.id;

  router.delete(route('admin.posts.destroy', post.slug), {
    preserveScroll: true,
    preserveState: true,
    onFinish: () => {
      deletingPostId.value = null;
    },
  });
};
</script>

<template>
      <AppHead
    title="Postagens"
  />
  <main class="post-admin py-4">
    <div class="container">
      <header class="post-admin__header">
        <div>
          <p class="post-admin__eyebrow">Painel administrativo</p>
          <h1 class="post-admin__title">Posts</h1>
          <p class="post-admin__subtitle">
            Gerencie conteúdos com performance para grandes volumes e fluxo editorial contínuo.
          </p>
        </div>

        <Link :href="route('admin.posts.create')" class="btn btn-primary post-admin__create-button">
          Novo post
        </Link>
      </header>

      <div v-if="flash.success" class="alert alert-success shadow-sm" role="alert">
        {{ flash.success }}
      </div>
      <div v-if="flash.error" class="alert alert-danger shadow-sm" role="alert">
        {{ flash.error }}
      </div>

      <section class="post-admin__table-wrapper">
        <div class="post-admin__table-head">
          <h2 class="post-admin__section-title">Listagem</h2>
          <small class="text-muted">
            <template v-if="posts.total > 0">
              Mostrando {{ posts.from }}–{{ posts.to }} de {{ posts.total }} posts
            </template>
            <template v-else>
              Nenhum post encontrado
            </template>
          </small>
        </div>

        <div v-if="hasPosts" class="table-responsive">
          <table class="table align-middle mb-0 post-admin__table">
            <thead>
              <tr>
                <th>Título</th>
                <th>Status</th>
                <th>Categoria</th>
                <th>Atualizado em</th>
                <th class="text-end">Ações</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="post in posts.data" :key="post.id" class="post-admin__row">
                <td>
                  <div class="fw-semibold text-dark">{{ post.title }}</div>
                  <small class="text-muted">{{ post.slug }}</small>
                </td>
                <td>
                  <span :class="statusBadgeClass(post)">
                    {{ statusLabel(post.status) }}
                  </span>
                </td>
                <td>{{ post.category || 'Sem categoria' }}</td>
                <td>{{ formatDateTime(post.updated_at) }}</td>
                <td>
                  <div class="d-flex gap-2 justify-content-end flex-wrap">
                    <Link :href="route('admin.posts.edit', post.slug)" class="btn btn-sm btn-outline-primary">
                      Editar
                    </Link>
                    <a
                      :href="post.is_published ? route('posts.show', post.slug) : post.preview_url"
                      target="_blank"
                      rel="noopener noreferrer"
                      class="btn btn-sm btn-outline-dark"
                    >
                      {{ post.is_published ? 'Ver público' : 'Preview draft' }}
                    </a>
                    <button
                      type="button"
                      class="btn btn-sm btn-outline-danger"
                      :disabled="deletingPostId === post.id"
                      @click="confirmAndDelete(post)"
                    >
                      {{ deletingPostId === post.id ? 'Excluindo...' : 'Excluir' }}
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div v-else class="post-admin__empty-state">
          <h3>Nenhum post cadastrado</h3>
          <p>Comece criando seu primeiro conteúdo para popular a base de conhecimento do projeto.</p>
          <Link :href="route('admin.posts.create')" class="btn btn-primary">Criar primeiro post</Link>
        </div>

        <nav v-if="hasPosts && posts.last_page > 1" class="post-admin__pagination" aria-label="Navegação da paginação">
          <ul class="pagination justify-content-center mb-0 flex-wrap">
            <li
              v-for="link in paginationLinks"
              :key="`${link.label}-${link.url}`"
              class="page-item"
              :class="{ active: link.active, disabled: !link.url }"
            >
              <Link
                v-if="link.url"
                :href="link.url"
                class="page-link"
                preserve-scroll
                preserve-state
                v-html="normalizePaginationLabel(link.label)"
              />
              <span v-else class="page-link" v-html="normalizePaginationLabel(link.label)" />
            </li>
          </ul>
        </nav>
      </section>
    </div>
  </main>
</template>

<style scoped>
.post-admin {
  background: linear-gradient(180deg, #f8fafc 0%, #f3f4f6 100%);
  min-height: calc(100vh - 4rem);
}

.post-admin__header {
  align-items: start;
  display: flex;
  gap: 1rem;
  justify-content: space-between;
  margin-bottom: 1.5rem;
}

.post-admin__eyebrow {
  color: #64748b;
  font-size: 0.8125rem;
  font-weight: 600;
  letter-spacing: 0.06em;
  margin-bottom: 0.25rem;
  text-transform: uppercase;
}

.post-admin__title {
  color: #0f172a;
  font-size: clamp(1.45rem, 2vw, 1.9rem);
  font-weight: 700;
  margin-bottom: 0.35rem;
}

.post-admin__subtitle {
  color: #475569;
  margin: 0;
}

.post-admin__create-button {
  border-radius: 0.65rem;
  box-shadow: 0 10px 20px rgba(37, 99, 235, 0.16);
  font-weight: 600;
  padding-inline: 1rem;
  white-space: nowrap;
}

.post-admin__table-wrapper {
  background-color: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 1rem;
  box-shadow: 0 15px 35px rgba(15, 23, 42, 0.06);
  overflow: hidden;
}

.post-admin__table-head {
  align-items: center;
  border-bottom: 1px solid #e2e8f0;
  display: flex;
  justify-content: space-between;
  padding: 1rem 1.25rem;
}

.post-admin__section-title {
  font-size: 1rem;
  font-weight: 700;
  margin: 0;
}

.post-admin__table thead th {
  background-color: #f8fafc;
  border-bottom-width: 1px;
  color: #475569;
  font-size: 0.8rem;
  font-weight: 700;
  letter-spacing: 0.02em;
  text-transform: uppercase;
}

.post-admin__row:hover {
  background-color: #f8fafc;
}

.status-badge {
  border-radius: 999px;
  display: inline-flex;
  font-size: 0.75rem;
  font-weight: 700;
  padding: 0.35rem 0.65rem;
}

.status-badge--published {
  background-color: #dcfce7;
  color: #166534;
}

.status-badge--draft {
  background-color: #e2e8f0;
  color: #334155;
}

.status-badge--archived {
  background-color: #fee2e2;
  color: #991b1b;
}

.post-admin__empty-state {
  margin: 2.25rem auto;
  max-width: 480px;
  padding: 1.5rem;
  text-align: center;
}

.post-admin__empty-state h3 {
  color: #0f172a;
  font-size: 1.2rem;
}

.post-admin__empty-state p {
  color: #64748b;
}

.post-admin__pagination {
  border-top: 1px solid #e2e8f0;
  padding: 1rem;
}

@media (max-width: 768px) {
  .post-admin__header {
    align-items: stretch;
    flex-direction: column;
  }

  .post-admin__create-button {
    width: 100%;
  }
}
</style>
