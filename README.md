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

Primeiros passos antes de iniciar:

- Clone o código para sua máquina da maneira que preferir - se lembrando que deverá rodar o projeto num servidor PHP;
- Configure o arquivo _includes/autoload/conexao.php_ conforme seu ambiente de Banco de Dados. Ele vem com as configurações que utilizei para rodar localmente no Wamp;
- Na pasta _extras/_ você pode encontrar o arquivo .sql com a estrutura das base de dados e duas tabelas que utilizei para esse projeto, basta importar! (Se modificar a variável $banco no arquivo _includes/autoload/conexao.php_, não se esqueça de atualizar o nome de sua base de dados também!);
- Após concluir os passos anteriores, basta acessar a página inicial do projeto e começar com os testes;

---

O que você pode fazer?

...em breve
