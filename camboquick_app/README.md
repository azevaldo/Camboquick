Camboquick — Aplicação Laravel

Esta pasta contém a aplicação web do Camboquick, sistema de gestão de vendas e estoque desenvolvido com Laravel e PHP.

Para conhecer o sistema, suas funcionalidades, módulos e regras de acesso, consulte o README principal.

🛠️ Tecnologias
PHP
Laravel
HTML5
CSS3
Bootstrap
JavaScript
Banco de dados relacional
📋 Requisitos

Antes de iniciar a instalação, certifique-se de possuir:

PHP instalado.
Composer instalado.
Banco de dados compatível com a aplicação.
Node.js e npm, caso os recursos frontend do projeto utilizem Vite/NPM.
📥 Instalação

Existem duas formas de preparar o banco de dados da aplicação.

Opção 1 — Utilizar a base de dados disponibilizada

Esta opção utiliza a base de dados que está disponível no repositório.

1. Clonar o repositório
git clone <URL-DO-REPOSITORIO>

Entre na pasta do projeto Laravel:

cd <pasta-do-laravel>
2. Instalar as dependências do PHP
composer install
3. Configurar o arquivo .env

Crie o arquivo .env a partir do exemplo:

cp .env.example .env

No Windows, também é possível criar o arquivo manualmente a partir do .env.example.

Configure as informações de conexão com o banco de dados.

Exemplo:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=camboquick_lua_cheia
DB_USERNAME=root
DB_PASSWORD=

Ajuste DB_USERNAME e DB_PASSWORD conforme a configuração do seu ambiente.

4. Gerar a chave da aplicação
php artisan key:generate
5. Importar a base de dados

Importe o arquivo de banco de dados disponibilizado no repositório para o seu servidor MySQL/MariaDB.

Depois, confirme se o nome da base de dados corresponde ao configurado no .env:

DB_DATABASE=camboquick_lua_cheia

Nesta opção, não é necessário executar php artisan migrate nem php artisan db:seed, pois a base de dados já será disponibilizada no repositório.

🗄️ Opção 2 — Criar o banco utilizando Laravel

Também é possível configurar a aplicação utilizando as migrations e seeders do Laravel.

1. Instalar as dependências
composer install
2. Configurar o .env

Crie o arquivo:

cp .env.example .env

Configure a conexão com o banco de dados:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=camboquick_lua_cheia
DB_USERNAME=root
DB_PASSWORD=

Antes de executar as migrations, certifique-se de que o banco de dados configurado existe no servidor MySQL/MariaDB.

3. Gerar a chave da aplicação
php artisan key:generate
4. Executar as migrations
php artisan migrate
5. Executar o seeder
php artisan db:seed

As migrations criarão a estrutura do banco de dados e o seeder poderá inserir os dados iniciais necessários para a aplicação.

📦 Dependências do frontend

Caso o projeto possua dependências JavaScript configuradas no package.json, instale-as com:

npm install

Durante o desenvolvimento, execute:

npm run dev

Se o projeto não utilizar um processo de desenvolvimento frontend separado, este passo pode ser ignorado.

▶️ Executando a aplicação

Depois de concluir a configuração:

php artisan serve

Por padrão, a aplicação poderá ser acessada em:

http://127.0.0.1:8000
🔧 Principais comandos
Iniciar o servidor
php artisan serve
Executar migrations
php artisan migrate
Executar seeders
php artisan db:seed
Limpar os caches da aplicação
php artisan optimize:clear
Instalar dependências PHP
composer install
Instalar dependências JavaScript
npm install
🏗️ Arquitetura

A aplicação utiliza a arquitetura MVC (Model-View-Controller) do Laravel.

Principais diretórios:

app/
├── Http/
├── Models/
└── ...

database/
├── migrations/
├── seeders/
└── ...

resources/
├── views/
└── ...

routes/
├── web.php
└── ...

public/
app/

Contém a lógica principal da aplicação, incluindo models, controllers e outros componentes.

database/

Contém as migrations e seeders utilizados para estruturar e inicializar o banco de dados.

resources/

Contém os recursos da interface da aplicação, incluindo as views.

routes/

Contém as rotas utilizadas pela aplicação.

public/

Contém os arquivos públicos da aplicação.

🔐 Controle de acesso

O sistema possui diferentes funções de usuário:

Administrador
Gerente
Caixa

As permissões de cada função determinam quais funcionalidades podem ser acessadas dentro da aplicação.

🗃️ Base de dados

O repositório contém uma cópia da base de dados para facilitar a configuração do projeto.

Também é possível criar a estrutura do banco utilizando as migrations e inicializar os dados através dos seeders.

Nome utilizado na configuração:

DB_DATABASE=camboquick_lua_cheia
🔙 Voltar para a documentação principal

Para conhecer o objetivo do Camboquick, suas funcionalidades, módulos e níveis de acesso:

👉 Voltar para o README principal

Camboquick — Sistema de gestão de vendas e estoque

Desenvolvido por Azevaldo Caluaco.
