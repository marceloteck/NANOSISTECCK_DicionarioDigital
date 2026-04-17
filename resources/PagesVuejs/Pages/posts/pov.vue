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
  onScrollHandler = () => updateStickyAd();
  onResizeHandler = () => updateStickyAd();

  window.addEventListener('scroll', onScrollHandler, { passive: true });
  window.addEventListener('resize', onResizeHandler);

  updateStickyAd();
});

onBeforeUnmount(() => {
  if (onScrollHandler) {
    window.removeEventListener('scroll', onScrollHandler);
  }

  if (onResizeHandler) {
    window.removeEventListener('resize', onResizeHandler);
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

.sidebar-card-sticky-ad {
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
    radial-gradient(circle at 14% 18%, rgba(109, 40, 217, 0.10), transparent 24%),
    radial-gradient(circle at 86% 14%, rgba(192, 138, 43, 0.09), transparent 22%);
  opacity: 0.95;
}

.sidebar-card-sticky-ad > * {
  position: relative;
  z-index: 1;
}

.sidebar-card-sticky-ad.is-fixed {
  position: fixed;
  z-index: 30;
}

.sidebar-card-sticky-ad.is-bottom {
  position: absolute;
  left: 0;
  right: 0;
  z-index: 20;
}

.sidebar-card-sticky-ad:hover {
  transform: translateY(-2px);
  box-shadow: 0 24px 60px rgba(31, 23, 40, 0.12);
  border-color: rgba(109, 40, 217, 0.16);
}

.sidebar-card-sticky-ad .ad-placeholder {
  min-height: 220px;
  display: flex;
  align-items: center;
  justify-content: center;
}

@media (max-width: 1180px) {
  .sidebar-card-sticky-ad,
  .sidebar-card-sticky-ad.is-fixed,
  .sidebar-card-sticky-ad.is-bottom {
    position: static;
    left: auto;
    right: auto;
    top: auto;
    width: auto !important;
  }
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

.sidebar-card-sticky-ad.is-fixed {
  position: fixed;
  z-index: 30;
}

.sidebar-card-sticky-ad.is-hidden {
  opacity: 0;
  pointer-events: none;
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

.sidebar-card-sticky-ad {
  will-change: transform, opacity;
}

.sidebar-card-sticky-ad.is-fixed {
  position: fixed;
  z-index: 30;
}

.sidebar-card-sticky-ad.is-hidden {
  opacity: 0;
  pointer-events: none;
}




.post-side-column {
  position: relative;
  align-self: start;
}

.sidebar-card-sticky-ad {
  transition:
    transform var(--transition),
    box-shadow var(--transition),
    border-color var(--transition);
}

.sidebar-card-sticky-ad.is-fixed {
  position: fixed;
  z-index: 30;
}

.sidebar-card-sticky-ad.is-bottom {
  position: absolute;
  left: 0;
  right: 0;
  z-index: 20;
}

.sidebar-card-sticky-ad:hover {
  transform: translateY(-2px);
  box-shadow: 0 24px 60px rgba(31, 23, 40, 0.12);
  border-color: rgba(109, 40, 217, 0.16);
}

@media (max-width: 1180px) {
  .sidebar-card-sticky-ad,
  .sidebar-card-sticky-ad.is-fixed,
  .sidebar-card-sticky-ad.is-bottom {
    position: static;
    left: auto;
    right: auto;
    top: auto;
    width: auto !important;
  }
}



















:root {
  --bg: #f6f4f8;
  --bg-soft: #ece8f0;
  --surface: rgba(255, 255, 255, 0.84);
  --surface-strong: rgba(255, 255, 255, 0.94);
  --surface-soft: #faf8fc;

  --border: rgba(42, 31, 56, 0.08);
  --border-strong: rgba(42, 31, 56, 0.14);

  --text: #1f1728;
  --muted: #655c72;
  --muted-2: #8b8298;

  --primary: #6d28d9;
  --primary-strong: #5b21b6;
  --accent: #9333ea;
  --accent-2: #c08a2b;
  --accent-soft: #f4ecff;
  --gold-soft: #fff7e8;

  --shadow-xl: 0 30px 80px rgba(31, 23, 40, 0.12);
  --shadow-lg: 0 20px 50px rgba(31, 23, 40, 0.10);
  --shadow-md: 0 12px 30px rgba(31, 23, 40, 0.08);

  --radius-xl: 32px;
  --radius-lg: 24px;
  --radius-md: 18px;
  --radius-sm: 14px;
  --container: 1280px;
  --transition: 260ms cubic-bezier(.2,.8,.2,1);
}

html {
  scroll-behavior: smooth;
}

body {
  font-family: "Inter", sans-serif;
  color: var(--text);
  background:
    radial-gradient(circle at 14% 18%, rgba(147, 51, 234, 0.07), transparent 22%),
    radial-gradient(circle at 82% 16%, rgba(192, 138, 43, 0.06), transparent 22%),
    radial-gradient(circle at 48% 78%, rgba(109, 40, 217, 0.05), transparent 22%),
    linear-gradient(180deg, var(--bg) 0%, var(--bg-soft) 55%, var(--bg) 100%);
  overflow-x: hidden;
  line-height: 1.7;
  -webkit-font-smoothing: antialiased;
  text-rendering: optimizeLegibility;
}

body::before {
  content: "";
  position: fixed;
  inset: 0;
  pointer-events: none;
  background-image:
    linear-gradient(rgba(31, 23, 40, 0.03) 1px, transparent 1px),
    linear-gradient(90deg, rgba(31, 23, 40, 0.03) 1px, transparent 1px);
  background-size: 34px 34px;
  mask-image: radial-gradient(circle at center, black 35%, transparent 100%);
  opacity: 0.22;
  z-index: 0;
}

main,
footer,
header,
section,
article,
aside,
.container {
  position: relative;
  z-index: 1;
}

a {
  color: inherit;
  text-decoration: none;
}

img,
svg,
video,
canvas,
iframe {
  display: block;
  max-width: 100%;
}

.container {
  width: min(92%, var(--container));
  margin: 0 auto;
}

.hero,
.section {
  position: relative;
}

.hero {
  padding: 52px 0 24px;
}

.hero-copy {
  max-width: 920px;
}

.hero-pill {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  padding: 11px 16px;
  border-radius: 999px;
  border: 1px solid rgba(109, 40, 217, 0.14);
  background: rgba(109, 40, 217, 0.08);
  color: var(--primary);
  font-weight: 600;
  font-size: 0.9rem;
  margin-bottom: 22px;
  flex-wrap: wrap;
}

.hero-title {
  font-size: clamp(2.6rem, 6vw, 4.9rem);
  line-height: 0.98;
  letter-spacing: -0.06em;
  margin-bottom: 20px;
  max-width: 980px;
  word-break: break-word;
}

.hero-desc {
  max-width: 860px;
  color: var(--muted);
  font-size: 1.08rem;
  line-height: 1.85;
  margin-bottom: 28px;
}

.hero-actions {
  display: flex;
  flex-wrap: wrap;
  gap: 14px;
  margin-bottom: 24px;
}

.hero-trust {
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
}

.hero-trust span {
  padding: 11px 14px;
  border-radius: 999px;
  color: var(--muted);
  background: rgba(255, 255, 255, 0.84);
  border: 1px solid var(--border);
  font-size: 0.9rem;
  box-shadow: var(--shadow-md);
}

.section {
  padding: 84px 0;
}

.system-grid {
  display: grid;
  grid-template-columns: minmax(0, 1fr) 360px;
  gap: 24px;
  align-items: start;
}

.posts-layout {
  display: grid;
  grid-template-columns: minmax(0, 1fr) 360px;
  gap: 24px;
  align-items: start;
}

.post-preview,
.sidebar-card,
.mini-info-card,
.faq-card,
.cta-panel,
.footer-shell,
.post-card {
  background: linear-gradient(180deg, var(--surface-strong), var(--surface));
  backdrop-filter: blur(18px);
  border: 1px solid var(--border);
  box-shadow: var(--shadow-lg);
}

.post-preview {
  border-radius: 28px;
  padding: 30px;
  transition: var(--transition);
}

.preview-label,
.sidebar-card small,
.mini-info-card small {
  display: block;
  color: var(--muted-2);
  font-size: 0.72rem;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  margin-bottom: 6px;
  font-weight: 700;
}

.post-preview h2 {
  font-size: clamp(1.5rem, 3vw, 2.5rem);
  margin-bottom: 14px;
  letter-spacing: -0.03em;
  overflow-wrap: anywhere;
  font-weight:bold;
}

.preview-intro {
  color: var(--muted);
  line-height: 1.85;
  margin-bottom: 20px;
}

.quick-answer {
  margin: 22px 0;
  padding: 18px 20px;
  border-radius: 20px;
  background: linear-gradient(180deg, var(--accent-soft), #ffffff);
  border: 1px solid rgba(109, 40, 217, 0.12);
}

.quick-answer strong {
  display: block;
  margin-bottom: 8px;
  color: var(--primary-strong);
}

.quick-answer p {
  color: var(--text);
  margin: 0;
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
  /*font-size: clamp(0.8rem, 2vw, 0.8rem);*/
  font-size: clamp(1.5rem, 3vw, 2rem);
  font-weight: 800;
}

.post-content h3 {
  font-size: clamp(1.2rem, 2.3vw, 1.45rem);
}

.post-content h4 {
  font-size: 1.05rem;
}

.post-content p {
  color: var(--muted);
  margin-bottom: 16px;
}

.post-content strong {
  color: var(--text);
  font-weight: 700;
}

.post-content ul,
.post-content ol {
  padding-left: 22px;
  margin: 16px 0 20px;
  display: grid;
  gap: 10px;
}

.post-content li {
  color: var(--muted);
  line-height: 1.8;
}

.post-content blockquote {
  margin: 24px 0;
  padding: 18px 20px;
  border-left: 4px solid var(--primary);
  border-radius: 18px;
  background: linear-gradient(180deg, var(--accent-soft), rgba(255,255,255,0.94));
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

.post-content hr {
  border: 0;
  height: 1px;
  background: linear-gradient(90deg, transparent, var(--border-strong), transparent);
  margin: 28px 0;
}

.post-content table {
  width: 100%;
  border-collapse: collapse;
  margin: 24px 0;
  overflow: hidden;
  border-radius: 18px;
  background: rgba(255,255,255,0.88);
  box-shadow: var(--shadow-md);
}

.post-content th,
.post-content td {
  padding: 14px 16px;
  border: 1px solid var(--border);
  text-align: left;
}

.post-content th {
  background: var(--accent-soft);
  color: var(--text);
  font-weight: 700;
}

.post-content img {
  width: 100%;
  border-radius: 22px;
  margin: 24px 0;
  box-shadow: var(--shadow-lg);
}

.post-content code {
  background: rgba(109, 40, 217, 0.08);
  color: var(--primary-strong);
  padding: 2px 8px;
  border-radius: 8px;
  font-size: 0.95em;
}

.post-content pre {
  margin: 24px 0;
  padding: 18px;
  border-radius: 20px;
  overflow-x: auto;
  background: #1f1728;
  color: #f8f7fb;
  box-shadow: var(--shadow-lg);
}

.post-content pre code {
  background: transparent;
  color: inherit;
  padding: 0;
}

.posts-sidebar,
.system-sidebar {
  display: grid;
  gap: 18px;
}

.sidebar-card {
  border-radius: 24px;
  padding: 22px;
}

.sidebar-card ul {
  list-style: none;
  display: grid;
  gap: 12px;
  margin-top: 12px;
  padding: 0;
}

.sidebar-card li a {
  display: block;
  padding: 14px 16px;
  border-radius: 16px;
  background: rgba(255, 255, 255, 0.88);
  border: 1px solid var(--border);
  color: var(--text);
  font-weight: 600;
  transition: var(--transition);
}

.sidebar-card li a:hover {
  transform: translateY(-2px);
  box-shadow: var(--shadow-md);
  border-color: rgba(109, 40, 217, 0.16);
}

.mini-info-card {
  padding: 18px;
  border-radius: 20px;
  transition: var(--transition);
}

.mini-info-card strong {
  display: block;
  font-size: 1rem;
  font-weight: 700;
}

.mini-info-card p {
  color: var(--muted);
  margin-top: 8px;
  line-height: 1.8;
}

.ad-placeholder {
  margin-top: 14px;
  padding: 26px 18px;
  border-radius: 18px;
  text-align: center;
  border: 1px dashed rgba(109, 40, 217, 0.22);
  background: linear-gradient(180deg, var(--accent-soft), rgba(255,255,255,0.88));
  color: var(--muted);
  line-height: 1.7;
}

.post-list {
  display: grid;
  gap: 18px;
}

.post-card {
  border-radius: 24px;
  padding: 24px;
  min-height: auto;
  transition: var(--transition);
}

.post-card:hover {
  transform: translateY(-4px);
  box-shadow: var(--shadow-xl);
}

.post-card .post-meta {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  color: var(--primary-strong);
  font-size: 0.9rem;
  font-weight: 700;
  margin-bottom: 10px;
}

.post-card h3 {
  font-size: 1.24rem;
  margin-bottom: 10px;
  letter-spacing: -0.03em;
}

.post-card p {
  color: var(--muted);
  line-height: 1.8;
  margin-bottom: 16px;
}

.post-card a {
  color: var(--primary);
  font-weight: 700;
}

.section-head {
  max-width: 760px;
  margin-bottom: 34px;
}

.eyebrow {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  font-size: 0.8rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 1.8px;
  color: var(--primary);
  margin-bottom: 14px;
  flex-wrap: wrap;
}

.eyebrow::before {
  content: "";
  width: 36px;
  height: 1px;
  background: linear-gradient(90deg, var(--primary), transparent);
}

.section-head h2 {
  font-size: clamp(2rem, 4vw, 3.4rem);
  line-height: 1.03;
  letter-spacing: -0.04em;
  margin-bottom: 14px;
}

.section-head p {
  color: var(--muted);
  font-size: 1.03rem;
  line-height: 1.8;
}

.faq-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 18px;
}

.faq-card {
  border-radius: 24px;
  padding: 22px;
}

.faq-card h3 {
  font-size: 1.08rem;
  margin-bottom: 8px;
  letter-spacing: -0.03em;
  overflow-wrap: anywhere;
}

.faq-card p {
  color: var(--muted);
  line-height: 1.8;
}

.cta-section {
  padding-top: 40px;
}

.cta-panel {
  border-radius: 34px;
  padding: 36px;
  position: relative;
  overflow: hidden;
}

.cta-panel::before {
  content: "";
  position: absolute;
  inset: 0;
  background:
    radial-gradient(circle at 12% 20%, rgba(109, 40, 217, 0.10), transparent 20%),
    radial-gradient(circle at 84% 16%, rgba(192, 138, 43, 0.10), transparent 22%);
  pointer-events: none;
}

.cta-panel > * {
  position: relative;
  z-index: 2;
}

.cta-panel small {
  display: block;
  color: var(--primary-strong);
  text-transform: uppercase;
  letter-spacing: 0.14em;
  font-weight: 700;
  margin-bottom: 12px;
}

.cta-panel h2 {
  font-size: clamp(2rem, 4vw, 3.4rem);
  line-height: 1.02;
  letter-spacing: -0.04em;
  margin-bottom: 14px;
  max-width: 860px;
}

.cta-panel p {
  color: var(--muted);
  font-size: 1.02rem;
  line-height: 1.85;
  max-width: 760px;
}

.cta-actions {
  display: flex;
  flex-wrap: wrap;
  gap: 14px;
  margin-top: 24px;
}

.cta-stats {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 14px;
  margin-top: 26px;
}

.cta-stat {
  padding: 18px;
  border-radius: 20px;
  background: rgba(255, 255, 255, 0.86);
  border: 1px solid var(--border);
}

.cta-stat strong {
  display: block;
  font-size: 1rem;
  font-weight: 700;
}

.cta-stat span {
  color: var(--muted);
  font-size: 0.92rem;
  display: block;
  margin-top: 6px;
}

.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  padding: 14px 20px;
  border-radius: 999px;
  border: 1px solid transparent;
  font-weight: 700;
  transition: var(--transition);
  cursor: pointer;
  white-space: nowrap;
  text-align: center;
}

.btn:hover {
  transform: translateY(-2px);
}

.btn-outline {
  background: rgba(255, 255, 255, 0.78);
  border-color: var(--border-strong);
  color: var(--text);
  box-shadow: var(--shadow-md);
}

.btn-primary {
  color: #ffffff;
  background: linear-gradient(135deg, var(--primary), var(--accent));
  box-shadow: 0 12px 30px rgba(109, 40, 217, 0.18);
}

.badge.text-bg-light {
  padding: 10px 14px;
  border-radius: 999px !important;
  background: #ffffff !important;
  border: 1px solid var(--border) !important;
  color: var(--primary) !important;
  font-weight: 700;
  font-size: 0.92rem;
  box-shadow: none !important;
}

.card {
  border: 1px solid var(--border);
  border-radius: 22px;
  background: linear-gradient(180deg, var(--surface-strong), var(--surface));
  box-shadow: var(--shadow-md);
}

.card-body {
  padding: 20px;
}

.card-body h3,
.card-body .h6 {
  font-size: 1rem;
  margin-bottom: 12px;
  letter-spacing: -0.02em;
}

.card-body .h6 {
  display: inline-block;
  position: relative;

  padding: 8px 14px;
  border-radius: 10px;

  font-size: 0.95rem;
  font-weight: 700;
  letter-spacing: 0.06em;
  text-transform: uppercase;

  color: var(--primary-strong);

  background: linear-gradient(
    135deg,
    rgba(109, 40, 217, 0.10),
    rgba(192, 138, 43, 0.10)
  );

  border: 1px solid rgba(109, 40, 217, 0.18);

  box-shadow: 0 6px 18px rgba(109, 40, 217, 0.08);
}

.card-body ul {
  list-style: none;
  padding: 0;
  margin: 0;
  display: grid;
  gap: 10px;
}

.card-body li a {
  display: inline-block;
  color: var(--primary);
  font-weight: 600;
}

.site-footer {
  padding: 30px 0 60px;
}

.footer-shell {
  padding: 24px 28px;
  border-radius: 26px;
  display: grid;
  grid-template-columns: 1fr auto;
  gap: 20px;
  align-items: center;
}

.footer-brand p {
  color: var(--muted);
  line-height: 1.8;
  max-width: 820px;
  margin-top: 12px;
}

.footer-links {
  list-style: none;
  display: flex;
  flex-wrap: wrap;
  gap: 18px;
  color: var(--muted);
  padding: 0;
  margin: 0;
}

.footer-links a {
  transition: var(--transition);
}

.footer-links a:hover {
  color: var(--text);
}

@media (max-width: 1180px) {
  .posts-layout,
  .system-grid {
    grid-template-columns: 1fr;
  }

  .cta-stats {
    grid-template-columns: 1fr;
  }

  .posts-sidebar,
  .system-sidebar {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }

  .posts-sidebar > *,
  .system-sidebar > * {
    height: 100%;
  }
}

@media (max-width: 940px) {
  .faq-grid,
  .footer-shell,
  .posts-sidebar,
  .system-sidebar {
    grid-template-columns: 1fr;
  }

  .hero {
    padding-top: 42px;
  }

  .hero-desc {
    font-size: 1rem;
  }

  .hero-trust span {
    font-size: 0.85rem;
  }
}

@media (max-width: 700px) {
  .container {
    width: min(94%, var(--container));
  }

  body::before {
    background-size: 26px 26px;
    opacity: 0.16;
  }

  .hero-title {
    font-size: 2.2rem;
    line-height: 1;
    margin-bottom: 16px;
  }

  .hero-desc {
    font-size: 0.98rem;
    line-height: 1.75;
  }

  .hero-pill {
    font-size: 0.82rem;
    padding: 10px 14px;
    margin-bottom: 18px;
  }

  .section {
    padding: 66px 0;
  }

  .section-head {
    margin-bottom: 24px;
  }

  .section-head h2 {
    font-size: 2rem;
    line-height: 1.06;
  }

  .section-head p {
    font-size: 0.98rem;
    line-height: 1.75;
  }

  .post-preview,
  .sidebar-card,
  .mini-info-card,
  .faq-card,
  .cta-panel,
  .footer-shell,
  .post-card {
    padding: 20px;
    border-radius: 22px;
  }

  .faq-grid {
    grid-template-columns: 1fr;
  }

  .cta-actions,
  .hero-actions {
    width: 100%;
  }

  .cta-actions .btn,
  .hero-actions .btn {
    width: 100%;
  }

  .post-preview h2 {
    font-size: 1.5rem;
    line-height: 1.08;
  }

  .cta-panel h2 {
    font-size: 2rem;
    line-height: 1.06;
  }

  .cta-panel p {
    font-size: 0.98rem;
    line-height: 1.75;
  }

  .footer-links {
    gap: 12px;
    flex-direction: column;
  }
}

@media (max-width: 520px) {
  .container {
    width: min(95%, var(--container));
  }

  .hero-title {
    font-size: 1.95rem;
  }

  .section-head h2,
  .cta-panel h2 {
    font-size: 1.75rem;
  }

  .hero-trust,
  .hero-actions,
  .cta-actions {
    gap: 10px;
  }

  .hero-trust span {
    width: 100%;
    text-align: center;
    justify-content: center;
  }

  .post-preview,
  .sidebar-card,
  .mini-info-card,
  .faq-card {
    padding: 18px;
  }

  .post-content h2 {
    font-size: 1.4rem;
  }

  .post-content h3 {
    font-size: 1.12rem;
  }
}
</style>