<script setup>
import { computed } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import AppHead from '../../components/Applications/AppHead.vue';

const page = usePage();
const seo = computed(() => page.props.seo ?? {});
const taxonomy = computed(() => page.props.taxonomy ?? {});
const posts = computed(() => page.props.posts ?? { data: [] });

const taxonomyLabel = computed(() =>
  taxonomy.value.type === 'category' ? 'Categoria' : 'Tag'
);

const taxonomyDescription = computed(() =>
  taxonomy.value.type === 'category'
    ? `Explore os conteúdos publicados nesta categoria com navegação clara, visual refinado e uma experiência pensada para retenção e descoberta.`
    : `Explore os conteúdos associados a esta tag com uma experiência visual elegante, leitura escaneável e navegação mais inteligente.`
);

const goToPost = (slug) => {
  if (!slug) return;
  router.visit(route('posts.show', slug));
};
</script>

<template>
  <AppHead v-bind="seo" />

  <ContentSiteLayout title="Posts" page-type="listing">
    <main>
      <section class="hero taxonomy-posts-hero" id="inicio">
        <div class="container">
          <div class="hero-copy">
            <div class="hero-pill">
              {{ taxonomyLabel }} • Curadoria editorial • Navegação premium • Estrutura otimizada
            </div>

            <h1 class="hero-title">
              {{ taxonomyLabel }}:
              <span class="gradient">{{ taxonomy.name }}</span>
            </h1>

            <p class="hero-desc">
              {{ taxonomyDescription }}
            </p>

            <div class="hero-actions">
              <a href="#posts-listagem" class="btn btn-primary">Explorar posts</a>
              <a href="#posts-paginacao" class="btn btn-outline">Navegar páginas</a>
            </div>

            <div class="hero-trust">
              <span>Leitura escaneável</span>
              <span>Cards premium</span>
              <span>Visual elegante</span>
              <span>Totalmente responsivo</span>
            </div>
          </div>
        </div>
      </section>

      <section class="section taxonomy-posts-section" id="posts-listagem">
        <div class="container">
          <div class="section-head taxonomy-posts-head">
            <span class="eyebrow">Posts relacionados</span>
            <h2>
              Conteúdos em {{ taxonomyLabel.toLowerCase() }} {{ taxonomy.name }}
            </h2>
            <p>
              Descubra os posts relacionados com uma navegação clara, elegante e feita para facilitar a continuidade da leitura.
            </p>
          </div>

          <div v-if="posts.data.length > 0" class="taxonomy-posts-grid">
            <article
              v-for="post in posts.data"
              :key="post.id"
              class="taxonomy-post-card clickable-card"
              role="link"
              tabindex="0"
              @click="goToPost(post.slug)"
              @keydown.enter="goToPost(post.slug)"
            >
              <div class="taxonomy-post-card-body">
                <div class="taxonomy-post-meta">
                  <span>{{ taxonomyLabel }}</span>
                  <span>•</span>
                  <span>{{ taxonomy.name }}</span>
                </div>

                <h2 class="taxonomy-post-card-title">
                  <Link
                    :href="route('posts.show', post.slug)"
                    class="taxonomy-post-card-link"
                  >
                    {{ post.title }}
                  </Link>
                </h2>

                <p class="taxonomy-post-card-text">
                  {{ post.excerpt }}
                </p>

                <div class="taxonomy-post-card-actions">
                  <Link
                    :href="route('posts.show', post.slug)"
                    class="taxonomy-post-card-action"
                  >
                    Ler conteúdo
                    <span aria-hidden="true">→</span>
                  </Link>
                </div>
              </div>
            </article>
          </div>

          <div v-else class="empty-state-card">
            <small>Nenhum conteúdo encontrado</small>
            <h2>Nenhum post encontrado para este filtro.</h2>
            <p>
              Quando novos conteúdos forem publicados para esta
              {{ taxonomy.type === 'category' ? 'categoria' : 'tag' }},
              eles aparecerão aqui de forma organizada.
            </p>
          </div>

          <div id="posts-paginacao" class="taxonomy-pagination">
            <PostPagination :links="posts.links || []" />
          </div>
        </div>
      </section>
    </main>
  </ContentSiteLayout>
</template>

<style scoped>
.taxonomy-posts-hero {
  padding-bottom: 18px;
}

.taxonomy-posts-section {
  padding-top: 56px;
}

.taxonomy-posts-head {
  max-width: 760px;
}

.taxonomy-posts-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 18px;
  align-items: stretch;
}

/* CARD MAIS PREMIUM */
.taxonomy-post-card {
  position: relative;
  min-height: 100%;
  border-radius: 26px;
  overflow: hidden;
  cursor: pointer;
  transition: var(--transition);
  background:
    linear-gradient(180deg, rgba(255,255,255,0.96), rgba(255,255,255,0.84));
  border: 1px solid rgba(42, 31, 56, 0.08);
  box-shadow:
    0 20px 50px rgba(31, 23, 40, 0.08),
    inset 0 1px 0 rgba(255,255,255,0.7);
  backdrop-filter: blur(14px);
}

.taxonomy-post-card::before {
  content: "";
  position: absolute;
  inset: 0;
  background:
    radial-gradient(circle at top right, rgba(109, 40, 217, 0.08), transparent 28%),
    radial-gradient(circle at bottom left, rgba(192, 138, 43, 0.06), transparent 24%);
  pointer-events: none;
}

.taxonomy-post-card:hover {
  transform: translateY(-6px);
  box-shadow:
    0 28px 70px rgba(31, 23, 40, 0.11),
    inset 0 1px 0 rgba(255,255,255,0.78);
  border-color: rgba(109, 40, 217, 0.12);
}

.taxonomy-post-card:active {
  transform: translateY(-2px) scale(0.99);
}

.taxonomy-post-card:focus {
  outline: 2px solid var(--primary);
  outline-offset: 3px;
}

.taxonomy-post-card-body {
  position: relative;
  z-index: 2;
  padding: 24px;
  display: flex;
  flex-direction: column;
  height: 100%;
}

.taxonomy-post-meta {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  flex-wrap: wrap;
  width: fit-content;
  padding: 8px 12px;
  margin-bottom: 14px;
  border-radius: 999px;
  background: rgba(109, 40, 217, 0.08);
  border: 1px solid rgba(109, 40, 217, 0.10);
  color: var(--primary-strong);
  font-size: 0.78rem;
  font-weight: 800;
  letter-spacing: 0.01em;
}

.taxonomy-post-card-title {
  font-size: 1.2rem;
  line-height: 1.18;
  letter-spacing: -0.035em;
  margin-bottom: 12px;
}

.taxonomy-post-card-link {
  color: var(--text);
  text-decoration: none;
  transition: var(--transition);
}

.taxonomy-post-card:hover .taxonomy-post-card-link,
.taxonomy-post-card-link:hover {
  color: var(--primary);
}

.taxonomy-post-card-text {
  color: var(--muted);
  font-size: 0.97rem;
  line-height: 1.78;
  margin-bottom: 20px;

  display: -webkit-box;
  -webkit-line-clamp: 4;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.taxonomy-post-card-actions {
  margin-top: auto;
  display: flex;
  align-items: center;
}

.taxonomy-post-card-action {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  color: var(--primary);
  font-weight: 800;
  text-decoration: none;
  transition: var(--transition);
}

.taxonomy-post-card-action span {
  transition: transform 180ms ease;
}

.taxonomy-post-card:hover .taxonomy-post-card-action span {
  transform: translateX(3px);
}

.taxonomy-post-card-action:hover {
  color: var(--primary-strong);
}

/* Estado vazio */
.empty-state-card {
  border-radius: 28px;
  padding: 30px;
  background: linear-gradient(180deg, var(--surface-strong), var(--surface));
  backdrop-filter: blur(18px);
  border: 1px solid var(--border);
  box-shadow: var(--shadow-lg);
}

.empty-state-card small {
  display: block;
  color: var(--muted-2);
  font-size: 0.72rem;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  margin-bottom: 8px;
  font-weight: 700;
}

.empty-state-card h2 {
  font-size: clamp(1.5rem, 3vw, 2rem);
  margin-bottom: 10px;
  letter-spacing: -0.03em;
  line-height: 1.08;
}

.empty-state-card p {
  color: var(--muted);
  line-height: 1.8;
  margin: 0;
  font-size: 0.98rem;
}

/* Paginação */
.taxonomy-pagination {
  margin-top: 30px;
}

.taxonomy-pagination :deep(nav),
.taxonomy-pagination :deep(.pagination) {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 10px;
}

.taxonomy-pagination :deep(a),
.taxonomy-pagination :deep(span) {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 44px;
  min-height: 44px;
  padding: 10px 14px;
  border-radius: 999px;
  background: rgba(255, 255, 255, 0.88);
  border: 1px solid var(--border);
  color: var(--text);
  font-weight: 700;
  text-decoration: none;
  transition: var(--transition);
  box-shadow: var(--shadow-md);
}

.taxonomy-pagination :deep(a:hover) {
  transform: translateY(-2px);
  border-color: rgba(109, 40, 217, 0.16);
  color: var(--primary);
}

.taxonomy-pagination :deep(.active span),
.taxonomy-pagination :deep(span[aria-current='page']) {
  background: linear-gradient(135deg, var(--primary), var(--accent));
  color: #fff;
  border-color: transparent;
  box-shadow: 0 12px 30px rgba(109, 40, 217, 0.18);
}

/* RESPONSIVIDADE */
@media (max-width: 1180px) {
  .taxonomy-posts-grid {
    gap: 16px;
  }
}

@media (max-width: 900px) {
  .taxonomy-posts-grid {
    grid-template-columns: 1fr;
    gap: 14px;
  }

  .taxonomy-post-card-body {
    padding: 20px;
  }

  .taxonomy-post-card-title {
    font-size: 1.08rem;
  }

  .taxonomy-post-card-text {
    font-size: 0.94rem;
    -webkit-line-clamp: 5;
  }
}

@media (max-width: 700px) {
  .taxonomy-posts-section {
    padding-top: 46px;
  }

  .taxonomy-post-card,
  .empty-state-card {
    border-radius: 22px;
  }

  .taxonomy-post-card-body,
  .empty-state-card {
    padding: 18px;
  }

  .taxonomy-post-meta {
    margin-bottom: 12px;
    font-size: 0.74rem;
    padding: 7px 11px;
  }

  .taxonomy-post-card-title {
    font-size: 1rem;
  }

  .taxonomy-post-card-text {
    font-size: 0.92rem;
    line-height: 1.68;
    margin-bottom: 16px;
    -webkit-line-clamp: 5;
  }
}

@media (max-width: 520px) {
  .taxonomy-posts-head {
    max-width: 100%;
  }

  .taxonomy-posts-grid {
    gap: 12px;
  }

  .taxonomy-post-card,
  .empty-state-card {
    border-radius: 18px;
  }

  .taxonomy-post-card-body,
  .empty-state-card {
    padding: 16px;
  }

  .taxonomy-post-card-title {
    font-size: 0.96rem;
  }

  .taxonomy-post-card-text {
    font-size: 0.9rem;
    line-height: 1.62;
    -webkit-line-clamp: 6;
  }

  .taxonomy-post-card-action {
    width: 100%;
    justify-content: space-between;
  }

  .taxonomy-pagination :deep(a),
  .taxonomy-pagination :deep(span) {
    min-width: 40px;
    min-height: 40px;
    padding: 8px 12px;
    font-size: 0.9rem;
  }
}

@media (max-width: 380px) {
  .taxonomy-post-card-body,
  .empty-state-card {
    padding: 14px;
  }

  .taxonomy-post-card-title {
    font-size: 0.92rem;
  }

  .taxonomy-post-card-text {
    font-size: 0.88rem;
  }
}
</style>