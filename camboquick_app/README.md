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
MySQL/MariaDB
📋 Requisitos

Antes de iniciar a instalação, certifique-se de possuir:

PHP instalado.
Composer instalado.
MySQL ou MariaDB.
Um ambiente local para executar a aplicação, como XAMPP, Laragon ou similar.
📥 Instalação

Existem duas formas de configurar o banco de dados do Camboquick.

Opção 1 — Utilizar a base de dados disponibilizada

Esta é a forma mais simples de executar o projeto.

A base de dados já está disponibilizada no repositório. Portanto, não é necessário executar migrations nem seeders.

1. Clonar o repositório
git clone https://github.com/azevaldo/Camboquick.git

Entre na pasta da aplicação Laravel:

cd Camboquick/camboquick_app
2. Instalar as dependências
composer install
3. Configurar o arquivo .env

Crie o arquivo .env:

cp .env.example .env

No Windows, também é possível criar o arquivo .env manualmente a partir do .env.example.

Configure a conexão com o banco de dados:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=camboquick_lua_cheia
DB_USERNAME=root
DB_PASSWORD=

Ajuste DB_USERNAME e DB_PASSWORD de acordo com a configuração do seu ambiente.

4. Criar a chave da aplicação
php artisan key:generate
5. Importar a base de dados

Importe o arquivo de banco de dados disponibilizado no repositório para o MySQL/MariaDB.

Depois, confirme se o nome da base de dados configurado no .env corresponde ao banco importado:

DB_DATABASE=camboquick_lua_cheia

Importante: ao utilizar a base de dados já pronta, não execute:

php artisan migrate

nem:

php artisan db:seed

A estrutura e os dados da aplicação já estão presentes na base de dados disponibilizada.

6. Executar a aplicação
php artisan serve

Acesse:

http://127.0.0.1:8000
Opção 2 — Criar o banco utilizando Laravel

Nesta opção, a base de dados é criada utilizando as migrations e os dados iniciais são inseridos através dos seeders do Laravel.

1. Clonar o repositório
git clone https://github.com/azevaldo/Camboquick.git

Entre na pasta da aplicação:

cd Camboquick/camboquick_app
2. Instalar as dependências
composer install
3. Configurar o .env

Crie o arquivo:

cp .env.example .env

Configure a conexão com o banco:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=camboquick_lua_cheia
DB_USERNAME=root
DB_PASSWORD=

Certifique-se de que o banco de dados camboquick_lua_cheia existe no MySQL/MariaDB.

4. Gerar a chave da aplicação
php artisan key:generate
5. Executar as migrations
php artisan migrate

As migrations irão criar as tabelas necessárias para o funcionamento da aplicação.

6. Executar os seeders
php artisan db:seed

Os seeders irão inserir os dados iniciais definidos pelo projeto.

7. Executar a aplicação
php artisan serve

Acesse:

http://127.0.0.1:8000
🔧 Principais comandos
Iniciar o servidor
php artisan serve
Executar migrations
php artisan migrate

Utilize este comando apenas quando estiver configurando o banco através das migrations.

Executar seeders
php artisan db:seed

Utilize este comando apenas quando estiver configurando o banco através dos seeders.

Limpar os caches
php artisan optimize:clear
Instalar dependências PHP
composer install
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

Contém a lógica principal da aplicação, incluindo controllers, models e outros componentes.

database/

Contém as migrations e seeders utilizados para estruturar e inicializar o banco de dados.

resources/

Contém os recursos da interface da aplicação, incluindo as views.

routes/

Contém as rotas utilizadas pela aplicação.

public/

Contém os arquivos públicos da aplicação.

🔐 Controle de acesso

O sistema possui três funções principais:

Administrador
Gerente
Caixa

Cada função possui diferentes níveis de acesso às funcionalidades da aplicação.

🗃️ Banco de dados

O projeto oferece duas possibilidades para configuração do banco:

Base de dados pronta:
Importe a base disponibilizada no repositório e configure o .env. Não é necessário executar migrations ou seeders.

Banco através do Laravel:
Crie o banco e execute:

php artisan migrate
php artisan db:seed

Nome utilizado na configuração:

DB_DATABASE=camboquick_lua_cheia
🔙 Voltar para a documentação principal

Para conhecer o objetivo do Camboquick, suas funcionalidades, módulos e níveis de acesso:

👉 Voltar para o README principal

Camboquick — Sistema de gestão de vendas e estoque

Desenvolvido por Azevaldo Caluaco.
