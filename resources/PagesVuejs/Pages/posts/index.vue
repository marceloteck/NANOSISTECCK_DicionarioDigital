<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();

const seo = computed(() => page.props.seo ?? {});
const posts = computed(() => page.props.posts ?? { data: [], links: [] });
const featuredCategories = computed(() => page.props.featuredCategories ?? []);
const popularPosts = computed(() => page.props.popularPosts ?? []);
const featuredTopics = computed(() => page.props.featuredTopics ?? []);
const totalPosts = computed(() => page.props.totalPosts ?? posts.value?.data?.length ?? 0);

const shouldRenderInlineAd = (index) => {
  return index === 1 || index === 5;
};
</script>

<template>
  <AppHead v-bind="seo" />

  <ContentSiteLayout title="Posts" page-type="listing">
    <section class="posts-page py-4 py-lg-5">
      <div class="container">
        <div class="posts-hero">
          <div class="posts-hero__content">
            <span class="posts-hero__eyebrow">Explore conteúdos úteis e atuais</span>
            <h1 class="posts-hero__title">Descubra significados, dicas, explicações e conteúdos que ajudam no seu dia a dia</h1>
            <p class="posts-hero__description">
              Navegue por conteúdos criados para esclarecer dúvidas, explicar termos, apresentar informações relevantes
              e ajudar você a encontrar respostas com mais rapidez, clareza e confiança.
            </p>
          </div>

          <div class="posts-hero__meta-card">
            <span class="posts-hero__meta-label">Conteúdos publicados</span>
            <strong class="posts-hero__meta-value">{{ totalPosts }}</strong>
            <span class="posts-hero__meta-text">opções para você ler, aprender e explorar nesta página</span>
          </div>
        </div>

        <div class="posts-layout">
          <main class="posts-main">
            <div class="posts-grid" v-if="posts.data.length > 0">
              <template
                v-for="(post, index) in posts.data"
                :key="`post-wrap-${post.id}`"
              >
                <article class="post-card">
                  <div class="post-card__body">
                    <p class="post-card__category">
                      {{ post.category?.name || 'Conteúdo informativo' }}
                    </p>

                    <h2 class="post-card__title">
                      <Link :href="route('posts.show', post.slug)">
                        {{ post.title }}
                      </Link>
                    </h2>

                    <p class="post-card__excerpt">
                      {{ post.excerpt }}
                    </p>

                    <div class="post-card__tags" v-if="post.tags?.length">
                      <span
                        v-for="tag in post.tags"
                        :key="tag.id"
                        class="post-card__tag"
                      >
                        #{{ tag.name }}
                      </span>
                    </div>

                    <div class="post-card__footer">
                      <Link
                        :href="route('posts.show', post.slug)"
                        class="post-card__action"
                      >
                        Ler este conteúdo
                      </Link>
                    </div>
                  </div>
                </article>

                <section
                  v-if="shouldRenderInlineAd(index) && posts.data.length > index + 1"
                  class="posts-inline-ad"
                  aria-label="Publicidade"
                >
                  <div class="posts-inline-ad__label">Publicidade</div>

                  <div class="posts-inline-ad__box">
                    <div class="posts-inline-ad__placeholder">
                      Conteúdo patrocinado
                    </div>
                  </div>
                </section>
              </template>
            </div>

            <div v-else class="posts-empty-state">
              <h2 class="posts-empty-state__title">Nenhum conteúdo foi encontrado nesta página no momento</h2>
              <p class="posts-empty-state__text">
                Isso pode acontecer temporariamente. Enquanto isso, você pode explorar outras categorias,
                descobrir novos assuntos e acessar conteúdos que também podem ser do seu interesse.
              </p>

              <div v-if="featuredCategories.length" class="posts-empty-state__tags">
                <Link
                  v-for="category in featuredCategories"
                  :key="category.name"
                  :href="category.url"
                  class="posts-topic-tag"
                >
                  {{ category.name }}
                </Link>
              </div>
            </div>

            <PostPagination :links="posts.links || []" class="mt-4" />
          </main>

          <aside class="posts-sidebar">
            <section class="posts-side-card posts-side-card--ad">
              <span class="posts-side-card__label">Publicidade</span>
              <h3 class="posts-side-card__title">Sugestão patrocinada para você</h3>
              <p class="posts-side-card__text">
                Neste espaço você pode encontrar recomendações, serviços e conteúdos patrocinados relacionados
                aos temas que está explorando.
              </p>

              <div class="posts-side-ad-placeholder">
                Conteúdo patrocinado
              </div>
            </section>

            <section v-if="featuredCategories.length" class="posts-side-card">
              <span class="posts-side-card__label">Continue explorando</span>
              <h3 class="posts-side-card__title">Categorias para encontrar mais conteúdos</h3>

              <div class="posts-topics">
                <Link
                  v-for="category in featuredCategories"
                  :key="category.name"
                  :href="category.url"
                  class="posts-topic-tag"
                >
                  {{ category.name }}
                </Link>
              </div>
            </section>

            <section v-if="popularPosts.length" class="posts-side-card">
              <span class="posts-side-card__label">Mais lidos</span>
              <h3 class="posts-side-card__title">Conteúdos populares entre os leitores</h3>

              <div class="posts-side-list">
                <Link
                  v-for="item in popularPosts"
                  :key="item.url"
                  :href="item.url"
                  class="posts-side-list__item"
                >
                  <span class="posts-side-list__title">{{ item.title }}</span>
                  <span class="posts-side-list__meta">
                    {{ item.excerpt }}
                  </span>
                </Link>
              </div>
            </section>

            <section v-if="featuredTopics.length" class="posts-side-card">
              <span class="posts-side-card__label">Sugestões para você</span>
              <h3 class="posts-side-card__title">Assuntos relacionados para continuar navegando</h3>

              <div class="posts-topics">
                <Link
                  v-for="topic in featuredTopics"
                  :key="topic.name"
                  :href="topic.url"
                  class="posts-topic-tag"
                >
                  {{ topic.name }}
                </Link>
              </div>
            </section>

            
            <section class="posts-side-card">
              <span class="posts-side-card__label">Descubra mais</span>
              <h3 class="posts-side-card__title">Encontre novos conteúdos do seu interesse</h3>
              <p class="posts-side-card__text">
                Aproveite esta página para descobrir temas relacionados, aprender algo novo, esclarecer dúvidas
                e acessar conteúdos organizados para tornar sua navegação mais prática, útil e agradável.
              </p>
            </section>

            <section class="posts-side-card posts-side-card--ad">
              <span class="posts-side-card__label">Publicidade</span>
              <h3 class="posts-side-card__title">Outra recomendação patrocinada</h3>
              <p class="posts-side-card__text">
                Um espaço pensado para apresentar sugestões relevantes sem atrapalhar sua leitura
                e sua experiência de navegação.
              </p>

              <div class="posts-side-ad-placeholder posts-side-ad-placeholder--small">
                Conteúdo patrocinado
              </div>
            </section>

          </aside>
        </div>
      </div>
    </section>
  </ContentSiteLayout>
</template>

<style scoped>
.posts-page {
  background:
    radial-gradient(circle at top left, rgba(13, 110, 253, 0.08), transparent 28%),
    linear-gradient(180deg, #f8fbff 0%, #ffffff 100%);
}

.posts-hero {
  display: grid;
  grid-template-columns: minmax(0, 1.7fr) minmax(220px, 0.6fr);
  gap: 1.25rem;
  align-items: stretch;
  margin-bottom: 2rem;
}

.posts-hero__content {
  max-width: 860px;
}

.posts-hero__eyebrow {
  display: inline-block;
  margin-bottom: 0.75rem;
  font-size: 0.78rem;
  font-weight: 700;
  letter-spacing: 0.16em;
  text-transform: uppercase;
  color: #0d6efd;
}

.posts-hero__title {
  margin: 0;
  font-size: clamp(2rem, 3vw, 3rem);
  line-height: 1.08;
  font-weight: 800;
  color: #111827;
}

.posts-hero__description {
  margin: 1rem 0 0;
  max-width: 760px;
  font-size: 1rem;
  line-height: 1.8;
  color: #5b6472;
}

.posts-hero__meta-card {
  display: grid;
  align-content: center;
  padding: 1.25rem;
  border-radius: 1.25rem;
  background: #ffffff;
  border: 1px solid rgba(13, 110, 253, 0.12);
  box-shadow: 0 14px 40px rgba(16, 24, 40, 0.08);
}

.posts-hero__meta-label {
  font-size: 0.82rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.12em;
  color: #0d6efd;
}

.posts-hero__meta-value {
  margin-top: 0.45rem;
  font-size: 2.1rem;
  line-height: 1;
  font-weight: 800;
  color: #111827;
}

.posts-hero__meta-text {
  margin-top: 0.55rem;
  color: #667085;
}

.posts-layout {
  display: grid;
  grid-template-columns: minmax(0, 1.7fr) minmax(290px, 0.75fr);
  gap: 1.5rem;
  align-items: start;
}

.posts-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 1rem;
}

.post-card {
  border-radius: 1.1rem;
  background: #ffffff;
  border: 1px solid #e8eef7;
  box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.post-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 18px 36px rgba(15, 23, 42, 0.08);
}

.post-card__body {
  padding: 1.2rem;
}

.post-card__category {
  margin: 0 0 0.75rem;
  font-size: 0.82rem;
  font-weight: 700;
  color: #6b7280;
}

.post-card__title {
  margin: 0;
  font-size: 1.15rem;
  line-height: 1.45;
  font-weight: 800;
}

.post-card__title a {
  color: #111827;
  text-decoration: none;
}

.post-card__title a:hover {
  color: #0d6efd;
}

.post-card__excerpt {
  margin: 0.95rem 0 0;
  color: #5d6877;
  line-height: 1.8;
}

.post-card__tags {
  display: flex;
  flex-wrap: wrap;
  gap: 0.55rem;
  margin-top: 1rem;
}

.post-card__tag {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0.48rem 0.7rem;
  border-radius: 999px;
  background: #f8fbff;
  border: 1px solid #e6eefc;
  color: #4b6589;
  font-size: 0.84rem;
  font-weight: 600;
}

.post-card__footer {
  margin-top: 1rem;
}

.post-card__action {
  font-weight: 700;
  text-decoration: none;
}

.posts-inline-ad {
  grid-column: 1 / -1;
  padding: 1rem 1.1rem 1.1rem;
  border-radius: 1.1rem;
  background: linear-gradient(180deg, #f8fbff 0%, #eef5ff 100%);
  border: 1px solid #d9e7ff;
  box-shadow: 0 10px 24px rgba(13, 110, 253, 0.06);
}

.posts-inline-ad__label {
  display: inline-block;
  margin-bottom: 0.75rem;
  font-size: 0.74rem;
  font-weight: 800;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  color: #0d6efd;
}

.posts-inline-ad__placeholder {
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

.posts-sidebar {
  display: grid;
  gap: 1rem;
  position: sticky;
  top: 1.5rem;
}

.posts-side-card {
  padding: 1.15rem;
  border-radius: 1.1rem;
  background: #ffffff;
  border: 1px solid #e8eef7;
  box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
}

.posts-side-card--ad {
  border-color: rgba(13, 110, 253, 0.16);
}

.posts-side-card__label {
  display: inline-block;
  margin-bottom: 0.65rem;
  font-size: 0.74rem;
  font-weight: 800;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  color: #0d6efd;
}

.posts-side-card__title {
  margin: 0;
  font-size: 1.05rem;
  font-weight: 800;
  color: #111827;
}

.posts-side-card__text {
  margin: 0.85rem 0 0;
  color: #616c7c;
  line-height: 1.75;
}

.posts-side-ad-placeholder {
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

.posts-side-ad-placeholder--small {
  min-height: 140px;
}

.posts-side-list {
  display: grid;
  gap: 0.7rem;
  margin-top: 1rem;
}

.posts-side-list__item {
  display: grid;
  gap: 0.35rem;
  text-decoration: none;
  padding: 0.85rem;
  border-radius: 0.9rem;
  background: #f8fbff;
  border: 1px solid #edf2f9;
  transition: background 0.2s ease, transform 0.2s ease;
}

.posts-side-list__item:hover {
  background: #f1f7ff;
  transform: translateY(-1px);
}

.posts-side-list__title {
  font-weight: 700;
  color: #1f2937;
  line-height: 1.5;
}

.posts-side-list__meta {
  font-size: 0.88rem;
  color: #667085;
  line-height: 1.6;
}

.posts-topics {
  display: flex;
  flex-wrap: wrap;
  gap: 0.65rem;
  margin-top: 1rem;
}

.posts-topic-tag {
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

.posts-topic-tag:hover {
  background: #eaf2ff;
  color: #0d6efd;
}

.posts-empty-state {
  padding: 1.4rem;
  border-radius: 1.2rem;
  background: #ffffff;
  border: 1px solid #e8eef7;
  box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
}

.posts-empty-state__title {
  margin: 0;
  font-size: 1.25rem;
  font-weight: 800;
  color: #111827;
}

.posts-empty-state__text {
  margin: 0.8rem 0 0;
  color: #616c7c;
  line-height: 1.75;
}

.posts-empty-state__tags {
  display: flex;
  flex-wrap: wrap;
  gap: 0.65rem;
  margin-top: 1rem;
}

@media (max-width: 1199.98px) {
  .posts-hero,
  .posts-layout {
    grid-template-columns: 1fr;
  }

  .posts-sidebar {
    position: static;
  }
}

@media (max-width: 767.98px) {
  .posts-grid {
    grid-template-columns: 1fr;
  }

  .posts-side-ad-placeholder,
  .posts-inline-ad__placeholder {
    min-height: 100px;
  }
}
</style>