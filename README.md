# bate-papo-api-php
Projeto que consiste num sistema de atendimento via chat em tempo real, utilizando das seguintes ferramentas:

- Mini Framework PHP criado por mim, focado na versatilidade de projetos;
- JS puro (tem uma pequena biblioteca criada também por mim, baseada em jQuery);
- CSS puro;
- HTML;
- MySql;

🏭 Funcionalidades:

- Criação de chat de atendimento, atualizado em tempo real;
- Registro de histórico de atendimentos;
- Paginação de mensagens e atendimentos automática;
- Finalização de chats, bloqueando o recebimento de mensagens;
- Alternância de visão do chat do atendente e do cliente;

✔️ Pré-requisitos de ambiente (baseados no que desenvolvi):

- PHP   >= 8.1.0
- MySql >= 5.7.36

---

## Primeiros passos antes de iniciar:

- Clone o código para sua máquina da maneira que preferir - se lembrando que deverá rodar o projeto num servidor PHP;
- Configure o arquivo _includes/autoload/conexao.php_ conforme seu ambiente de Banco de Dados. Ele vem com as configurações que utilizei para rodar localmente no Wamp;
- Na pasta _extras/_ você pode encontrar o arquivo .sql com a estrutura das base de dados e duas tabelas que utilizei para esse projeto, basta importar! (Se modificar a variável $banco no arquivo _includes/autoload/conexao.php_, não se esqueça de atualizar o nome de sua base de dados também!);
- Após concluir os passos anteriores, basta acessar a página inicial do projeto e começar com os testes;

---

## O que você pode fazer?

Assim que iniciar o projeto utilize o menu lateral para ter acesso às 3 telas principais do projeto:

- Página Inicial: te apresenta os mais recentes atendimentos/chats em aberto, limitando a 5 itens. Visa facilitar o acesso às atividades recentes;
- Iniciar Atendimento: aqui você poderá criar uma nova sala de chat após inserir um título pra ela. Assim que ela for gerada, você será redirecionado automaticamente e terá acesso ao chat. *Abaixo você terá mais informações sobre como utilizar todos os seus recursos);
- Histórico de atendimentos: você pode consultar todos os atendimentos já gerados, sejam eles disponíveis ou finalizados, assim como suas mensagens. A página de itens é automática (5 em 5 itens);

---

## 👥 Sala de atendimento (chat)

Sempre que acessar um chat, você entrará com a visão do atendente (ou remetente 1). Logo abaixo do título do atendimento você poderá (e recomendo que faça isso) abrir em uma nova aba a visão de chat do cliente (ou remetente 2). Para uma melhor experiência e entendimento, é interessante separar cada chat em uma janela e mantê-la uma ao lado da outra.
As funcionalidades dessa página são:

- Envio de mensagem que refletirá em tempo real na outra sala de bate papo;
- Paginação de mensagens passadas (5 em 5 itens);
- Destaque na página assim que uma nova mensagem for recebida;
- Possibilidade de finalizar o chat a qualquer momento (o envio de mensagens automaticamente se encerra);

---

## Limitações conhecidas

- Caso sejam abertas mais de uma aba da mesma visão (atendente ou cliente) de um determinado chat, as mensagens enviadas por esse remetente não serão sincronizadas entre essas abas em tempo real;
- (Fica mais como uma escolha de padrão de desenvolvimento, mas...) Todas as requisições são realizadas via POST para padronizar os envios e recebimentos de informações por JSON (não utilizar os verbos GET, PUT ou DELETE);

