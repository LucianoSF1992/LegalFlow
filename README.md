# LegalFlow

Sistema demonstrativo de gestão jurídica desenvolvido como projeto de
portfólio. O LegalFlow reúne uma API REST construída com Laravel e uma
aplicação web SPA em Vue 3, demonstrando operações comuns de gestão de
clientes, processos, tarefas e documentos.

**Demonstração online:** https://legalflow.lucianoferreiradev.com\
**Repositório:** https://github.com/LucianoSF1992/LegalFlow

> **Projeto demonstrativo:** utilize somente dados fictícios. A
> aplicação não deve armazenar dados pessoais, documentos jurídicos ou
> informações confidenciais reais. O uso em ambiente real exige revisão
> e implementação de controles adicionais de segurança, autorização,
> privacidade/LGPD e backup.

## Visão geral

O projeto demonstra: - Desenvolvimento de API REST com Laravel e PHP. -
Autenticação por tokens com Laravel Sanctum. - Interface SPA com Vue 3 e
TypeScript. - Persistência de dados relacionais com MySQL. - Operações
CRUD, validação, paginação e integração entre frontend e backend. -
Testes automatizados com PHPUnit. - Containerização e publicação de uma
aplicação full stack em VPS Linux.

## Tecnologias

  Camada               Tecnologias
  -------------------- -------------------------------
  Backend              PHP 8.4, Laravel 12
  Autenticação         Laravel Sanctum
  Banco de dados       MySQL 8.4
  Frontend             Vue 3, TypeScript, Vite
  Estado e navegação   Pinia, Vue Router
  Comunicação HTTP     Axios
  Testes               PHPUnit
  Containers           Docker, Docker Compose
  Infraestrutura       Ubuntu Server, Nginx, PHP-FPM
  HTTPS                Let's Encrypt / Certbot

## Funcionalidades

-   Cadastro de usuários e autenticação por token.
-   Login e encerramento de sessão.
-   Dashboard com indicadores.
-   Cadastro, consulta, edição e exclusão de clientes.
-   Gestão de processos vinculados a clientes.
-   Gestão de tarefas com prioridade, status e prazo.
-   Envio, listagem, download e exclusão de documentos.
-   Armazenamento privado de documentos.
-   Paginação, busca e filtros básicos.
-   Validação de dados e rotas protegidas por autenticação.
-   Interface responsiva em português.

## Demonstração online

Acesse: **https://legalflow.lucianoferreiradev.com**

É possível criar uma conta diretamente pela aplicação e explorar as
funcionalidades. Utilize somente informações fictícias; não envie
documentos reais nem dados pessoais sensíveis.

## Arquitetura

O projeto é dividido em duas aplicações:

-   `backend/`: API REST em Laravel, responsável pela autenticação,
    regras de negócio, validação e persistência.
-   `frontend/`: aplicação SPA em Vue 3, responsável pela interface e
    comunicação HTTP com a API.

### Ambiente de produção

A aplicação está publicada em uma VPS Ubuntu, com a seguinte
arquitetura:

-   Nginx no host serve os arquivos estáticos do frontend e encaminha as
    requisições da API.
-   Backend Laravel executado em um container Docker com PHP-FPM.
-   MySQL 8.4 executado em container Docker.
-   Rede interna Docker para comunicação entre backend e banco de dados.
-   Volume Docker persistente para os dados do MySQL.
-   Volume Docker persistente para os documentos enviados.
-   PHP-FPM publicado somente em `127.0.0.1:9000`, sem exposição pública
    direta.
-   HTTPS configurado com certificado Let's Encrypt.

O ambiente publicado utiliza um virtual host Nginx dedicado ao domínio
do LegalFlow, sem substituir as configurações dos outros projetos
hospedados na VPS.

## Requisitos para execução local

-   PHP 8.2 ou superior, com extensões necessárias pelo Laravel e
    `pdo_mysql`.
-   Composer 2.
-   Node.js 20 ou superior e npm.
-   Docker com Docker Compose, ou uma instalação MySQL 8+.
-   Extensão `pdo_sqlite` habilitada para executar os testes
    automatizados.

## Como executar localmente

Os comandos abaixo partem da pasta raiz do projeto.

### 1. Iniciar o banco de dados

Com Docker Compose:

``` bash
docker compose up -d db
```

A configuração local de exemplo utiliza: - Banco: `legalflow` - Usuário:
`legalflow` - Senha: `legalflow_local_only`

Essas credenciais são exclusivas do ambiente local. **Nunca as reutilize
em produção.**

Se preferir usar uma instalação própria do MySQL, crie o banco e o
usuário e ajuste as variáveis `DB_*` no arquivo `backend/.env`.

### 2. Configurar e iniciar o backend

No Windows PowerShell:

``` powershell
cd backend
Copy-Item .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

No Linux/macOS:

``` bash
cd backend
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

A API ficará disponível em `http://127.0.0.1:8000/api`.

O seeder cria uma conta demonstrativa somente no ambiente `local`: -
E-mail: `demo@legalflow.local` - Senha: `Demo@12345`

Essa conta e senha são destinadas exclusivamente ao ambiente local. Não
utilize essas credenciais em produção nem em serviços externos.

### 3. Configurar e iniciar o frontend

Abra outro terminal na pasta raiz do projeto.

No Windows PowerShell:

``` powershell
cd frontend
Copy-Item .env.example .env
npm install
npm run dev
```

No Linux/macOS:

``` bash
cd frontend
cp .env.example .env
npm install
npm run dev
```

A aplicação ficará disponível em `http://localhost:5173`.

### 4. Executar os testes automatizados

Na pasta `backend`:

Windows PowerShell:

``` powershell
.\vendor\bin\phpunit
```

Linux/macOS:

``` bash
./vendor/bin/phpunit
```

Os testes utilizam SQLite em memória conforme a configuração do PHPUnit.
A extensão `pdo_sqlite` deve estar habilitada no PHP.

### 5. Gerar o build do frontend

Na pasta `frontend`:

``` bash
npm run build
```

Os arquivos de produção serão gerados em `frontend/dist`.

## Principais endpoints da API

  --------------------------------------------------------------------------------
  Método                  Endpoint                         Descrição
  ----------------------- -------------------------------- -----------------------
  POST                    `/api/auth/register`             Registro de usuário

  POST                    `/api/auth/login`                Autenticação

  POST                    `/api/auth/logout`               Revogação do token
                                                           atual

  GET                     `/api/auth/me`                   Dados do usuário
                                                           autenticado

  GET                     `/api/dashboard`                 Indicadores do
                                                           dashboard

  GET/POST                `/api/clients`                   Listagem e criação de
                                                           clientes

  GET/PUT/DELETE          `/api/clients/{id}`              Consulta, atualização e
                                                           exclusão de cliente

  GET/POST                `/api/cases`                     Listagem e criação de
                                                           processos

  GET/PUT/DELETE          `/api/cases/{id}`                Consulta, atualização e
                                                           exclusão de processo

  GET/POST                `/api/tasks`                     Listagem e criação de
                                                           tarefas

  GET/PUT/DELETE          `/api/tasks/{id}`                Consulta, atualização e
                                                           exclusão de tarefa

  GET/POST                `/api/documents`                 Listagem e envio de
                                                           documentos

  GET                     `/api/documents/{id}/download`   Download de documento

  DELETE                  `/api/documents/{id}`            Exclusão de documento
  --------------------------------------------------------------------------------

As rotas de registro e login são públicas. As demais rotas de negócio
exigem autenticação por token Bearer.

## Estrutura do projeto

``` text
LegalFlow/
├── backend/                    # API Laravel
│   ├── app/
│   ├── database/
│   ├── routes/
│   ├── tests/
│   └── Dockerfile
├── frontend/                   # Aplicação Vue 3
│   ├── src/
│   ├── public/
│   └── package.json
├── deploy/                     # Arquivos de deploy
│   ├── docker-compose.production.yml
│   ├── .env.production.example
│   ├── nginx-legalflow.conf.example
│   └── DEPLOY-PRODUCTION.md
├── docker-compose.yml           # Ambiente local
├── DEPLOY.md
├── README.md
└── README.en.md
```

## Configurações e segurança

-   Arquivos `.env` contêm configurações específicas de cada ambiente e
    não devem ser versionados.
-   Gere uma `APP_KEY` própria para cada ambiente.
-   Utilize credenciais fortes e exclusivas em produção.
-   Mantenha `APP_DEBUG=false` em produção.
-   Não execute o seeder de demonstração em produção.
-   Armazene arquivos enviados em local privado e configure limites de
    upload.
-   Configure e verifique backups regularmente.
-   Utilize HTTPS e monitore os logs da aplicação.
-   Revise autorização, cadastro público e proteção contra abuso antes
    de disponibilizar o sistema para uso além de demonstração.
-   Não utilize dados reais de clientes ou processos jurídicos.

## Deploy

O procedimento de implantação está documentado em: -
[`DEPLOY.md`](./DEPLOY.md) -
[`deploy/DEPLOY-PRODUCTION.md`](./deploy/DEPLOY-PRODUCTION.md)

Os arquivos de exemplo de Nginx e Docker Compose ficam na pasta
`deploy/`. O arquivo `deploy/.env.production.example` é apenas um
modelo: credenciais reais e a chave da aplicação devem permanecer em
arquivo de ambiente não versionado.

------------------------------------------------------------------------

Desenvolvido como projeto de portfólio para demonstrar conhecimentos em
desenvolvimento Full Stack, APIs REST, autenticação, persistência de
dados, Docker e deploy em servidor Linux.
