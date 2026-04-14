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

const searchSuggestions = [
  'cringe significado',
  'emoji 🤡 significado',
  'o que é ghosting',
  'fyp significado',
];

const searchPreviews = [
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

const categories = [
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

const featured = [
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

const posts = [
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

const topSearches = [
  'o que significa cringe',
  'o que é ghosting',
  'emoji 🙏 significado',
  'o que significa random',
  'o que significa tankar',
];

const infoCards = [
  {
    title: 'Estrutura ideal do post',
    description: 'Título claro, resposta rápida, explicação simples, exemplos, perguntas frequentes e links para outros termos.',
  },
  {
    title: 'Boa para SEO',
    description: 'Essa estrutura ajuda o Google a entender melhor o conteúdo e ajuda o usuário a encontrar a resposta sem esforço.',
  },
  {
    title: 'Boa para retenção',
    description: 'O visitante lê, entende e já encontra caminhos para navegar por outros assuntos relacionados.',
  },
  {
    title: 'Pronta para o futuro',
    description: 'Depois essa base pode receber PHP, banco de dados, painel administrativo e busca mais inteligente.',
  },
];

const faqItems = [
  {
    question: 'Esse modelo já serve como base real do projeto?',
    answer: 'Sim. Ele já mostra a identidade visual, a experiência da home, a busca e o padrão de exibição dos posts.',
  },
  {
    question: 'Por que o visual claro foi escolhido?',
    answer: 'Porque ele favorece leitura longa, conforto visual, percepção de confiança e melhor adaptação para sites de conteúdo.',
  },
  {
    question: 'Esse modelo é bom para SEO?',
    answer: 'Sim. A estrutura foi organizada para facilitar escaneabilidade, semântica, navegação interna e expansão futura de conteúdo.',
  },
  {
    question: 'Essa base é boa para monetização?',
    answer: 'Sim. O layout foi pensado para leitura confortável, boa retenção e espaços adequados para monetização sem poluição visual.',
  },
];

const ctaStats = [
  {
    title: 'Visual premium',
    description: 'Elegante, claro e confortável aos olhos',
  },
  {
    title: 'Estrutura escalável',
    description: 'Pronta para crescer em posts, categorias e sistema',
  },
  {
    title: 'Experiência forte',
    description: 'Busca central, navegação intuitiva e foco no usuário',
  },
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

  <Header />

  <main>
    <HeroSection :suggestions="searchSuggestions" :previews="searchPreviews" />
    <CategoriesSection :items="categories" />
    <FeaturedSection :items="featured" />
    <PostsSection :posts="posts" :top-searches="topSearches" />
    <PostPreviewSection :infos="infoCards" />
    <FaqSection :items="faqItems" />
    <CtaSection :stats="ctaStats" />
  </main>

  <Footer />
</template>
