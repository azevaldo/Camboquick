Camboquick

Sistema de gestão de vendas e estoque desenvolvido para o Camboquick, um bar localizado no bairro do Gama, município da Catumbela, província de Benguela, Angola.

O sistema foi desenvolvido para apoiar a gestão diária do estabelecimento, permitindo controlar vendas, estoque, fornecedores, pedidos por mesa, usuários, faturamento, lucros e relatórios.

🎯 Objetivo

O Camboquick tem como objetivo centralizar a gestão das operações de vendas e estoque do estabelecimento, facilitando o controle dos produtos comercializados, das vendas realizadas e das informações financeiras.

O sistema permite acompanhar desde a entrada de produtos no estoque até a venda ao cliente, incluindo o gerenciamento de pedidos realizados nas mesas.

📌 Principais funcionalidades
🛒 Vendas
Registro de vendas de produtos.
Consulta de vendas realizadas.
Cancelamento de vendas.
Emissão de faturas.
Consulta detalhada das operações de venda.
📦 Gestão de estoque
Cadastro e gerenciamento de produtos.
Entrada de produtos no estoque.
Consulta do estoque.
Controle das movimentações de estoque.
Cancelamento de entradas de estoque.
🏪 Fornecedores
Cadastro de fornecedores.
Consulta e gerenciamento dos fornecedores.
Associação de produtos e movimentações aos fornecedores.
🍽️ Pedidos por mesa

O sistema possui um módulo específico para gerenciamento de pedidos realizados nas mesas.

É possível:

Abrir uma conta para uma mesa.
Associar a conta a um cliente e ao usuário responsável pelo atendimento.
Adicionar vários produtos ao mesmo pedido.
Continuar adicionando itens enquanto a conta estiver aberta.
Consultar os itens da conta.
Finalizar o pedido.

Esse módulo permite acompanhar o consumo de uma mesa até o encerramento da conta.

💰 Lucros e consultas

O sistema permite consultar informações relacionadas às vendas e aos resultados do estabelecimento, incluindo:

Consultas de vendas.
Consultas por período.
Consultas mensais.
Consultas anuais.
Informações relacionadas ao lucro.
Consultas detalhadas das movimentações do sistema.
📊 Relatórios

O sistema disponibiliza relatórios relacionados às operações do estabelecimento, incluindo informações sobre:

Vendas.
Estoque.
Lucros.
Movimentações realizadas no sistema.
👥 Usuários e permissões

O sistema possui três níveis principais de usuários:

Administrador

Responsável pela administração dos usuários do sistema.

Pode:

Cadastrar usuários.
Gerenciar usuários.
Alterar a função dos usuários.
Promover um caixa para gerente.
Alterar um gerente para caixa.
Remover usuários do sistema.
Gerente

Possui acesso às principais funcionalidades de gestão do sistema.

Pode gerenciar operações relacionadas a:

Vendas.
Estoque.
Produtos.
Fornecedores.
Pedidos.
Consultas.
Lucros.
Relatórios.
Caixa

Responsável principalmente pela realização das vendas.

Pode:

Registrar vendas.
Registrar pedidos.
Consultar informações necessárias para o atendimento.
Finalizar pedidos e vendas conforme as permissões disponíveis.

A função de um usuário pode ser alterada pelo administrador conforme a necessidade do estabelecimento.

🏗️ Estrutura do projeto

O repositório está organizado separando a documentação geral do projeto da aplicação Laravel:

camboquick/
│
├── README.md
│
├── <pasta-do-laravel>/
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── public/
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── artisan
│   ├── composer.json
│   └── README.md
│
└── Banco de dados/
    └── ...

O README.md da raiz apresenta o sistema e suas funcionalidades.

O README.md localizado dentro da aplicação Laravel contém as instruções técnicas para instalação e configuração do projeto.

🛠️ Tecnologias utilizadas
PHP
Laravel
HTML5
CSS3
Bootstrap
JavaScript
Banco de dados relacional

A aplicação utiliza o padrão arquitetural MVC (Model-View-Controller) disponibilizado pelo Laravel.

⚙️ Instalação

As instruções completas para instalação e configuração da aplicação estão disponíveis no README localizado dentro do projeto Laravel.

O projeto possui duas formas de configuração:

Utilizando a base de dados disponibilizada no repositório.
Criando e configurando a base de dados através das migrations e seeders do Laravel.

👉 Ver instruções de instalação

🗄️ Banco de dados

O repositório disponibiliza uma cópia da base de dados utilizada pelo sistema para facilitar a configuração e utilização do projeto.

Quando utilizada a base de dados disponibilizada, é necessário configurar corretamente as informações de conexão no arquivo .env, incluindo o nome da base de dados:

DB_DATABASE=camboquick_lua_cheia

As instruções detalhadas estão disponíveis no README técnico da aplicação.

📍 Sobre o projeto

Camboquick
Bairro do Gama — Catumbela
Província de Benguela — Angola

O sistema foi desenvolvido especificamente para apoiar a gestão do estabelecimento Camboquick.

👨‍💻 Desenvolvimento

Desenvolvido por Azevaldo Caluaco.
