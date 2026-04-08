<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();

const seo = computed(() => page.props.seo ?? {});
const seoDefaults = computed(() => page.props.seoDefaults ?? {});
const enabledModules = computed(() => page.props.enabledModules ?? []);
const institutionalPages = computed(() => page.props.institutionalPages ?? []);

const quickLinks = computed(() => [
  { label: 'Home', route: 'index.home', enabled: true },
  { label: 'Login', route: 'login', enabled: page.props.canLogin },
  { label: 'Cadastro', route: 'register', enabled: page.props.canRegister },
  { label: 'Perfil', route: 'profile.edit', enabled: true },
  { label: 'Posts', route: 'posts.index', enabled: true },
  { label: 'Robots', href: '/robots.txt', enabled: true },
  { label: 'Sitemap', href: '/sitemap.xml', enabled: true },
]);

const toolsChecklist = [
  'Config global em config/seo.php',
  'AppHead.vue com SEO dinâmico',
  'SeoBuilder + SchemaBuilder + CanonicalUrl',
  'PostSeoMapper pronto para posts futuros',
  'RobotsBuilder + SitemapBuilder + SeoController',
  'Share global via Inertia (seoDefaults/base_url/current_url/environment)',
  'Controle de indexação por ambiente e páginas válidas',
  'Base preparada para AdSense',
];
</script>

<template>
  <AppHead
    :title="seo.title"
    :description="seo.description"
    :image="seo.image"
    :canonical="seo.canonical"
    :robots="seo.robots"
    :type="seo.type"
    :author="seo.author"
    :published_time="seo.published_time"
    :modified_time="seo.modified_time"
    :tags="seo.tags"
    :section="seo.section"
    :noindex="seo.noindex"
    :schema="seo.schema"
  />

  <HybridSiteLayout title="Home" page-type="home">
  <section class="container py-5">
    <header class="mb-4">
      <h1 class="display-6 fw-bold">Template Mestre SEO — Sandbox de Projeto</h1>
      <p class="text-secondary mb-0">
        Esta página valida toda a infraestrutura SEO (meta, schema, canonical, robots e sitemap)
        e serve como referência para iniciar novos projetos com padrão 10/10 para AdSense.
      </p>
    </header>

    <div class="row g-4">
      <div class="col-12 col-lg-6">
        <div class="card h-100 shadow-sm">
          <div class="card-body">
            <h2 class="h5">Rotas essenciais para iniciar</h2>
            <ul class="list-unstyled mb-0">
              <li v-for="item in quickLinks" :key="item.label" class="py-1">
                <template v-if="item.enabled">
                  <Link v-if="item.route" :href="route(item.route)" class="text-decoration-none">
                    {{ item.label }}
                  </Link>
                  <a v-else :href="item.href" class="text-decoration-none">{{ item.label }}</a>
                </template>
                <span v-else class="text-muted">{{ item.label }} (indisponível)</span>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="card h-100 shadow-sm">
          <div class="card-body">
            <h2 class="h5">Checklist da stack SEO ativa</h2>
            <ul class="mb-0">
              <li v-for="item in toolsChecklist" :key="item">{{ item }}</li>
            </ul>
          </div>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="card shadow-sm">
          <div class="card-body">
            <h2 class="h5">Configuração SEO carregada no runtime</h2>
            <pre class="bg-light p-3 rounded small mb-0">{{ JSON.stringify(seoDefaults, null, 2) }}</pre>
          </div>
        </div>
      </div>


      <div class="col-12 col-lg-6">
        <div class="card shadow-sm">
          <div class="card-body">
            <h2 class="h5">Módulos ativos neste projeto</h2>
            <ul class="mb-0">
              <li v-for="module in enabledModules" :key="module">{{ module }}</li>
            </ul>
          </div>
        </div>
      </div>

      <div class="col-12">
        <div class="card shadow-sm">
          <div class="card-body">
            <h2 class="h5">Páginas institucionais habilitadas</h2>
            <ul class="mb-0">
              <li v-for="item in institutionalPages" :key="item.key">{{ item.key }} (/{{ item.slug }})</li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-12">
        <div class="card shadow-sm">
          <div class="card-body">
            <h2 class="h5">Ambiente técnico</h2>
            <p class="mb-1"><strong>Laravel:</strong> {{ page.props.laravelVersion }}</p>
            <p class="mb-0"><strong>PHP:</strong> {{ page.props.phpVersion }}</p>
          </div>
        </div>
      </div>
    </div>
  </section>
</HybridSiteLayout>
</template>
