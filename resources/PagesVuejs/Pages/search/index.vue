<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();

const seo = computed(() => page.props.seo ?? {});
const results = computed(() => page.props.results ?? { data: [], links: [] });
const query = computed(() => page.props.query ?? '');
const tools = computed(() => page.props.tools ?? []);
const featuredCategories = computed(() => page.props.featuredCategories ?? []);
const trendingPosts = computed(() => page.props.trendingPosts ?? []);
const totalResults = computed(() => page.props.totalResults ?? results.value?.data?.length ?? 0);

const recentPosts = ref([]);

const RECENT_POSTS_KEY = 'nanosistecck_recent_posts';

const normalizeRecentPosts = (items) => {
  if (!Array.isArray(items)) return [];

  return items
    .filter((item) => item && item.title && item.url)
    .slice(0, 8);
};

const loadRecentPosts = () => {
  try {
    const stored = localStorage.getItem(RECENT_POSTS_KEY);
    recentPosts.value = stored ? normalizeRecentPosts(JSON.parse(stored)) : [];
  } catch (error) {
    recentPosts.value = [];
  }
};

const saveResultPostsAsRecent = () => {
  if (typeof window === 'undefined') return;
  if (!results.value?.data?.length) return;

  try {
    const current = recentPosts.value ?? [];

    const merged = [
      ...results.value.data.map((item) => ({
        id: item.id,
        title: item.title,
        url: item.url,
        excerpt: item.excerpt ?? '',
        category: item.category ?? null,
      })),
      ...current,
    ];

    const unique = [];
    const seen = new Set();

    for (const item of merged) {
      const key = item.url;
      if (!seen.has(key)) {
        seen.add(key);
        unique.push(item);
      }
    }

    recentPosts.value = unique.slice(0, 8);
    localStorage.setItem(RECENT_POSTS_KEY, JSON.stringify(recentPosts.value));
  } catch (error) {
    //
  }
};

const shouldRenderInlineAd = (index) => {
  return index === 2 || index === 7;
};

onMounted(() => {
  loadRecentPosts();
  saveResultPostsAsRecent();
});

watch(
  () => results.value?.data,
  () => {
    saveResultPostsAsRecent();
  },
  { deep: true }
);
</script>

<template>
  <AppHead v-bind="seo" />

  <HybridSiteLayout title="Buscar" page-type="search">
    <section class="search-page py-4 py-lg-5">
      <div class="container">
        <div class="search-hero">
          <div class="search-hero__content">
            <span class="search-hero__eyebrow">Encontre o que você procura</span>
            <h1 class="search-hero__title">Busque significados, termos, gírias e conteúdos úteis em poucos segundos</h1>
            <p class="search-hero__description">
              Pesquise palavras, expressões, abreviações, assuntos populares e conteúdos explicativos.
              Aqui você encontra respostas rápidas, descobre novos temas e continua navegando por conteúdos relacionados.
            </p>
          </div>

          <form
            class="search-form-card"
            :action="route('search.index')"
            method="get"
            role="search"
          >
            <label for="search-input" class="search-form-card__label">
              O que você deseja descobrir hoje?
            </label>

            <div class="search-form-card__row">
              <input
                id="search-input"
                type="search"
                name="q"
                :value="query"
                class="form-control search-form-card__input"
                placeholder="Digite uma palavra, expressão ou assunto..."
                autocomplete="off"
              />
              <button type="submit" class="btn btn-primary search-form-card__button">
                Buscar agora
              </button>
            </div>

            <p class="search-form-card__meta">
              <strong>{{ totalResults }}</strong> resultado(s) encontrado(s) para:
              <strong>{{ query || 'todos os conteúdos' }}</strong>
            </p>
          </form>
        </div>

        <div class="search-layout">
          <main class="search-main">
            <div v-if="results.data.length > 0" class="search-results">
              <template
                v-for="(item, index) in results.data"
                :key="`result-wrapper-${item.id}`"
              >
                <article class="search-result-card">
                  <div class="search-result-card__top">
                    <Link :href="item.url" class="search-result-card__title">
                      {{ item.title }}
                    </Link>

                    <div class="search-result-card__badges">
                      <Link
                        v-if="item.category_url"
                        :href="item.category_url"
                        class="badge text-bg-light text-decoration-none"
                      >
                        {{ item.category }}
                      </Link>

                      <Link
                        v-for="tag in item.tags || []"
                        :key="tag.name"
                        :href="tag.url"
                        class="badge text-bg-light text-decoration-none"
                      >
                        #{{ tag.name }}
                      </Link>
                    </div>
                  </div>

                  <p class="search-result-card__excerpt">
                    {{ item.excerpt }}
                  </p>

                  <div class="search-result-card__footer">
                    <Link :href="item.url" class="search-result-card__action">
                      Continuar lendo
                    </Link>
                  </div>
                </article>

                <section
                  v-if="shouldRenderInlineAd(index) && results.data.length > index + 1"
                  class="search-ad-inline"
                  aria-label="Publicidade"
                >
                  <div class="search-ad-inline__label">Publicidade</div>

                  <div class="search-ad-inline__box">
                    <!-- BLOCO ADSENSE RESPONSIVO -->
                    <!-- Substitua pelo seu código real do AdSense -->
                    <div class="search-ad-inline__placeholder">
                      Conteúdo patrocinado
                    </div>
                  </div>
                </section>
              </template>
            </div>

            <div v-else class="search-empty-state">
              <h2 class="search-empty-state__title">Nenhum conteúdo foi encontrado para essa busca</h2>
              <p class="search-empty-state__text">
                Tente pesquisar com outras palavras, usar termos mais simples ou procurar expressões parecidas
                para encontrar resultados mais próximos do que você deseja.
              </p>

              <div
                v-if="featuredCategories.length > 0"
                class="search-empty-state__categories"
              >
                <span class="search-side-card__mini-title">Categorias que podem ajudar</span>

                <div class="search-side-tags">
                  <Link
                    v-for="category in featuredCategories"
                    :key="category.name"
                    :href="category.url"
                    class="search-side-tag"
                  >
                    {{ category.name }}
                  </Link>
                </div>
              </div>
            </div>

            <PostPagination :links="results.links || []" class="mt-4" />

            <section
              v-if="trendingPosts.length > 0"
              class="search-discovery-section"
            >
              <div class="search-section-heading">
                <h2>Conteúdos em destaque para você continuar explorando</h2>
                <p>Descubra outros temas relacionados, aprenda mais e encontre respostas úteis em novos conteúdos.</p>
              </div>

              <div class="search-discovery-grid">
                <Link
                  v-for="post in trendingPosts"
                  :key="post.url"
                  :href="post.url"
                  class="search-discovery-card"
                >
                  <span class="search-discovery-card__title">{{ post.title }}</span>
                  <span class="search-discovery-card__text">
                    {{ post.excerpt }}
                  </span>
                </Link>
              </div>
            </section>
          </main>

          <aside class="search-sidebar">
            <section class="search-side-card search-side-card--ad">
              <span class="search-side-card__label">Publicidade</span>
              <h3 class="search-side-card__title">Sugestão patrocinada</h3>
              <p class="search-side-card__text">
                Neste espaço você pode encontrar recomendações e ofertas relacionadas ao que está pesquisando.
              </p>

              <div class="search-ad-placeholder">
                Conteúdo patrocinado
              </div>
            </section>

            <section
              v-if="recentPosts.length > 0"
              class="search-side-card"
            >
              <span class="search-side-card__label">Seu histórico recente</span>
              <h3 class="search-side-card__title">Conteúdos que você já pesquisou ou visitou</h3>

              <div class="search-side-list">
                <Link
                  v-for="item in recentPosts"
                  :key="item.url"
                  :href="item.url"
                  class="search-side-list__item"
                >
                  <span class="search-side-list__title">{{ item.title }}</span>
                  <span v-if="item.category" class="search-side-list__meta">
                    {{ item.category }}
                  </span>
                </Link>
              </div>
            </section>

            <section
              v-if="featuredCategories.length > 0"
              class="search-side-card"
            >
              <span class="search-side-card__label">Explore mais assuntos</span>
              <h3 class="search-side-card__title">Categorias populares</h3>

              <div class="search-side-tags">
                <Link
                  v-for="category in featuredCategories"
                  :key="category.name"
                  :href="category.url"
                  class="search-side-tag"
                >
                  {{ category.name }}
                </Link>
              </div>
            </section>

            <section
              v-if="tools.length > 0"
              class="search-side-card"
            >
              <span class="search-side-card__label">Recursos úteis</span>
              <h3 class="search-side-card__title">Ferramentas que podem ajudar você</h3>

              <div class="search-side-list">
                <Link
                  v-for="tool in tools"
                  :key="tool.url"
                  :href="tool.url"
                  class="search-side-list__item"
                >
                  <span class="search-side-list__title">{{ tool.name }}</span>
                  <span class="search-side-list__meta">
                    {{ tool.description || 'Acesse e descubra como essa ferramenta pode ser útil para você.' }}
                  </span>
                </Link>
              </div>
            </section>

            <section class="search-side-card">
              <span class="search-side-card__label">Dica de busca</span>
              <h3 class="search-side-card__title">Encontre resultados com mais facilidade</h3>
              <p class="search-side-card__text">
                Para encontrar respostas mais precisas, tente pesquisar palavras curtas, nomes específicos,
                gírias, abreviações e expressões populares que tenham relação direta com o que você quer descobrir.
              </p>
            </section>
          </aside>
        </div>
      </div>
    </section>
  </HybridSiteLayout>
</template>

<style scoped>
.search-page {
  background:
    radial-gradient(circle at top right, rgba(13, 110, 253, 0.08), transparent 28%),
    linear-gradient(180deg, #f8fbff 0%, #ffffff 100%);
}

.search-hero {
  margin-bottom: 2rem;
}

.search-hero__content {
  max-width: 860px;
  margin-bottom: 1.5rem;
}

.search-hero__eyebrow {
  display: inline-block;
  margin-bottom: 0.75rem;
  font-size: 0.78rem;
  font-weight: 700;
  letter-spacing: 0.16em;
  text-transform: uppercase;
  color: #0d6efd;
}

.search-hero__title {
  margin: 0;
  font-size: clamp(2rem, 3vw, 3rem);
  line-height: 1.08;
  font-weight: 800;
  color: #111827;
}

.search-hero__description {
  margin: 1rem 0 0;
  max-width: 760px;
  font-size: 1rem;
  line-height: 1.8;
  color: #5b6472;
}

.search-form-card {
  padding: 1.25rem;
  border-radius: 1.25rem;
  background: #ffffff;
  border: 1px solid rgba(13, 110, 253, 0.12);
  box-shadow: 0 14px 40px rgba(16, 24, 40, 0.08);
}

.search-form-card__label {
  display: block;
  margin-bottom: 0.8rem;
  font-weight: 700;
  color: #1f2937;
}

.search-form-card__row {
  display: grid;
  grid-template-columns: minmax(0, 1fr) auto;
  gap: 0.9rem;
}

.search-form-card__input {
  min-height: 54px;
  border-radius: 0.95rem;
  border: 1px solid #d8e1ee;
  box-shadow: none;
}

.search-form-card__input:focus {
  border-color: #86b7fe;
  box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.12);
}

.search-form-card__button {
  min-width: 160px;
  min-height: 54px;
  border-radius: 0.95rem;
  font-weight: 700;
}

.search-form-card__meta {
  margin: 0.9rem 0 0;
  color: #667085;
}

.search-layout {
  display: grid;
  grid-template-columns: minmax(0, 1.7fr) minmax(280px, 0.7fr);
  gap: 1.5rem;
  align-items: start;
}

.search-results {
  display: grid;
  gap: 1rem;
}

.search-result-card {
  padding: 1.25rem;
  border-radius: 1.1rem;
  background: #ffffff;
  border: 1px solid #e8eef7;
  box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.search-result-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 18px 36px rgba(15, 23, 42, 0.08);
}

.search-result-card__top {
  display: grid;
  gap: 0.8rem;
}

.search-result-card__title {
  font-size: 1.15rem;
  line-height: 1.45;
  font-weight: 800;
  color: #111827;
  text-decoration: none;
}

.search-result-card__title:hover {
  color: #0d6efd;
}

.search-result-card__badges {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.search-result-card__excerpt {
  margin: 1rem 0 0;
  color: #5d6877;
  line-height: 1.8;
}

.search-result-card__footer {
  margin-top: 1rem;
}

.search-result-card__action {
  font-weight: 700;
  text-decoration: none;
}

.search-ad-inline {
  padding: 1rem 1.1rem 1.1rem;
  border-radius: 1.1rem;
  background: linear-gradient(180deg, #f8fbff 0%, #eef5ff 100%);
  border: 1px solid #d9e7ff;
  box-shadow: 0 10px 24px rgba(13, 110, 253, 0.06);
}

.search-ad-inline__label {
  display: inline-block;
  margin-bottom: 0.75rem;
  font-size: 0.74rem;
  font-weight: 800;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  color: #0d6efd;
}

.search-ad-inline__box {
  width: 100%;
}

.search-ad-inline__placeholder {
  display: grid;
  place-items: center;
  min-height: 120px;
  border-radius: 0.95rem;
  border: 1px dashed #bcd3fa;
  background: #ffffff;
  color: #58719a;
  font-weight: 700;
  text-align: center;
  padding: 1rem;
}

.search-sidebar {
  display: grid;
  gap: 1rem;
  position: sticky;
  top: 1.5rem;
}

.search-side-card {
  padding: 1.15rem;
  border-radius: 1.1rem;
  background: #ffffff;
  border: 1px solid #e8eef7;
  box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
}

.search-side-card--ad {
  border-color: rgba(13, 110, 253, 0.16);
}

.search-side-card__label {
  display: inline-block;
  margin-bottom: 0.65rem;
  font-size: 0.74rem;
  font-weight: 800;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  color: #0d6efd;
}

.search-side-card__title {
  margin: 0;
  font-size: 1.05rem;
  font-weight: 800;
  color: #111827;
}

.search-side-card__text {
  margin: 0.85rem 0 0;
  color: #616c7c;
  line-height: 1.75;
}

.search-ad-placeholder {
  display: grid;
  place-items: center;
  min-height: 220px;
  margin-top: 1rem;
  border-radius: 1rem;
  border: 1px dashed #bfd3f6;
  background: linear-gradient(180deg, #f8fbff 0%, #eef5ff 100%);
  color: #4f6b95;
  font-weight: 700;
  text-align: center;
  padding: 1rem;
}

.search-side-list {
  display: grid;
  gap: 0.7rem;
  margin-top: 1rem;
}

.search-side-list__item {
  display: grid;
  gap: 0.3rem;
  text-decoration: none;
  padding: 0.85rem;
  border-radius: 0.9rem;
  background: #f8fbff;
  border: 1px solid #edf2f9;
  transition: background 0.2s ease, transform 0.2s ease;
}

.search-side-list__item:hover {
  background: #f1f7ff;
  transform: translateY(-1px);
}

.search-side-list__title {
  font-weight: 700;
  color: #1f2937;
  line-height: 1.5;
}

.search-side-list__meta {
  font-size: 0.88rem;
  color: #667085;
}

.search-side-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 0.65rem;
  margin-top: 1rem;
}

.search-side-tag {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0.65rem 0.9rem;
  border-radius: 999px;
  text-decoration: none;
  background: #f4f8ff;
  border: 1px solid #dbe7fb;
  color: #315a9b;
  font-weight: 600;
  transition: all 0.2s ease;
}

.search-side-tag:hover {
  background: #eaf2ff;
  color: #0d6efd;
}

.search-empty-state {
  padding: 1.4rem;
  border-radius: 1.2rem;
  background: #ffffff;
  border: 1px solid #e8eef7;
  box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
}

.search-empty-state__title {
  margin: 0;
  font-size: 1.25rem;
  font-weight: 800;
  color: #111827;
}

.search-empty-state__text {
  margin: 0.8rem 0 0;
  color: #616c7c;
  line-height: 1.75;
}

.search-empty-state__categories {
  margin-top: 1.2rem;
}

.search-side-card__mini-title {
  font-size: 0.9rem;
  font-weight: 700;
  color: #1f2937;
}

.search-discovery-section {
  margin-top: 2rem;
}

.search-section-heading {
  margin-bottom: 1rem;
}

.search-section-heading h2 {
  margin: 0;
  font-size: 1.3rem;
  font-weight: 800;
  color: #111827;
}

.search-section-heading p {
  margin: 0.45rem 0 0;
  color: #667085;
  line-height: 1.7;
}

.search-discovery-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 1rem;
}

.search-discovery-card {
  display: grid;
  gap: 0.6rem;
  padding: 1.1rem;
  border-radius: 1rem;
  text-decoration: none;
  background: #ffffff;
  border: 1px solid #e8eef7;
  box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
}

.search-discovery-card:hover {
  border-color: #cfe0fb;
}

.search-discovery-card__title {
  font-weight: 800;
  color: #111827;
  line-height: 1.45;
}

.search-discovery-card__text {
  color: #667085;
  line-height: 1.7;
}

@media (max-width: 1199.98px) {
  .search-layout {
    grid-template-columns: 1fr;
  }

  .search-sidebar {
    position: static;
  }
}

@media (max-width: 767.98px) {
  .search-form-card__row {
    grid-template-columns: 1fr;
  }

  .search-form-card__button {
    width: 100%;
    min-width: 100%;
  }

  .search-discovery-grid {
    grid-template-columns: 1fr;
  }

  .search-ad-inline__placeholder,
  .search-ad-placeholder {
    min-height: 100px;
  }
}
</style>