<script setup>
import axios from 'axios';
import { computed, ref, watch } from 'vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

const page = usePage();
const mode = computed(() => page.props.mode);
const categories = computed(() => page.props.categories || []);
const searchIntents = computed(() => page.props.searchIntents || []);
const post = computed(() => page.props.post || {});
const importedTagsPreview = ref([]);
const importDiagnostics = ref([]);
const importMessage = ref('');
const samplePayload = `{
  "title": "O que é SEO semântico e como aplicar no conteúdo",
  "slug": "o-que-e-seo-semantico-e-como-aplicar-no-conteudo",
  "excerpt": "Entenda SEO semântico na prática para criar conteúdo útil, escaneável e alinhado à intenção de busca.",
  "hero_title": "SEO semântico na prática para conteúdo que rankeia",
  "hero_summary": "Aprenda como organizar contexto, entidades e intenção de busca para melhorar relevância e experiência do usuário.",
  "quick_answer": "SEO semântico é a otimização de conteúdo com foco em contexto e intenção, não apenas repetição de palavra-chave. Na prática, você estrutura o texto por tópicos, perguntas e relações entre termos.",
  "content_html": "<h2>Introdução</h2><p>SEO semântico é a evolução do SEO tradicional. Em vez de repetir termos, você cobre o assunto com profundidade, clareza e contexto.</p><h2>Explicação principal</h2><p>Ao aplicar SEO semântico, você organiza o conteúdo por intenção de busca, utiliza linguagem natural e conecta tópicos relacionados. Isso ajuda o usuário e facilita a compreensão pelos mecanismos de busca.</p><h3>Pilares da aplicação</h3><ul><li><strong>Contexto:</strong> explique o tema central e seus desdobramentos.</li><li><strong>Entidades:</strong> cite pessoas, ferramentas, conceitos e processos ligados ao assunto.</li><li><strong>Estrutura:</strong> use títulos claros, listas e FAQs para escaneabilidade.</li></ul><h2>Exemplos práticos</h2><ul><li>Responder perguntas reais do público em blocos curtos.</li><li>Criar seção de diferenças entre conceitos parecidos.</li><li>Adicionar FAQ com dúvidas recorrentes sobre implementação.</li></ul><h2>Erros comuns e observações</h2><ul><li>Focar só em densidade de palavra-chave.</li><li>Ignorar intenção de busca da página.</li><li>Publicar conteúdo sem exemplos práticos.</li></ul><h2>Conclusão</h2><p>Quando o conteúdo é útil, bem estruturado e orientado à intenção, ele tende a performar melhor em tráfego orgânico e retenção.</p>",
  "featured_image": "",
  "featured_image_alt": "Ilustração sobre SEO semântico e estrutura de conteúdo",
  "seo_title": "SEO semântico: o que é e como aplicar no conteúdo",
  "meta_description": "Guia objetivo para entender SEO semântico e aplicar em conteúdos mais relevantes, escaneáveis e orientados à intenção de busca.",
  "meta_keywords": "seo semantico, intencao de busca, conteudo otimizado, estrutura de artigo",
  "canonical_url": "https://dicionario.nanosistecck.com/posts/o-que-e-seo-semantico-e-como-aplicar-no-conteudo",
  "schema_type": "Article",
  "search_intent": "informational",
  "content_type": "guide",
  "category_name": "SEO",
  "author_name": "Equipe NANOSISTECCK",
  "is_published": false,
  "is_indexable": true,
  "published_at": null,
  "status": "draft",
  "faq_json": [
    { "question": "SEO semântico substitui SEO tradicional?", "answer": "Não substitui. Ele amplia a abordagem tradicional ao considerar contexto e intenção de busca." },
    { "question": "Preciso repetir palavra-chave muitas vezes?", "answer": "Não. O ideal é cobrir o tema com naturalidade e termos relacionados, sem exagero." },
    { "question": "Qual a diferença entre palavra-chave e intenção?", "answer": "Palavra-chave é o termo usado na busca; intenção é o objetivo real por trás dessa busca." },
    { "question": "FAQ ajuda no SEO?", "answer": "Sim. FAQ melhora escaneabilidade, responde dúvidas diretas e pode enriquecer sinais de relevância da página." }
  ],
  "related_keywords": ["seo semantico", "intencao de busca", "como otimizar conteudo", "faq para seo"],
  "tags": ["seo", "conteudo"],
  "cta_title": "Quer acelerar resultados com SEO?",
  "cta_text": "Veja como estruturar conteúdo com padrão editorial pronto para produção no Dicionário Digital.",
  "cta_button_text": "Saiba mais",
  "cta_button_url": "https://dicionario.nanosistecck.com/posts"
}`;

const form = useForm({
  title: post.value.title || '',
  slug: post.value.slug || '',
  excerpt: post.value.excerpt || '',
  author_name: post.value.author_name || '',
  status: post.value.status || 'draft',
  published_at: post.value.published_at || '',
  category_name: post.value.category_name || post.value.category?.name || '',
  tags: (post.value.tags || []).map((tag) => tag.id),
  tags_input: (post.value.tags || []).map((tag) => tag.name).join(', '),
  related_keywords: (post.value.related_keywords || []).join(', '),
  search_intent: post.value.search_intent || 'informational',
  content_type: post.value.content_type || 'guide',
  hero_title: post.value.hero_title || '',
  hero_summary: post.value.hero_summary || '',
  featured_image: post.value.featured_image || '',
  featured_image_alt: post.value.featured_image_alt || '',
  quick_answer: post.value.quick_answer || '',
  content_html: post.value.content_html || '',
  faq_json: post.value.faq_json || [{ question: '', answer: '' }],
  seo_title: post.value.seo_title || '',
  meta_description: post.value.meta_description || '',
  meta_keywords: post.value.meta_keywords || '',
  canonical_url: post.value.canonical_url || '',
  schema_type: post.value.schema_type || 'Article',
  is_indexable: post.value.is_indexable ?? true,
  is_published: post.value.is_published ?? false,
  cta_title: post.value.cta_title || '',
  cta_text: post.value.cta_text || '',
  cta_button_text: post.value.cta_button_text || '',
  cta_button_url: post.value.cta_button_url || '',
});

const jsonForm = useForm({ payload_json: '' });
const categorySuggestions = computed(() => {
  return [...new Set((categories.value || []).map((category) => String(category.name || '').trim()).filter(Boolean))];
});
const readingTime = computed(() => {
  const text = String(form.content_html || '').replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim();
  const words = text ? text.split(' ').length : 0;

  return Math.max(1, Math.ceil(words / 220));
});

watch(() => form.title, (value) => {
  if (mode.value === 'create' || !post.value.slug) {
    form.slug = value
      .normalize('NFD')
      .replace(/[\u0300-\u036f]/g, '')
      .toLowerCase()
      .replace(/[^a-z0-9\s-]/g, '')
      .trim()
      .replace(/\s+/g, '-');
  }
});

watch(() => form.tags_input, (value) => {
  const mapped = String(value).split(',').map((item) => item.trim()).filter(Boolean);
  form.tags = mapped;
  importedTagsPreview.value = mapped;
});

watch(() => form.category_name, (value) => {
  form.category_name = String(value || '').replace(/\s+/g, ' ').trimStart();
});

const buildPayload = () => ({
  title: form.title,
  slug: form.slug,
  excerpt: form.excerpt,
  author_name: form.author_name,
  status: form.status,
  published_at: form.published_at || null,
  category_name: String(form.category_name || '').replace(/\s+/g, ' ').trim() || null,
  tags: Array.isArray(form.tags) ? form.tags : [],
  related_keywords: String(form.related_keywords).split(',').map((item) => item.trim()).filter(Boolean),
  search_intent: form.search_intent,
  content_type: form.content_type,
  hero_title: form.hero_title,
  hero_summary: form.hero_summary,
  featured_image: form.featured_image,
  featured_image_alt: form.featured_image_alt,
  quick_answer: form.quick_answer,
  content_html: form.content_html,
  faq_json: form.faq_json,
  seo_title: form.seo_title,
  meta_description: form.meta_description,
  meta_keywords: form.meta_keywords,
  canonical_url: form.canonical_url,
  schema_type: form.schema_type,
  is_indexable: form.is_indexable,
  is_published: form.is_published,
  cta_title: form.cta_title,
  cta_text: form.cta_text,
  cta_button_text: form.cta_button_text,
  cta_button_url: form.cta_button_url,
});

const submit = () => {
  const payload = buildPayload();

  form.transform(() => payload);

  if (mode.value === 'create') {
    form.post(route('posts.store'));
    return;
  }

  form.patch(route('posts.update', post.value.slug));
};

const importJson = async () => {
  jsonForm.clearErrors();
  importDiagnostics.value = [];
  importMessage.value = '';

  try {
    const response = await axios.post(route('admin.posts.import-json'), jsonForm.data());
    const payload = response.data.data || {};
    importMessage.value = response.data.message || 'JSON importado com sucesso.';

    Object.entries(payload).forEach(([key, value]) => {
      if (Object.prototype.hasOwnProperty.call(form.data(), key)) {
        form[key] = value;
      }
    });

    if (!payload.category_name && payload.category_id) {
      const legacyCategory = categories.value.find((category) => Number(category.id) === Number(payload.category_id));
      form.category_name = legacyCategory?.name || '';
    }

    form.related_keywords = (payload.related_keywords || []).join(', ');

    const mappedTags = (payload.tags || []).map((tag) => {
      if (typeof tag === 'number') return String(tag);
      if (typeof tag === 'string') return tag;
      if (tag && typeof tag === 'object' && tag.name) return tag.name;

      return '';
    }).filter(Boolean);

    importedTagsPreview.value = mappedTags;
    form.tags_input = mappedTags.join(', ');
  } catch (error) {
    const apiMessage = error.response?.data?.message || 'Falha ao importar JSON';
    const diagnostics = Array.isArray(error.response?.data?.diagnostics) ? error.response.data.diagnostics : [];
    const fieldErrors = error.response?.data?.errors || {};
    const flattenedFieldErrors = Object.entries(fieldErrors).flatMap(([field, messages]) => {
      return (Array.isArray(messages) ? messages : [messages]).map((message) => ({
        field,
        message: String(message),
      }));
    });
    const mergedDiagnostics = diagnostics.length ? diagnostics : flattenedFieldErrors;
    const uniqueDiagnostics = mergedDiagnostics.filter((item, index, list) => {
      const field = item.field || '';
      const message = item.message || item.suggestion || item.code || '';
      return list.findIndex((entry) => ((entry.field || '') === field) && ((entry.message || entry.suggestion || entry.code || '') === message)) === index;
    });

    importDiagnostics.value = uniqueDiagnostics;
    jsonForm.setError('payload_json', apiMessage);
  }
};

const addFaq = () => form.faq_json.push({ question: '', answer: '' });
const removeFaq = (index) => form.faq_json.splice(index, 1);
const loadSamplePayload = () => {
  jsonForm.payload_json = samplePayload;
  importDiagnostics.value = [];
  importMessage.value = 'Modelo carregado. Clique em Importar para validar e preencher o formulário.';
};
</script>

<template>
    <AppHead
    title="Criar e editar Postagens"
  />
  <main class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h3 m-0">{{ mode === 'create' ? 'Criar post' : 'Editar post' }}</h1>
      <Link :href="route('admin.posts.index')" class="btn btn-outline-secondary">Voltar</Link>
    </div>

    <div class="card p-3 mb-4">
      <h2 class="h5">Importar JSON</h2>
      <textarea v-model="jsonForm.payload_json" rows="6" class="form-control" placeholder="Cole aqui o JSON do post"></textarea>
      <div v-if="jsonForm.errors.payload_json" class="text-danger small mt-2">{{ jsonForm.errors.payload_json }}</div>
      <div v-if="importMessage" class="text-success small mt-2">{{ importMessage }}</div>
      <div v-if="importDiagnostics.length" class="alert alert-warning mt-3 mb-0">
        <strong>Diagnóstico da importação</strong>
        <ul class="mb-0 mt-2 ps-3">
          <li v-for="(item, index) in importDiagnostics" :key="index">
            <template v-if="item.field"><strong>{{ item.field }}:</strong> </template>{{ item.message || item.suggestion || item.code }}
            <template v-if="item.suggestion"> — {{ item.suggestion }}</template>
          </li>
        </ul>
      </div>
      <div class="d-flex gap-2 mt-3">
        <button type="button" class="btn btn-outline-primary" @click="importJson">Importar</button>
        <button type="button" class="btn btn-outline-secondary" @click="loadSamplePayload">Carregar JSON modelo válido</button>
      </div>
    </div>

    <form @submit.prevent="submit" class="d-grid gap-4">
      <section class="card p-3">
        <h2 class="h5">1. Identificação</h2>
        <div class="row g-3">
          <div class="col-md-8"><input v-model="form.title" class="form-control" placeholder="title" /></div>
          <div class="col-md-4"><input v-model="form.slug" class="form-control" placeholder="slug" /></div>
          <div class="col-12"><textarea v-model="form.excerpt" class="form-control" placeholder="excerpt"></textarea></div>
          <div class="col-md-4"><input v-model="form.author_name" class="form-control" placeholder="author_name" /></div>
          <div class="col-md-4"><select v-model="form.status" class="form-select"><option value="draft">draft</option><option value="published">published</option></select></div>
          <div class="col-md-4"><input v-model="form.published_at" type="datetime-local" class="form-control" /></div>
        </div>
      </section>

      <section class="card p-3">
        <h2 class="h5">2. Taxonomia</h2>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Categoria</label>
            <input
              v-model="form.category_name"
              class="form-control"
              list="category-name-suggestions"
              placeholder="Digite o nome da categoria (ex.: SEO)"
            />
            <datalist id="category-name-suggestions">
              <option v-for="category in categorySuggestions" :key="category" :value="category">{{ category }}</option>
            </datalist>
            <small class="text-muted">Digite livremente. Se já existir, a categoria será reaproveitada automaticamente.</small>
          </div>
          <div class="col-md-6">
            <small class="text-muted">Sugestões rápidas</small>
            <div class="d-flex gap-2 flex-wrap mt-1">
              <button
                v-for="category in categorySuggestions.slice(0, 8)"
                :key="category"
                type="button"
                class="btn btn-sm btn-outline-secondary"
                @click="form.category_name = category"
              >
                {{ category }}
              </button>
            </div>
          </div>
          <div class="col-md-6"><input v-model="form.tags_input" class="form-control" placeholder="tags dinâmico: seo, laravel" /></div>
          <div class="col-md-6">
            <small class="text-muted">Tags reconhecidas</small>
            <div class="d-flex gap-2 flex-wrap mt-1">
              <span v-for="tag in importedTagsPreview" :key="tag" class="badge text-bg-light">{{ tag }}</span>
            </div>
          </div>
          <div class="col-md-6"><input v-model="form.related_keywords" class="form-control" placeholder="related_keywords" /></div>
          <div class="col-md-3"><select v-model="form.search_intent" class="form-select"><option v-for="item in searchIntents" :key="item" :value="item">{{ item }}</option></select></div>
          <div class="col-md-3"><input v-model="form.content_type" class="form-control" placeholder="content_type" /></div>
        </div>
      </section>

      <section class="card p-3">
        <h2 class="h5">3. Hero</h2>
        <div class="row g-3">
          <div class="col-md-6"><input v-model="form.hero_title" class="form-control" placeholder="hero title" /></div>
          <div class="col-md-6"><input v-model="form.hero_summary" class="form-control" placeholder="hero resumo" /></div>
          <div class="col-md-6"><input v-model="form.featured_image" class="form-control" placeholder="featured_image" /></div>
          <div class="col-md-6"><input v-model="form.featured_image_alt" class="form-control" placeholder="featured_image_alt" /></div>
        </div>
      </section>

      <section class="card p-3">
        <h2 class="h5">4. Conteúdo Premium</h2>
        <textarea v-model="form.quick_answer" class="form-control mb-3" placeholder="quick_answer"></textarea>
        <textarea v-model="form.content_html" rows="10" class="form-control" placeholder="content_html"></textarea>
      </section>

      <section class="card p-3">
        <h2 class="h5">5. FAQ</h2>
        <div v-for="(item, index) in form.faq_json" :key="index" class="row g-2 mb-2">
          <div class="col-md-5"><input v-model="item.question" class="form-control" placeholder="Pergunta" /></div>
          <div class="col-md-6"><input v-model="item.answer" class="form-control" placeholder="Resposta" /></div>
          <div class="col-md-1"><button class="btn btn-outline-danger w-100" type="button" @click="removeFaq(index)">-</button></div>
        </div>
        <button type="button" class="btn btn-outline-primary" @click="addFaq">Adicionar FAQ</button>
      </section>

      <section class="card p-3">
        <h2 class="h5">6. SEO</h2>
        <div class="row g-3">
          <div class="col-md-6"><input v-model="form.seo_title" class="form-control" placeholder="seo_title" /></div>
          <div class="col-md-6"><input v-model="form.meta_description" class="form-control" placeholder="meta_description" /></div>
          <div class="col-md-4"><input v-model="form.meta_keywords" class="form-control" placeholder="meta_keywords" /></div>
          <div class="col-md-4"><input v-model="form.canonical_url" class="form-control" placeholder="canonical_url" /></div>
          <div class="col-md-4"><input v-model="form.schema_type" class="form-control" placeholder="schema_type" /></div>
        </div>
      </section>

      <section class="card p-3">
        <h2 class="h5">7. CTA FINAL</h2>
        <div class="row g-3">
          <div class="col-md-6"><input v-model="form.cta_title" class="form-control" placeholder="cta_title" /></div>
          <div class="col-md-6"><input v-model="form.cta_button_text" class="form-control" placeholder="cta_button_text" /></div>
          <div class="col-12"><textarea v-model="form.cta_text" class="form-control" placeholder="cta_text"></textarea></div>
          <div class="col-12"><input v-model="form.cta_button_url" class="form-control" placeholder="cta_button_url" /></div>
        </div>
      </section>

      <section class="card p-3">
        <h2 class="h5">8. Configurações</h2>
        <div class="d-flex gap-4 align-items-center">
          <label><input v-model="form.is_published" type="checkbox" /> is_published</label>
          <label><input v-model="form.is_indexable" type="checkbox" /> is_indexable</label>
          <span>reading_time estimado: <strong>{{ readingTime }} min</strong></span>
        </div>
      </section>

      <div v-if="Object.keys(form.errors).length" class="alert alert-danger">
        <div v-for="(error, field) in form.errors" :key="field">{{ error }}</div>
      </div>

      <button class="btn btn-primary" :disabled="form.processing">Salvar post</button>
    </form>
  </main>
</template>

<style scoped>
:root {
  --editor-bg: #0b1020;
  --editor-surface: rgba(255, 255, 255, 0.72);
  --editor-surface-strong: rgba(255, 255, 255, 0.9);
  --editor-border: rgba(15, 23, 42, 0.1);
  --editor-border-strong: rgba(99, 102, 241, 0.24);
  --editor-text: #0f172a;
  --editor-text-soft: #475569;
  --editor-title: #0b1220;
  --editor-primary: #4f46e5;
  --editor-primary-hover: #4338ca;
  --editor-primary-soft: rgba(79, 70, 229, 0.1);
  --editor-success: #047857;
  --editor-warning: #92400e;
  --editor-danger: #b91c1c;
  --editor-shadow-sm: 0 10px 30px rgba(15, 23, 42, 0.06);
  --editor-shadow-md: 0 18px 50px rgba(15, 23, 42, 0.12);
  --editor-shadow-lg: 0 28px 80px rgba(2, 6, 23, 0.16);
  --editor-radius-sm: 14px;
  --editor-radius-md: 20px;
  --editor-radius-lg: 28px;
}

/* fundo geral */
.container.py-4 {
  max-width: 1240px;
  position: relative;
  z-index: 1;
}

.container.py-4::before {
  content: "";
  position: fixed;
  inset: 0;
  z-index: -2;
  background:
    radial-gradient(circle at top left, rgba(99, 102, 241, 0.18), transparent 32%),
    radial-gradient(circle at top right, rgba(14, 165, 233, 0.12), transparent 30%),
    linear-gradient(180deg, #f8fafc 0%, #eef2ff 48%, #f8fafc 100%);
}

.container.py-4::after {
  content: "";
  position: fixed;
  inset: 0;
  z-index: -1;
  pointer-events: none;
  background-image:
    linear-gradient(rgba(255,255,255,0.18) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255,255,255,0.18) 1px, transparent 1px);
  background-size: 32px 32px;
  opacity: 0.22;
}

/* topo */
.container.py-4 > .d-flex.justify-content-between.align-items-center.mb-3 {
  margin-bottom: 1.25rem !important;
  padding: 1.15rem 1.35rem;
  border: 1px solid rgba(255,255,255,0.6);
  border-radius: var(--editor-radius-lg);
  background: linear-gradient(135deg, rgba(255,255,255,0.86), rgba(255,255,255,0.64));
  backdrop-filter: blur(18px);
  -webkit-backdrop-filter: blur(18px);
  box-shadow: var(--editor-shadow-md);
}

.container.py-4 h1.h3 {
  font-size: clamp(1.45rem, 2vw, 2.05rem);
  font-weight: 800;
  letter-spacing: -0.03em;
  color: var(--editor-title);
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.container.py-4 h1.h3::before {
  content: "";
  width: 14px;
  height: 14px;
  border-radius: 999px;
  background: linear-gradient(135deg, var(--editor-primary), #06b6d4);
  box-shadow: 0 0 0 6px rgba(79, 70, 229, 0.12);
}

/* cards */
.card {
  border: 1px solid rgba(255,255,255,0.55) !important;
  border-radius: var(--editor-radius-md) !important;
  background: linear-gradient(180deg, rgba(255,255,255,0.82), rgba(255,255,255,0.68)) !important;
  backdrop-filter: blur(18px);
  -webkit-backdrop-filter: blur(18px);
  box-shadow: var(--editor-shadow-sm);
  transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
  overflow: hidden;
}

.card:hover {
  transform: translateY(-2px);
  box-shadow: var(--editor-shadow-md);
  border-color: rgba(99, 102, 241, 0.18) !important;
}

.card.p-3 {
  padding: 1.45rem !important;
}

.card h2.h5 {
  margin: 0 0 1rem;
  font-size: 1.03rem;
  font-weight: 800;
  line-height: 1.35;
  color: var(--editor-title);
  letter-spacing: -0.02em;
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.card h2.h5::before {
  content: "";
  width: 10px;
  height: 10px;
  flex: 0 0 10px;
  border-radius: 999px;
  background: linear-gradient(135deg, var(--editor-primary), #22c55e);
  box-shadow: 0 0 0 5px rgba(79, 70, 229, 0.11);
}

/* grid/form */
form.d-grid.gap-4 {
  gap: 1.35rem !important;
}

.row.g-3 {
  --bs-gutter-y: 1rem;
  --bs-gutter-x: 1rem;
}

.row.g-2 {
  --bs-gutter-y: 0.75rem;
  --bs-gutter-x: 0.75rem;
}

/* labels auxiliares */
small.text-muted {
  color: var(--editor-text-soft) !important;
  font-size: 0.83rem;
  font-weight: 600;
  letter-spacing: 0.01em;
}

/* campos */
.form-control,
.form-select {
  min-height: 52px;
  border-radius: 16px !important;
  border: 1px solid rgba(148, 163, 184, 0.35) !important;
  background: rgba(255, 255, 255, 0.9) !important;
  color: var(--editor-text) !important;
  font-size: 0.96rem;
  font-weight: 500;
  padding: 0.85rem 1rem !important;
  box-shadow: inset 0 1px 0 rgba(255,255,255,0.55);
  transition:
    border-color 0.22s ease,
    box-shadow 0.22s ease,
    background-color 0.22s ease,
    transform 0.22s ease;
}

.form-control::placeholder,
.form-select {
  color: #64748b !important;
}

textarea.form-control {
  min-height: 120px;
  resize: vertical;
  line-height: 1.65;
  padding-top: 0.95rem !important;
}

textarea.form-control[rows="10"] {
  min-height: 340px;
  font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", monospace;
  font-size: 0.93rem;
  line-height: 1.72;
}

textarea.form-control[rows="6"] {
  min-height: 220px;
  font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", monospace;
  font-size: 0.92rem;
  line-height: 1.65;
}

.form-control:hover,
.form-select:hover {
  border-color: rgba(99, 102, 241, 0.38) !important;
}

.form-control:focus,
.form-select:focus {
  background: #ffffff !important;
  border-color: rgba(79, 70, 229, 0.55) !important;
  box-shadow:
    0 0 0 4px rgba(79, 70, 229, 0.12),
    0 14px 30px rgba(79, 70, 229, 0.08) !important;
  transform: translateY(-1px);
}

/* botões */
.btn {
  min-height: 48px;
  border-radius: 14px !important;
  font-weight: 700 !important;
  letter-spacing: 0.01em;
  padding: 0.78rem 1.1rem !important;
  transition:
    transform 0.2s ease,
    box-shadow 0.2s ease,
    border-color 0.2s ease,
    background-color 0.2s ease,
    color 0.2s ease;
}

.btn:hover {
  transform: translateY(-1px);
}

.btn:active {
  transform: translateY(0);
}

.btn-outline-secondary {
  border-color: rgba(148, 163, 184, 0.4) !important;
  color: #334155 !important;
  background: rgba(255, 255, 255, 0.7) !important;
}

.btn-outline-secondary:hover {
  color: #0f172a !important;
  border-color: rgba(100, 116, 139, 0.45) !important;
  background: rgba(255, 255, 255, 0.95) !important;
  box-shadow: var(--editor-shadow-sm);
}

.btn-outline-primary {
  border-color: rgba(79, 70, 229, 0.28) !important;
  color: var(--editor-primary) !important;
  background: rgba(79, 70, 229, 0.06) !important;
}

.btn-outline-primary:hover {
  color: #fff !important;
  border-color: var(--editor-primary) !important;
  background: linear-gradient(135deg, var(--editor-primary), #6366f1) !important;
  box-shadow: 0 16px 34px rgba(79, 70, 229, 0.25);
}

.btn-outline-danger {
  border-color: rgba(239, 68, 68, 0.22) !important;
  color: var(--editor-danger) !important;
  background: rgba(239, 68, 68, 0.06) !important;
}

.btn-outline-danger:hover {
  color: #fff !important;
  background: linear-gradient(135deg, #ef4444, #dc2626) !important;
  border-color: #dc2626 !important;
  box-shadow: 0 14px 28px rgba(220, 38, 38, 0.22);
}

.btn-primary {
  min-height: 56px;
  border: none !important;
  border-radius: 18px !important;
  background: linear-gradient(135deg, #4f46e5 0%, #6366f1 48%, #0ea5e9 100%) !important;
  box-shadow: 0 18px 40px rgba(79, 70, 229, 0.28);
  font-size: 1rem;
  font-weight: 800 !important;
}

.btn-primary:hover {
  box-shadow: 0 22px 48px rgba(79, 70, 229, 0.34);
}

.btn-primary:disabled {
  opacity: 0.72;
  cursor: not-allowed;
  transform: none;
}

/* bloco de importação */
.card.mb-4:first-of-type {
  position: relative;
  overflow: hidden;
}

.card.mb-4:first-of-type::before {
  content: "";
  position: absolute;
  inset: 0 0 auto 0;
  height: 4px;
  background: linear-gradient(90deg, #4f46e5, #06b6d4, #22c55e);
}

.card.mb-4:first-of-type textarea.form-control {
  background:
    linear-gradient(180deg, rgba(248,250,252,0.96), rgba(255,255,255,0.98)) !important;
}

/* alertas */
.alert {
  border: none !important;
  border-radius: 18px !important;
  padding: 1rem 1rem !important;
  box-shadow: var(--editor-shadow-sm);
}

.alert-warning {
  background: linear-gradient(180deg, rgba(255, 251, 235, 0.98), rgba(255, 247, 237, 0.96)) !important;
  color: #78350f !important;
}

.alert-danger {
  background: linear-gradient(180deg, rgba(254, 242, 242, 0.98), rgba(254, 226, 226, 0.96)) !important;
  color: #7f1d1d !important;
}

.text-danger.small,
.text-success.small {
  display: inline-flex;
  align-items: center;
  gap: 0.45rem;
  font-weight: 700;
  margin-top: 0.7rem !important;
}

.text-danger.small::before,
.text-success.small::before {
  width: 8px;
  height: 8px;
  content: "";
  border-radius: 999px;
  display: inline-block;
}

.text-danger.small::before {
  background: #ef4444;
}

.text-success.small {
  color: var(--editor-success) !important;
}

.text-success.small::before {
  background: #10b981;
}

/* badges */
.badge.text-bg-light {
  padding: 0.55rem 0.85rem;
  border-radius: 999px;
  background: linear-gradient(135deg, rgba(79, 70, 229, 0.1), rgba(14, 165, 233, 0.08)) !important;
  color: #312e81 !important;
  border: 1px solid rgba(79, 70, 229, 0.14);
  font-size: 0.82rem;
  font-weight: 700;
  letter-spacing: 0.01em;
}

/* faq */
.card .row.g-2.mb-2 {
  margin-bottom: 0.85rem !important;
  padding: 0.85rem;
  border-radius: 18px;
  background: rgba(248, 250, 252, 0.7);
  border: 1px solid rgba(148, 163, 184, 0.14);
}

.card .row.g-2.mb-2 .btn {
  min-height: 52px;
}

/* checkboxes */
label {
  display: inline-flex;
  align-items: center;
  gap: 0.6rem;
  color: var(--editor-text);
  font-weight: 700;
}

input[type="checkbox"] {
  width: 18px;
  height: 18px;
  accent-color: var(--editor-primary);
  cursor: pointer;
}

/* reading time */
.card .d-flex.gap-4.align-items-center span {
  padding: 0.6rem 0.9rem;
  border-radius: 999px;
  background: rgba(79, 70, 229, 0.08);
  color: #312e81;
  font-weight: 700;
}

/* mensagens de erro do inertia */
div[role="alert"],
.alert-danger > div {
  font-weight: 600;
  line-height: 1.6;
}

/* separação visual do botão final */
form > .btn.btn-primary:last-child {
  position: sticky;
  bottom: 18px;
  z-index: 20;
}

/* links do inertia */
a.btn,
a.btn:hover,
a.btn:focus {
  text-decoration: none;
}

/* responsivo */
@media (max-width: 991.98px) {
  .container.py-4 {
    max-width: 100%;
    padding-left: 1rem;
    padding-right: 1rem;
  }

  .container.py-4 > .d-flex.justify-content-between.align-items-center.mb-3 {
    gap: 1rem;
    align-items: flex-start !important;
    flex-direction: column;
  }

  .card.p-3 {
    padding: 1.15rem !important;
  }

  .btn-primary {
    width: 100%;
  }

  form > .btn.btn-primary:last-child {
    bottom: 12px;
  }
}

@media (max-width: 767.98px) {
  .container.py-4 {
    padding-left: 0.75rem;
    padding-right: 0.75rem;
  }

  .container.py-4 h1.h3 {
    font-size: 1.25rem;
  }

  .card.p-3 {
    padding: 1rem !important;
    border-radius: 18px !important;
  }

  .form-control,
  .form-select,
  .btn {
    min-height: 46px;
    font-size: 0.94rem;
  }

  textarea.form-control[rows="10"] {
    min-height: 280px;
  }

  .d-flex.gap-2.mt-3,
  .d-flex.gap-4.align-items-center {
    flex-direction: column;
    align-items: stretch !important;
  }

  .card .d-flex.gap-4.align-items-center span {
    width: 100%;
    text-align: center;
  }

  .card .row.g-2.mb-2 {
    padding: 0.7rem;
  }
}

@media (max-width: 575.98px) {
  .container.py-4 > .d-flex.justify-content-between.align-items-center.mb-3 {
    padding: 1rem;
  }

  .card h2.h5 {
    font-size: 0.98rem;
  }

  .form-control,
  .form-select {
    padding-left: 0.9rem !important;
    padding-right: 0.9rem !important;
  }

  .btn {
    width: 100%;
  }

  .badge.text-bg-light {
    width: 100%;
    justify-content: center;
    text-align: center;
  }
}
</style>