<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import AppHead from '../../components/Applications/AppHead.vue';

const page = usePage();
const seo = computed(() => page.props.seo ?? {});
const taxonomyIndex = computed(() => page.props.taxonomyIndex ?? {});
const items = computed(() => page.props.items ?? { data: [] });

const goTo = (url) => {
  router.visit(url);
};
</script>

<template>
  <AppHead v-bind="seo" />

  <ContentSiteLayout :title="taxonomyIndex.title || 'Taxonomia'" page-type="listing">
    <main>
      <section class="hero taxonomy-hero" id="inicio">
        <div class="container">
          <div class="hero-copy">
            <div class="hero-pill">
              Taxonomia • Navegação inteligente • Conteúdo organizado • Estrutura otimizada
            </div>

            <h1 class="hero-title">
              {{ taxonomyIndex.title || 'Taxonomia' }}
            </h1>

            <p class="hero-desc">
              {{
                taxonomyIndex.description ||
                'Explore os conteúdos organizados desta seção e encontre rapidamente os temas, categorias ou termos relacionados dentro do projeto.'
              }}
            </p>

            <div class="hero-actions">
              <a href="#listagem" class="btn btn-primary">Explorar itens</a>
              <a href="#paginacao" class="btn btn-outline">Ver navegação</a>
            </div>

            <div class="hero-trust">
              <span>Navegação organizada</span>
              <span>Estrutura pensada para SEO</span>
              <span>Listagem escaneável</span>
              <span>Experiência premium</span>
            </div>
          </div>
        </div>
      </section>

      <!-- ANÚNCIO 1: logo abaixo do hero -->
      <section class="taxonomy-ad-section taxonomy-ad-section-top" aria-label="Publicidade">
        <div class="container">
          <div class="taxonomy-ad-slot taxonomy-ad-slot-horizontal">
            <small>Publicidade</small>
            <div class="taxonomy-ad-box">
              Espaço estratégico para anúncio premium horizontal, banner responsivo ou bloco de monetização de alta visibilidade.
            </div>
          </div>
        </div>
      </section>

      <section class="section" id="listagem">
        <div class="container">
          <div class="section-head taxonomy-head">
            <span class="eyebrow">Explorar taxonomia</span>
            <h2>
              {{ taxonomyIndex.title || 'Itens disponíveis' }}
            </h2>
            <p>
              {{
                taxonomyIndex.description ||
                'Selecione um item da taxonomia para continuar navegando por conteúdos organizados de forma clara, elegante e útil para o usuário final.'
              }}
            </p>
          </div>

          <div v-if="items.data?.length" class="taxonomy-grid">
            <article
              v-for="item in items.data"
              :key="item.id"
              class="taxonomy-card hero-panel clickable-card"
              role="link"
              tabindex="0"
              @click="goTo(item.url)"
              @keydown.enter="goTo(item.url)"
            >
              <div class="taxonomy-card-body">
                <div class="taxonomy-meta">
                  <span>Taxonomia</span>
                  <span>•</span>
                  <span>{{ item.posts_count }} post(s)</span>
                </div>

                <h2 class="taxonomy-card-title">
                  <Link :href="item.url" class="taxonomy-card-link">
                    {{ item.name }}
                  </Link>
                </h2>

                <p class="taxonomy-card-text">
                  Reúne {{ item.posts_count }} post(s) publicados para explorar este tema com mais profundidade.
                </p>

                <div class="taxonomy-card-actions">
                  <Link :href="item.url" class="btn btn-outline">
                    Ver conteúdos
                  </Link>
                </div>
              </div>
            </article>
          </div>

          <div v-else class="empty-state-card">
            <small>Nenhum conteúdo encontrado</small>
            <h2>Nenhum item disponível no momento.</h2>
            <p>
              Assim que novos conteúdos forem adicionados a esta taxonomia, eles aparecerão aqui de forma organizada.
            </p>
          </div>

          <!-- ANÚNCIO 2: no meio/final da área de conteúdo -->
          <div class="taxonomy-ad-slot taxonomy-ad-slot-inline" aria-label="Publicidade">
            <small>Publicidade</small>
            <div class="taxonomy-ad-box">
              Espaço ideal para anúncio nativo, bloco AdSense responsivo, recomendação patrocinada ou vitrine de parceiros.
            </div>
          </div>

          <!-- ANÚNCIO 3: antes da paginação -->
          <div style="display:none;" class="taxonomy-ad-slot taxonomy-ad-slot-footer" aria-label="Publicidade">
            <small>Publicidade</small>
            <div class="taxonomy-ad-box">
              Área nobre para monetização antes da paginação, aproveitando usuários que chegaram até o fim da listagem.
            </div>
          </div>

          <div id="paginacao" class="taxonomy-pagination">
            <PostPagination :links="items.links || []" />
          </div>
        </div>
      </section>
    </main>
  </ContentSiteLayout>
</template>

<style scoped>
.clickable-card {
  cursor: pointer;
  position: relative;
}

.clickable-card:hover {
  transform: translateY(-4px);
}

.clickable-card:active {
  transform: scale(0.98);
}

.clickable-card:focus {
  outline: 2px solid var(--primary);
  outline-offset: 2px;
}

.clickable-card a {
  position: relative;
  z-index: 2;
}

.taxonomy-hero {
  padding-bottom: 20px;
}

.taxonomy-head {
  max-width: 780px;
}

.taxonomy-grid {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: 16px;
  align-items: stretch;
}

.taxonomy-card {
  border-radius: 22px;
  min-height: 100%;
  transition: var(--transition);
  overflow: hidden;
}

.taxonomy-card:hover {
  transform: translateY(-4px);
}

.taxonomy-card-body {
  padding: 20px;
  display: flex;
  flex-direction: column;
  height: 100%;
}

.taxonomy-meta {
  display: flex;
  align-items: center;
  gap: 8px;
  flex-wrap: wrap;
  color: var(--primary-strong);
  font-size: 0.82rem;
  font-weight: 700;
  margin-bottom: 10px;
  line-height: 1.4;
}

.taxonomy-card-title {
  font-size: 1.08rem;
  margin-bottom: 10px;
  letter-spacing: -0.03em;
  line-height: 1.18;
}

.taxonomy-card-link {
  color: var(--text);
  text-decoration: none;
  transition: var(--transition);
}

.taxonomy-card-link:hover {
  color: var(--primary);
}

.taxonomy-card-text {
  color: var(--muted);
  line-height: 1.72;
  margin-bottom: 16px;
  font-size: 0.95rem;
}

.taxonomy-card-actions {
  margin-top: auto;
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}

.taxonomy-card-actions .btn {
  padding: 12px 16px;
  font-size: 0.9rem;
}

/* ANÚNCIOS */
.taxonomy-ad-section {
  padding: 8px 0 0;
}

.taxonomy-ad-slot {
  margin-top: 24px;
  border-radius: 24px;
  padding: 18px;
  background: linear-gradient(180deg, var(--surface-strong), var(--surface));
  backdrop-filter: blur(18px);
  border: 1px solid var(--border);
  box-shadow: var(--shadow-lg);
}

.taxonomy-ad-slot small {
  display: block;
  color: var(--muted-2);
  font-size: 0.72rem;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  margin-bottom: 10px;
  font-weight: 700;
}

.taxonomy-ad-box {
  min-height: 120px;
  border-radius: 18px;
  border: 1px dashed rgba(109, 40, 217, 0.22);
  background: linear-gradient(180deg, var(--accent-soft), rgba(255,255,255,0.88));
  color: var(--muted);
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
  padding: 22px;
  line-height: 1.7;
}

.taxonomy-ad-slot-horizontal .taxonomy-ad-box {
  min-height: 140px;
}

.taxonomy-ad-slot-inline {
  margin-top: 28px;
}

.taxonomy-ad-slot-footer {
  margin-top: 20px;
  margin-bottom: 8px;
}

.empty-state-card {
  border-radius: 26px;
  padding: 26px;
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
  font-size: clamp(1.45rem, 3vw, 2rem);
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

.taxonomy-pagination {
  margin-top: 28px;
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

@media (max-width: 1180px) {
  .taxonomy-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}

@media (max-width: 900px) {
  .taxonomy-hero {
    padding-bottom: 14px;
  }

  .taxonomy-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 14px;
  }

  .taxonomy-card-body {
    padding: 18px;
  }

  .taxonomy-card-title {
    font-size: 1rem;
  }

  .taxonomy-card-text {
    font-size: 0.93rem;
    line-height: 1.68;
  }

  .taxonomy-ad-box {
    min-height: 110px;
    padding: 18px;
  }
}

@media (max-width: 700px) {
  .taxonomy-grid {
    grid-template-columns: 1fr;
    gap: 14px;
  }

  .taxonomy-card,
  .empty-state-card,
  .taxonomy-ad-slot {
    border-radius: 20px;
  }

  .taxonomy-card-body,
  .empty-state-card,
  .taxonomy-ad-slot {
    padding: 18px;
  }

  .taxonomy-meta {
    font-size: 0.8rem;
    gap: 6px;
    margin-bottom: 8px;
  }

  .taxonomy-card-title {
    font-size: 1rem;
    line-height: 1.2;
  }

  .taxonomy-card-text {
    font-size: 0.92rem;
    line-height: 1.65;
    margin-bottom: 14px;
  }

  .taxonomy-card-actions {
    width: 100%;
  }

  .taxonomy-card-actions .btn {
    width: 100%;
    justify-content: center;
  }

  .taxonomy-ad-box {
    min-height: 100px;
    font-size: 0.92rem;
  }
}

@media (max-width: 520px) {
  .taxonomy-head {
    max-width: 100%;
  }

  .taxonomy-grid {
    gap: 12px;
  }

  .taxonomy-card,
  .empty-state-card,
  .taxonomy-ad-slot {
    border-radius: 18px;
  }

  .taxonomy-card-body,
  .empty-state-card,
  .taxonomy-ad-slot {
    padding: 16px;
  }

  .taxonomy-meta {
    font-size: 0.76rem;
  }

  .taxonomy-card-title {
    font-size: 0.96rem;
  }

  .taxonomy-card-text {
    font-size: 0.9rem;
    line-height: 1.62;
  }

  .taxonomy-pagination :deep(nav),
  .taxonomy-pagination :deep(.pagination) {
    gap: 8px;
  }

  .taxonomy-pagination :deep(a),
  .taxonomy-pagination :deep(span) {
    min-width: 40px;
    min-height: 40px;
    padding: 8px 12px;
    font-size: 0.9rem;
  }

  .taxonomy-ad-box {
    min-height: 92px;
    padding: 16px;
    font-size: 0.88rem;
  }
}

@media (max-width: 380px) {
  .taxonomy-card-body,
  .empty-state-card,
  .taxonomy-ad-slot {
    padding: 14px;
  }

  .taxonomy-card-title {
    font-size: 0.92rem;
  }

  .taxonomy-card-text {
    font-size: 0.88rem;
  }

  .taxonomy-card-actions .btn {
    padding: 11px 14px;
    font-size: 0.86rem;
  }
}
</style>