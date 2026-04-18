<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const seo = computed(() => page.props.seo ?? {});
const institutionalPage = computed(() => page.props.institutionalPage ?? {});

const contentByKey = {
  about: {
    lead: 'Somos um projeto editorial criado para ajudar o usuário a entender, de forma clara e confiável, termos, siglas, gírias, expressões, emojis e referências que aparecem na internet, nas redes sociais e no ambiente digital do dia a dia.',
    blocks: [
      'Nosso objetivo é transformar dúvidas comuns em respostas simples, úteis e bem organizadas. Em vez de explicações confusas ou superficiais, buscamos apresentar conteúdo direto, escaneável e fácil de compreender, mesmo para quem teve o primeiro contato com o termo há poucos segundos.',
      'Publicamos páginas pensadas para atender a intenção de busca do usuário final. Isso significa explicar o significado de cada termo com contexto, exemplos práticos, linguagem acessível e organização visual que facilite a leitura em computador, tablet e celular.',
      'Trabalhamos para que cada conteúdo seja útil de verdade. Sempre que possível, mostramos onde o termo costuma aparecer, como ele é usado, o que ele quer dizer em diferentes contextos e quais expressões relacionadas podem ajudar o visitante a continuar aprendendo.',
      'Nossa linha editorial é baseada em clareza, utilidade e experiência. Por isso, evitamos complicação desnecessária, excesso de termos técnicos e textos feitos apenas para mecanismos de busca. O foco principal é oferecer uma experiência informativa, honesta e agradável para pessoas reais.',
      'Também valorizamos atualização e revisão contínua. A linguagem da internet muda rapidamente, novas expressões surgem com frequência e certos significados podem variar conforme o contexto, a plataforma e o comportamento do público. Por isso, nosso conteúdo pode ser revisado, ampliado e atualizado sempre que necessário.',
      'Além do significado de palavras e expressões, buscamos organizar os conteúdos de forma que o usuário encontre respostas rápidas e, ao mesmo tempo, tenha a opção de aprofundar a leitura. Dessa forma, cada página pode servir tanto para uma consulta imediata quanto para uma navegação mais completa dentro do site.',
      'Nosso compromisso é manter um projeto editorial útil, responsável e alinhado a boas práticas de qualidade, transparência e experiência do usuário. Isso inclui apresentar conteúdo original, estrutura clara, navegação intuitiva e informações institucionais acessíveis para quem deseja entender melhor como o site funciona.',
      'Se você chegou até aqui procurando entender um termo, uma sigla ou uma expressão usada na internet, nossa proposta é simples: ajudar você a encontrar uma resposta clara, rápida e confiável, com conteúdo pensado para informar de verdade.'
    ],
  },
  contact: {
    lead: 'Esta é a página oficial de contato do projeto. Aqui você pode falar com nossa equipe sobre conteúdos, sugestões, correções ou assuntos relacionados ao site.',
    blocks: [
      'Se você encontrou algum termo que gostaria de ver explicado, pode enviar sua sugestão. Estamos sempre avaliando novos conteúdos com base nas dúvidas mais comuns dos usuários e no que está em alta na internet.',
      'Também é possível entrar em contato para reportar correções. Caso identifique alguma informação desatualizada, incompleta ou que possa ser melhorada, sua contribuição é bem-vinda e ajuda a manter a qualidade do conteúdo.',
      'Para assuntos relacionados a parcerias, colaborações ou propostas comerciais, utilize o mesmo canal de contato. Todas as solicitações são analisadas conforme relevância e alinhamento com o projeto.',
      'Nosso principal canal de comunicação é o e-mail: nanosistecck@gmail.com. Ao entrar em contato, procure descrever sua solicitação de forma clara para facilitar o atendimento.',
      'O tempo de resposta pode variar de acordo com o volume de mensagens recebidas. Cada solicitação é analisada e respondida conforme prioridade e disponibilidade da equipe.',
      'Não solicitamos dados sensíveis e recomendamos que você não compartilhe informações pessoais desnecessárias ao enviar sua mensagem. O contato deve ser feito apenas para assuntos relacionados ao funcionamento do site.',
      'Ao utilizar este canal, você concorda em manter uma comunicação respeitosa e objetiva. Mensagens com conteúdo inadequado, spam ou fora do contexto do site podem não receber resposta.',
      'Este canal existe para facilitar a comunicação entre o usuário e o projeto, garantindo mais transparência, melhoria contínua do conteúdo e uma experiência mais completa para todos os visitantes.'
    ],
  },
  privacy: {
    lead: 'Esta página explica como coletamos, utilizamos e protegemos as informações dos usuários que acessam nosso site. Nosso compromisso é garantir transparência, segurança e respeito à privacidade durante a navegação.',
    blocks: [
      'Coletamos algumas informações de forma automática quando você acessa o site, como endereço IP, tipo de dispositivo, navegador utilizado, páginas visitadas e tempo de permanência. Esses dados são utilizados exclusivamente para análise de desempenho, melhoria da experiência do usuário e funcionamento adequado da plataforma.',
      
      'Utilizamos cookies e tecnologias semelhantes para entender como o site é utilizado, personalizar conteúdo, melhorar a navegação e, quando aplicável, exibir anúncios mais relevantes. Cookies são pequenos arquivos armazenados no seu navegador e você pode desativá-los a qualquer momento nas configurações do seu dispositivo.',
      
      'Este site pode exibir anúncios de terceiros, incluindo serviços como o Google AdSense. Esses serviços podem utilizar cookies e outras tecnologias para coletar informações sobre suas visitas e exibir anúncios com base em seus interesses. Não temos controle direto sobre esses dados coletados por terceiros.',
      
      'O Google pode utilizar o cookie DoubleClick para exibir anúncios personalizados com base em visitas anteriores a este e a outros sites. Você pode desativar a personalização de anúncios acessando as configurações de anúncios do Google.',
      
      'Não solicitamos dados pessoais sensíveis diretamente ao usuário, como documentos, senhas ou informações financeiras. Caso você entre em contato conosco por e-mail, as informações fornecidas serão utilizadas apenas para responder à sua solicitação.',
      
      'Adotamos medidas básicas de segurança para proteger as informações coletadas, mas é importante lembrar que nenhum sistema é totalmente livre de riscos. Recomendamos que você também utilize boas práticas de segurança ao navegar na internet.',
      
      'Este site pode conter links para páginas externas. Não somos responsáveis pelas políticas de privacidade ou práticas de outros sites. Recomendamos que você leia as políticas de cada site que visitar.',
      
      'Ao continuar navegando em nosso site, você concorda com esta política de privacidade e com o uso das tecnologias descritas nesta página.',
      
      'Esta política pode ser atualizada a qualquer momento para refletir mudanças legais, técnicas ou operacionais. Recomendamos que você revise esta página periodicamente para se manter informado.'
    ],
  },
  terms: {
    lead: 'Estes termos de uso definem as regras para acesso, navegação e utilização do conteúdo disponível neste site. Ao continuar utilizando a plataforma, você concorda com as condições descritas nesta página.',
    blocks: [
      'O conteúdo disponibilizado neste site tem caráter informativo e educacional. Nosso objetivo é explicar termos, expressões e conceitos utilizados na internet de forma clara e acessível, sem garantir aplicação específica em todos os contextos possíveis.',
      
      'Ao acessar o site, você concorda em utilizar as informações de forma responsável, respeitando a legislação vigente e sem fazer uso indevido dos conteúdos apresentados.',
      
      'Todo o conteúdo publicado, incluindo textos, estrutura e organização das páginas, é de responsabilidade do projeto e não deve ser copiado, reproduzido ou redistribuído sem autorização prévia, exceto nos casos permitidos por lei.',
      
      'Não nos responsabilizamos por interpretações incorretas, decisões tomadas com base no conteúdo ou qualquer uso inadequado das informações apresentadas. O usuário é responsável pela forma como utiliza o conteúdo disponível.',
      
      'O site pode conter links para páginas externas. Esses links são fornecidos apenas para facilitar a navegação e não representam garantia, aprovação ou responsabilidade sobre conteúdos de terceiros.',
      
      'Podemos exibir anúncios, recomendações e conteúdos patrocinados ao longo da navegação. Esses elementos fazem parte da manutenção do projeto e podem ser personalizados com base no comportamento do usuário.',
      
      'O uso do site deve ser feito de forma respeitosa. Não é permitido tentar explorar falhas, comprometer a segurança da plataforma ou utilizar o ambiente para atividades ilegais ou prejudiciais.',
      
      'Reservamo-nos o direito de alterar, atualizar ou remover conteúdos e funcionalidades a qualquer momento, sem aviso prévio, visando melhorias, ajustes técnicos ou adequação a novas diretrizes.',
      
      'Estes termos podem ser modificados periodicamente para refletir mudanças legais, operacionais ou estruturais. Recomendamos que você revise esta página regularmente para se manter informado.',
      
      'Em caso de dúvidas sobre estes termos, você pode entrar em contato através do e-mail: nanosistecck@gmail.com.'
    ],
  },
};

const pageContent = computed(() => contentByKey[institutionalPage.value.key] ?? {
  lead: `Esta página reúne informações institucionais sobre ${institutionalPage.value.title}, com o objetivo de oferecer transparência e clareza sobre o funcionamento do site.`,
  blocks: [
    'No momento, o conteúdo desta seção ainda está em fase de atualização e poderá ser ampliado para trazer informações mais completas e detalhadas.',
    'Enquanto isso, você pode continuar navegando pelo site para acessar nossos conteúdos principais, incluindo explicações de termos, gírias e expressões utilizadas na internet.',
    'Recomendamos visitar esta página novamente em breve para acompanhar possíveis atualizações e melhorias nas informações institucionais do projeto.'
  ],
});
</script>

<template>
  <AppHead v-bind="seo" />
  <InstitutionalLayout :title="institutionalPage.title" page-type="institutional">
    <h1 class="h3 mb-3">{{ institutionalPage.title }}</h1>
    <p class="text-secondary">{{ pageContent.lead }}</p>

    <div class="d-grid gap-3 mt-4">
      <p v-for="(block, index) in pageContent.blocks" :key="index" class="mb-0">{{ block }}</p>
    </div>

    <div class="mt-4 d-flex gap-2 flex-wrap">
      <Link :href="route('index.home')" class="btn btn-outline-secondary btn-sm">Voltar para a Home</Link>
      <Link v-if="route().has('search.index')" :href="route('search.index')" class="btn btn-primary btn-sm">Ir para busca</Link>
    </div>
  </InstitutionalLayout>
</template>
