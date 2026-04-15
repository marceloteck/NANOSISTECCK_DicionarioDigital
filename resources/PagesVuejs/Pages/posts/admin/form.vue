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
  "category_id": 1,
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
  "tags": [1, "seo", "conteudo"],
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
  category_id: post.value.category_id || '',
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

const buildPayload = () => ({
  title: form.title,
  slug: form.slug,
  excerpt: form.excerpt,
  author_name: form.author_name,
  status: form.status,
  published_at: form.published_at || null,
  category_id: form.category_id || null,
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

    importDiagnostics.value = [...diagnostics, ...flattenedFieldErrors];
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
            <select v-model="form.category_id" class="form-select">
              <option value="">Sem categoria</option>
              <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
            </select>
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
