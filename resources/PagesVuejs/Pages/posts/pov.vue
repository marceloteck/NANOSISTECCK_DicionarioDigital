<script setup>
import { computed, ref, onMounted, onBeforeUnmount } from 'vue';
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
  if (post.value.quick_answer) return post.value.quick_answer;
  if (post.value.excerpt) return post.value.excerpt;
  return 'Conteúdo completo com definição, contexto de uso, exemplos práticos e respostas rápidas.';
});

const mappedRelatedPosts = computed(() =>
  relatedPosts.value.map((item) => ({
    category: item.category?.name || post.value.category?.name || 'Relacionados',
    badge: item.badge || 'Recomendado',
    title: item.title,
    description: item.excerpt,
    href: route('posts.show', item.slug),
  }))
);

const headingTitle = computed(() => post.value.hero_title || post.value.title || 'Post');

const postPreviewRef = ref(null);
const sideColumnRef = ref(null);
const stickyAdRef = ref(null);
const stickyAdState = ref('');
const stickyAdAnchorRef = ref(null);

let onScrollHandler = null;
let onResizeHandler = null;
let rafId = null;

const scheduleStickyAdUpdate = () => {
  if (rafId !== null) return;

  rafId = window.requestAnimationFrame(() => {
    updateStickyAd();
    rafId = null;
  });
};

const updateStickyAd = () => {
  if (
    !postPreviewRef.value ||
    !sideColumnRef.value ||
    !stickyAdRef.value ||
    !stickyAdAnchorRef.value
  ) return;

  const adEl = stickyAdRef.value;
  const anchorEl = stickyAdAnchorRef.value;

  if (window.innerWidth <= 1180) {
    stickyAdState.value = '';
    adEl.style.left = '';
    adEl.style.width = '';
    adEl.style.top = '';
    adEl.style.visibility = '';
    adEl.style.opacity = '';
    anchorEl.style.height = '';
    return;
  }

  const articleRect = postPreviewRef.value.getBoundingClientRect();
  const sideRect = sideColumnRef.value.getBoundingClientRect();
  const anchorRect = anchorEl.getBoundingClientRect();

  const scrollY = window.scrollY;
  const articleBottom = articleRect.bottom + scrollY;
  const anchorTop = anchorRect.top + scrollY;

  const fixedTop = 110;
  const adHeight = adEl.offsetHeight;
  const sideWidth = sideRect.width;

  const startStick = anchorTop - fixedTop;
  const stopStick = articleBottom - adHeight - 24;

  anchorEl.style.height = `${adHeight}px`;

  if (scrollY < startStick) {
    stickyAdState.value = '';
    adEl.style.left = '';
    adEl.style.width = '';
    adEl.style.top = '';
    adEl.style.visibility = '';
    adEl.style.opacity = '';
    return;
  }

  if (scrollY >= stopStick) {
    stickyAdState.value = 'is-hidden';
    adEl.style.left = '';
    adEl.style.width = '';
    adEl.style.top = '';
    adEl.style.visibility = 'hidden';
    adEl.style.opacity = '0';
    return;
  }

  stickyAdState.value = 'is-fixed';
  adEl.style.left = `${sideRect.left}px`;
  adEl.style.width = `${sideWidth}px`;
  adEl.style.top = `${fixedTop}px`;
  adEl.style.visibility = 'visible';
  adEl.style.opacity = '1';
};


onMounted(() => {
  onScrollHandler = () => scheduleStickyAdUpdate();
  onResizeHandler = () => scheduleStickyAdUpdate();

  window.addEventListener('scroll', onScrollHandler, { passive: true });
  window.addEventListener('resize', onResizeHandler);

  scheduleStickyAdUpdate();
});

onBeforeUnmount(() => {
  if (onScrollHandler) {
    window.removeEventListener('scroll', onScrollHandler);
  }

  if (onResizeHandler) {
    window.removeEventListener('resize', onResizeHandler);
  }

  if (rafId !== null) {
    window.cancelAnimationFrame(rafId);
    rafId = null;
  }
});

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
            {{ post.hero_summary || post.excerpt }}
          </p>

          <div class="hero-actions">
            <a href="#conteudo" class="btn btn-primary">Ver significado completo</a>
            <a href="#relacionados" class="btn btn-outline">Explorar termos parecidos</a>
          </div>

          <div class="hero-trust">
            <span v-if="post.author_name">Por {{ post.author_name }}</span>
            <span v-if="post.published_at">Publicado em {{ new Date(post.published_at).toLocaleDateString('pt-BR') }}</span>
            <span v-if="post.tags?.length">{{ post.tags.length }} tag(s) relacionadas</span>
          </div>
        </div>
      </div>
    </section>

    <section class="section" id="conteudo">
      <div class="container system-grid">
        <article ref="postPreviewRef" class="post-preview">
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
              :key="typeof tag === 'string' ? tag : (tag.slug || tag.id)"
              :href="route('posts.tag', typeof tag === 'string' ? tag : tag.slug)"
              class="badge text-bg-light text-decoration-none"
            >
              #{{ typeof tag === 'string' ? tag : tag.name }}
            </Link>
          </div>
        </article>

        
        
<div class="post-side-column" ref="sideColumnRef">
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

    <div ref="stickyAdAnchorRef" class="sticky-ad-anchor">
    <div
      ref="stickyAdRef"
      class="sidebar-card sidebar-card-sticky-ad"
      :class="stickyAdState"
    >
      <small>Conteúdo patrocinado</small>
      <div class="ad-placeholder">
        Neste espaço você pode encontrar recomendações, sugestões úteis e conteúdos patrocinados
        relacionados aos assuntos mais buscados do momento.
      </div>
    </div>
  </div>
  </aside>
</div>





      </div>
    </section>

    <RelatedPostsSection :posts="mappedRelatedPosts" :top-searches="topSearches" />

    <PostFaqSection v-if="faqItems.length" :items="faqItems" />

    <PostCtaSection v-if="ctaStats.length" :stats="ctaStats" :title="page.props.cta?.title" :text="page.props.cta?.text" :button-text="page.props.cta?.button_text" :button-url="page.props.cta?.button_url" />
  </main>

  <Footer />
</template>

<style scoped>
.post-side-column {
  position: relative;
  align-self: start;
}

.sticky-ad-anchor {
  position: relative;
}

.sidebar-card-sticky-ad {
  will-change: transform, opacity;
  transition:
    transform var(--transition),
    box-shadow var(--transition),
    border-color var(--transition),
    opacity 220ms ease;
}

.sidebar-card-sticky-ad::before {
  content: "";
  position: absolute;
  inset: 0;
  pointer-events: none;
  background:
    radial-gradient(circle at 14% 18%, rgba(109, 40, 217, 0.1), transparent 24%),
    radial-gradient(circle at 86% 14%, rgba(192, 138, 43, 0.09), transparent 22%);
  opacity: 0.95;
}

.sidebar-card-sticky-ad > * {
  position: relative;
  z-index: 1;
}

.sidebar-card-sticky-ad:hover {
  transform: translateY(-2px);
  box-shadow: 0 24px 60px rgba(31, 23, 40, 0.12);
  border-color: rgba(109, 40, 217, 0.16);
}

.sidebar-card-sticky-ad.is-fixed {
  position: fixed;
  z-index: 30;
}

.sidebar-card-sticky-ad.is-hidden {
  opacity: 0;
  pointer-events: none;
}

.sidebar-card-sticky-ad .ad-placeholder {
  min-height: 220px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.post-content {
  color: var(--text);
  line-height: 1.85;
}

.post-content > *:first-child {
  margin-top: 0;
}

.post-content > *:last-child {
  margin-bottom: 0;
}

.post-content h2,
.post-content h3,
.post-content h4 {
  color: var(--text);
  letter-spacing: -0.03em;
  line-height: 1.15;
  margin-top: 32px;
  margin-bottom: 14px;
  scroll-margin-top: 110px;
}

.post-content h2 {
  font-size: clamp(1.5rem, 3vw, 2rem);
  font-weight: 800;
}

.post-content h3 {
  font-size: clamp(1.2rem, 2.3vw, 1.45rem);
}

.post-content h4 {
  font-size: 1.05rem;
}

.post-content p,
.post-content li {
  color: var(--muted);
}

.post-content ul,
.post-content ol {
  padding-left: 22px;
  margin: 16px 0 20px;
  display: grid;
  gap: 10px;
}

.post-content blockquote {
  margin: 24px 0;
  padding: 18px 20px;
  border-left: 4px solid var(--primary);
  border-radius: 18px;
  background: linear-gradient(180deg, var(--accent-soft), rgba(255, 255, 255, 0.94));
  color: var(--text);
  box-shadow: var(--shadow-md);
}

.post-content a {
  color: var(--primary);
  font-weight: 700;
  text-decoration: underline;
  text-decoration-thickness: 1px;
  text-underline-offset: 3px;
}

.post-content img,
.post-content video,
.post-content iframe {
  width: 100%;
  border-radius: 22px;
  margin: 24px 0;
  box-shadow: var(--shadow-lg);
}

.post-content table {
  display: block;
  width: 100%;
  overflow-x: auto;
  border-collapse: collapse;
  margin: 24px 0;
  border-radius: 18px;
  background: rgba(255, 255, 255, 0.88);
  box-shadow: var(--shadow-md);
}

.post-content th,
.post-content td {
  min-width: 160px;
  padding: 14px 16px;
  border: 1px solid var(--border);
  text-align: left;
}

.post-content th {
  background: var(--accent-soft);
  color: var(--text);
  font-weight: 700;
}

@media (max-width: 1180px) {
  .sticky-ad-anchor {
    height: auto !important;
  }

  .sidebar-card-sticky-ad,
  .sidebar-card-sticky-ad.is-fixed,
  .sidebar-card-sticky-ad.is-hidden {
    position: static;
    left: auto;
    right: auto;
    top: auto;
    width: auto !important;
    visibility: visible !important;
    opacity: 1 !important;
  }
}

@media (max-width: 700px) {
  .post-content h2 {
    font-size: 1.4rem;
  }

  .post-content h3 {
    font-size: 1.12rem;
  }
}
</style>
