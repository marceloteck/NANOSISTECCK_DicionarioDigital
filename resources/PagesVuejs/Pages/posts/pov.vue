<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import AppHead from '../../components/Applications/AppHead.vue';
import Footer from '../../components/Home/Footer.vue';
import PostHeader from '../../components/Post/PostHeader.vue';
import PostSidebar from '../../components/Post/PostSidebar.vue';
import PostBreadcrumb from '../../components/Post/PostBreadcrumb.vue';
import QuickAnswerBox from '../../components/Post/QuickAnswerBox.vue';
import RelatedPostsSection from '../../components/Post/RelatedPostsSection.vue';
import PostFaqSection from '../../components/Post/PostFaqSection.vue';
import PostCtaSection from '../../components/Post/PostCtaSection.vue';

const page = usePage();

const seo = computed(() => page.props.seo ?? {});
const post = computed(() => page.props.post ?? {});
const breadcrumbs = computed(() => page.props.breadcrumbs ?? []);
const sidebar = computed(() => page.props.sidebar ?? { cards: [], categories: [], internalLinks: [] });
const relatedPosts = computed(() => page.props.relatedPosts ?? []);
const topSearches = computed(() => page.props.topSearches ?? []);
const faqItems = computed(() => post.value.faq ?? []);
const ctaStats = computed(() => page.props.cta?.stats ?? []);

const quickAnswer = computed(() => {
  if (post.value.excerpt) return post.value.excerpt;
  return 'Conteúdo completo com definição, contexto de uso, exemplos práticos e respostas rápidas.';
});

const mappedRelatedPosts = computed(() => relatedPosts.value.map((item) => ({
  category: post.value.category?.name || 'Relacionados',
  badge: 'Recomendado',
  title: item.title,
  description: item.excerpt,
  href: route('posts.show', item.slug),
})));

const headingTitle = computed(() => post.value.title || 'Post');
</script>

<template>
  <AppHead v-bind="seo" />

  <PostHeader />

  <main>
    <section class="hero" id="inicio">
      <div class="container">
        <div class="hero-copy">
          <div class="hero-pill">
            {{ post.category?.name || 'Posts' }} • Leitura de {{ post.reading_time || 1 }} min • Conteúdo atualizado
          </div>

          <h1 class="hero-title">
            {{ headingTitle }}
          </h1>

          <p class="hero-desc">
            {{ post.excerpt }}
          </p>

          <div class="hero-actions">
            <a href="#conteudo" class="btn btn-primary">Ver significado completo</a>
            <a href="#relacionados" class="btn btn-outline">Explorar termos parecidos</a>
          </div>

          <div class="hero-trust">
            <span v-if="post.author_name">Por {{ post.author_name }}</span>
            <span v-if="post.published_at">Publicado em {{ new Date(post.published_at).toLocaleDateString('pt-BR') }}</span>
            <span v-if="post.tags?.length">{{ post.tags.length }} tag(s) relacionadas</span>
            <span>Estrutura otimizada para SEO</span>
          </div>
        </div>
      </div>
    </section>

    <section class="section" id="conteudo">
      <div class="container system-grid">
        <article class="post-preview">
          <PostBreadcrumb :items="breadcrumbs" />

          <small class="preview-label">Significado completo</small>
          <h2>{{ headingTitle }}</h2>

          <QuickAnswerBox :text="quickAnswer" />

          <aside v-if="post.toc?.length" class="card mb-4 shadow-sm">
            <div class="card-body">
              <h3 class="h6 mb-2">Neste artigo</h3>
              <ul class="list-unstyled mb-0">
                <li v-for="item in post.toc" :key="item.id" :class="item.level === 'h3' ? 'ms-3' : ''">
                  <a :href="`#${item.id}`">{{ item.label }}</a>
                </li>
              </ul>
            </div>
          </aside>

          <section class="post-content" v-html="post.content_html" />

          <div v-if="post.tags?.length" class="d-flex gap-2 flex-wrap mt-4">
            <Link
              v-for="tag in post.tags"
              :key="tag.slug || tag.id"
              :href="route('posts.tag', tag.slug)"
              class="badge text-bg-light text-decoration-none"
            >
              #{{ tag.name }}
            </Link>
          </div>
        </article>

        <div>
          <PostSidebar :cards="sidebar.cards || []" />

          <aside class="posts-sidebar mt-4">
            <div class="sidebar-card" v-if="sidebar.categories?.length">
              <small>Categorias</small>
              <ul>
                <li v-for="category in sidebar.categories" :key="category.slug">
                  <Link :href="route('posts.category', category.slug)">{{ category.name }}</Link>
                </li>
              </ul>
            </div>

            <div class="sidebar-card" v-if="sidebar.internalLinks?.length">
              <small>Links internos</small>
              <ul>
                <li v-for="link in sidebar.internalLinks" :key="link.href">
                  <a :href="link.href">{{ link.label }}</a>
                </li>
              </ul>
            </div>
          </aside>
        </div>
      </div>
    </section>

    <RelatedPostsSection :posts="mappedRelatedPosts" :top-searches="topSearches" />

    <PostFaqSection v-if="faqItems.length" :items="faqItems" />

    <PostCtaSection v-if="ctaStats.length" :stats="ctaStats" />
  </main>

  <Footer />
</template>
