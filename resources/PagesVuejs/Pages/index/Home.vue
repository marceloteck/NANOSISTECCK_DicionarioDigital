<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AppHead from '../../components/Applications/AppHead.vue';
import Header from '../../components/Home/Header.vue';
import HeroSection from '../../components/Home/HeroSection.vue';
import CategoriesSection from '../../components/Home/CategoriesSection.vue';
import FeaturedSection from '../../components/Home/FeaturedSection.vue';
import PostsSection from '../../components/Home/PostsSection.vue';
import PostPreviewSection from '../../components/Home/PostPreviewSection.vue';
import FaqSection from '../../components/Home/FaqSection.vue';
import CtaSection from '../../components/Home/CtaSection.vue';
import Footer from '../../components/Home/Footer.vue';

const page = usePage();

const seo = computed(() => page.props.seo ?? {});
const homeData = computed(() => page.props.homeData ?? {});

const fallbackSearchSuggestions = [
  'cringe significado',
  'emoji 🤡 significado',
  'o que é ghosting',
  'fyp significado',
];

const fallbackSearchPreviews = [
  {
    label: 'Resultado sugerido',
    title: 'O que significa POV?',
    description: 'POV significa “point of view”, ou “ponto de vista”, e é usado para mostrar uma cena sob a perspectiva de alguém.',
  },
  {
    label: 'Relacionado',
    title: 'O que significa FYP?',
    description: 'FYP significa “For You Page”, a página principal de recomendação do TikTok.',
  },
];

const fallbackCategories = [
  {
    icon: '#',
    title: 'Gírias da Internet',
    description: 'Entenda palavras e expressões populares usadas em redes sociais, memes e conversas online.',
  },
  {
    icon: '♪',
    title: 'TikTok e Trends',
    description: 'Descubra siglas, tendências e expressões que aparecem em vídeos curtos e comentários virais.',
  },
  {
    icon: '☺',
    title: 'Emojis e Símbolos',
    description: 'Veja o que cada emoji pode significar em diferentes contextos da internet.',
  },
  {
    icon: '</>',
    title: 'Tecnologia Fácil',
    description: 'Aprenda termos digitais, nomes de recursos e conceitos online de forma simples e clara.',
  },
];

const fallbackFeatured = [
  {
    category: 'Gírias da Internet',
    title: 'O que significa POV? Entenda por que esse termo ficou tão popular',
    description: 'Veja o significado, onde é usado e por que ele aparece tanto em vídeos, memes e conteúdos do TikTok.',
    action: 'Ler artigo completo',
    href: '/posts/o-que-significa-pov',
  },
  {
    category: 'Emojis',
    title: 'Emoji 🤡 significado: ironia, vergonha ou brincadeira?',
    description: 'Entenda os sentidos mais comuns desse emoji e em quais situações ele muda totalmente o tom da conversa.',
    action: 'Ver significado',
  },
  {
    category: 'TikTok',
    title: 'O que significa FYP e por que essa sigla aparece tanto?',
    description: 'Descubra a ligação entre FYP, recomendação de vídeos e o algoritmo que influencia o alcance do conteúdo.',
    action: 'Descobrir agora',
  },
];

const fallbackPosts = [
  {
    category: 'Gírias da Internet',
    badge: 'Leitura rápida',
    title: 'O que significa low profile?',
    description: 'Entenda quando essa expressão é usada e por que ela ficou tão comum nas redes sociais.',
    href: '/posts/o-que-significa-pov',
  },
  {
    category: 'Emojis',
    badge: 'Muito pesquisado',
    title: 'Emoji 💀 significa morte mesmo?',
    description: 'Nem sempre. Em muitos contextos, esse emoji representa humor, exagero ou vergonha alheia.',
  },
  {
    category: 'TikTok',
    badge: 'Tendência',
    title: 'O que significa flopar?',
    description: 'Veja o que quer dizer flopar na internet e por que esse termo é usado quando algo não dá certo.',
  },
];

const fallbackTopSearches = [
  'o que significa cringe',
  'o que é ghosting',
  'emoji 🙏 significado',
  'o que significa random',
  'o que significa tankar',
];

const infoCards = [
  {
    title: 'Conteúdo direto ao ponto',
    description: 'Você encontra uma explicação clara, exemplos práticos e links para termos relacionados em poucos segundos.',
  },
  {
    title: 'Fácil de encontrar no Google',
    description: 'A estrutura facilita a busca e ajuda você a chegar mais rápido na resposta certa.',
  },
  {
    title: 'Leitura sem complicação',
    description: 'A navegação foi pensada para você ler, entender e continuar explorando novos termos com facilidade.',
  },
  {
    title: 'Sempre atualizada',
    description: 'Novos significados e tendências podem ser adicionados com frequência para manter você sempre por dentro.',
  },
];

const faqItems = [
  {
    question: 'O conteúdo é confiável para consulta rápida?',
    answer: 'Sim. As explicações seguem linguagem simples, contexto de uso e exemplos para você entender sem dificuldade.',
  },
  {
    question: 'Por que o visual é mais limpo e claro?',
    answer: 'Porque isso melhora o conforto na leitura, facilita a navegação e deixa sua experiência mais agradável no celular e no desktop.',
  },
  {
    question: 'É fácil encontrar os termos mais buscados?',
    answer: 'Sim. Você pode usar a busca, navegar por categorias e descobrir conteúdos relacionados na própria página.',
  },
  {
    question: 'A experiência fica boa mesmo com conteúdo patrocinado?',
    answer: 'Sim. Os espaços patrocinados são posicionados para não atrapalhar a leitura e manter o site organizado.',
  },
];

const ctaStats = [
  {
    title: 'Leitura confortável',
    description: 'Design elegante para você focar no que importa',
  },
  {
    title: 'Mais termos todo dia',
    description: 'Conteúdo em expansão para acompanhar as tendências',
  },
  {
    title: 'Navegação inteligente',
    description: 'Busca central e atalhos rápidos para cada assunto',
  },
];

const searchSuggestions = computed(() => homeData.value.searchSuggestions?.length ? homeData.value.searchSuggestions : fallbackSearchSuggestions);
const searchPreviews = computed(() => homeData.value.searchPreviews?.length ? homeData.value.searchPreviews : fallbackSearchPreviews);
const categories = computed(() => homeData.value.categories?.length ? homeData.value.categories : fallbackCategories);
const featured = computed(() => homeData.value.featured?.length ? homeData.value.featured : fallbackFeatured);
const posts = computed(() => homeData.value.posts?.length ? homeData.value.posts : fallbackPosts);
const topSearches = computed(() => homeData.value.topSearches?.length ? homeData.value.topSearches : fallbackTopSearches);
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

  <Header />

  <main>
    <HeroSection :suggestions="searchSuggestions" :previews="searchPreviews" />
    <CategoriesSection :items="categories" />
    <FeaturedSection :items="featured" />
    <PostsSection :posts="posts" :top-searches="topSearches" />
    <!--<PostPreviewSection :infos="infoCards" />-->
    <FaqSection :items="faqItems" />
    <CtaSection :stats="ctaStats" />
  </main>

  <Footer />
</template>
