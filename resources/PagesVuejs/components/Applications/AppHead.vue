<template>
  <Head :title="finalTitle">
    <link head-key="canonical" rel="canonical" :href="canonicalUrl" />

    <meta head-key="description" name="description" :content="finalDescription" />

    <link v-if="defaults.favicon" head-key="favicon" rel="icon" :href="normalizeUrl(defaults.favicon)" />
    <meta v-if="keywordString" head-key="keywords" name="keywords" :content="keywordString" />
    <meta v-if="finalAuthor" head-key="author" name="author" :content="finalAuthor" />
    <meta head-key="robots" name="robots" :content="robotsContent" />

    <meta head-key="og:type" property="og:type" :content="finalType" />
    <meta head-key="og:title" property="og:title" :content="finalTitle" />
    <meta head-key="og:description" property="og:description" :content="finalDescription" />
    <meta head-key="og:url" property="og:url" :content="canonicalUrl" />
    <meta head-key="og:image" property="og:image" :content="finalImage" />
    <meta v-if="defaults.siteName" head-key="og:site_name" property="og:site_name" :content="defaults.siteName" />
    <meta v-if="defaults.locale" head-key="og:locale" property="og:locale" :content="defaults.locale" />

    <meta head-key="twitter:card" name="twitter:card" :content="defaults.twitterCard" />
    <meta head-key="twitter:title" name="twitter:title" :content="finalTitle" />
    <meta head-key="twitter:description" name="twitter:description" :content="finalDescription" />
    <meta head-key="twitter:image" name="twitter:image" :content="finalImage" />
    <meta v-if="defaults.twitterSite" head-key="twitter:site" name="twitter:site" :content="defaults.twitterSite" />
    <meta v-if="defaults.twitterCreator" head-key="twitter:creator" name="twitter:creator" :content="defaults.twitterCreator" />

    <meta v-if="published_time" head-key="article:published_time" property="article:published_time" :content="published_time" />
    <meta v-if="modified_time" head-key="article:modified_time" property="article:modified_time" :content="modified_time" />
    <meta v-if="section" head-key="article:section" property="article:section" :content="section" />

    <meta
      v-for="(tag, index) in normalizedTags"
      :key="`article:tag:${index}`"
      :head-key="`article:tag:${index}`"
      property="article:tag"
      :content="tag"
    />

    <component
      v-if="schemaJson"
      :is="'script'"
      head-key="structured-data"
      type="application/ld+json"
      v-html="schemaJson"
    />

    <slot />
  </Head>
</template>

<script setup>
import { computed } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';

const props = defineProps({
  title: {
    type: String,
    default: '',
  },
  description: {
    type: String,
    default: '',
  },
  image: {
    type: String,
    default: '',
  },
  canonical: {
    type: String,
    default: '',
  },
  robots: {
    type: String,
    default: '',
  },
  type: {
    type: String,
    default: 'website',
  },
  author: {
    type: String,
    default: '',
  },
  published_time: {
    type: String,
    default: '',
  },
  modified_time: {
    type: String,
    default: '',
  },
  tags: {
    type: Array,
    default: () => [],
  },
  section: {
    type: String,
    default: '',
  },
  noindex: {
    type: Boolean,
    default: false,
  },
  schema: {
    type: [Object, Array, String],
    default: null,
  },
});

const page = usePage();
const browserOrigin = typeof window !== 'undefined' ? window.location.origin : '';
const browserUrl = typeof window !== 'undefined' ? window.location.href : '';

const runtimeSeoDefaults = computed(() => (page.props && page.props.seoDefaults) || {});
const runtimeEnvironment = computed(() => (page.props && page.props.environment) || '');
const runtimeCurrentUrl = computed(() => (page.props && page.props.current_url) || browserUrl);

const defaults = computed(() => ({
  siteName: runtimeSeoDefaults.value.site_name || import.meta.env.VITE_APP_NAME || 'Website',
  titleDefault: runtimeSeoDefaults.value.title_default || import.meta.env.VITE_APP_NAME || 'Website',
  titleSeparator: runtimeSeoDefaults.value.title_separator || ' | ',
  descriptionDefault: runtimeSeoDefaults.value.description_default || '',
  defaultImage: runtimeSeoDefaults.value.default_image || '',
  author: runtimeSeoDefaults.value.author || '',
  baseUrl: runtimeSeoDefaults.value.base_url || browserOrigin,
  locale: runtimeSeoDefaults.value.locale || 'pt_BR',
  twitterCard: (runtimeSeoDefaults.value.twitter && runtimeSeoDefaults.value.twitter.card) || 'summary_large_image',
  twitterSite: (runtimeSeoDefaults.value.twitter && runtimeSeoDefaults.value.twitter.site) || '',
  twitterCreator: (runtimeSeoDefaults.value.twitter && runtimeSeoDefaults.value.twitter.creator) || '',
  keywordsDefault: runtimeSeoDefaults.value.keywords_default || [],
  favicon: (page.props && page.props.project && page.props.project.branding && page.props.project.branding.favicon)
    || runtimeSeoDefaults.value.favicon
    || '',
}));

const normalizeUrl = (value) => {
  if (!value) {
    return '';
  }

  if (/^https?:\/\//i.test(value)) {
    return value;
  }

  return `${defaults.value.baseUrl.replace(/\/$/, '')}/${value.replace(/^\//, '')}`;
};

const normalizeCanonical = (value) => {
  const rawUrl = normalizeUrl(value);
  if (!rawUrl) {
    return '';
  }

  try {
    const url = new URL(rawUrl);
    const blockedParams = ['fbclid', 'gclid', 'msclkid'];
    Array.from(url.searchParams.keys()).forEach((key) => {
      if (key.startsWith('utm_') || blockedParams.includes(key)) {
        url.searchParams.delete(key);
      }
    });

    if (url.pathname !== '/') {
      url.pathname = url.pathname.replace(/\/+$/, '');
    }

    return url.toString();
  } catch (error) {
    return rawUrl;
  }
};

const finalTitle = computed(() => {
  if (!props.title) {
    return defaults.value.titleDefault;
  }

  return `${props.title}${defaults.value.titleSeparator}${defaults.value.siteName}`;
});

const finalDescription = computed(() => props.description || defaults.value.descriptionDefault);
const finalImage = computed(() => normalizeUrl(props.image || defaults.value.defaultImage));
const canonicalUrl = computed(() => normalizeCanonical(props.canonical || runtimeCurrentUrl.value));
const finalType = computed(() => props.type || 'website');
const finalAuthor = computed(() => props.author || defaults.value.author);

const normalizedTags = computed(() => props.tags.filter(Boolean));

const keywordString = computed(() => {
  const source = normalizedTags.value.length ? normalizedTags.value : defaults.value.keywordsDefault;
  return source.length ? source.join(', ') : '';
});

const robotsContent = computed(() => {
  const indexation = runtimeSeoDefaults.value.indexation || {};
  const enabled = indexation.enabled !== false;
  const allowedEnvs = Array.isArray(indexation.allow_in_env) ? indexation.allow_in_env : ['production'];
  const blockedByEnvironment = !enabled || !allowedEnvs.includes(runtimeEnvironment.value);
  const blockedPaths = Array.isArray(runtimeSeoDefaults.value.robots && runtimeSeoDefaults.value.robots.disallow_paths)
    ? runtimeSeoDefaults.value.robots.disallow_paths
    : [];
  const currentPath = (() => {
    try {
      return new URL(canonicalUrl.value).pathname;
    } catch (error) {
      return '/';
    }
  })();
  const blockedByPath = blockedPaths.some((path) => {
    const normalizedPath = String(path).charAt(0) === '/' ? String(path).slice(1) : String(path);
    return currentPath.startsWith(`/${normalizedPath}`);
  });

  if (props.noindex || blockedByEnvironment || blockedByPath) {
    return 'noindex, nofollow';
  }

  return props.robots || 'index, follow';
});

const schemaJson = computed(() => {
  if (!props.schema) {
    return '';
  }

  if (typeof props.schema === 'string') {
    return props.schema;
  }

  return JSON.stringify(props.schema);
});
</script>
