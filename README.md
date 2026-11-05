# LegalFlow

Sistema demonstrativo de gestão jurídica desenvolvido como projeto de portfólio. O LegalFlow reúne uma API REST construída com Laravel e uma aplicação web SPA em Vue 3, permitindo demonstrar operações comuns de gestão de clientes, processos, tarefas e documentos.

> **Projeto demonstrativo:** utilize somente dados fictícios. A aplicação não deve armazenar dados pessoais, documentos jurídicos ou informações confidenciais reais. O uso em ambiente real exige revisão de segurança, autorização, privacidade/LGPD, backups e controles adicionais.

## Visão geral

O projeto foi desenvolvido para demonstrar:
- Desenvolvimento de API REST com Laravel e PHP.
- Autenticação por tokens com Laravel Sanctum.
- Interface SPA com Vue 3 e TypeScript.
- Persistência de dados relacionais com MySQL.
- Operações CRUD, validação, paginação e integração entre frontend e backend.
- Testes automatizados com PHPUnit.

## Tecnologias

| Camada | Tecnologias |
|---|---|
| Backend | PHP 8.2+, Laravel 12 |
| Autenticação | Laravel Sanctum |
| Banco de dados | MySQL 8+ |
| Frontend | Vue 3, TypeScript, Vite |
| Estado e navegação | Pinia, Vue Router |
| Comunicação HTTP | Axios |
| Testes | PHPUnit |
| Ambiente local do banco | Docker Compose |

## Funcionalidades

- Cadastro e autenticação de usuários por token.
- Dashboard com indicadores.
- Cadastro, consulta, edição e exclusão de clientes.
- Gestão de processos vinculados a clientes.
- Gestão de tarefas com prioridade, status e prazo.
- Envio, listagem, download e exclusão de documentos.
- Paginação, busca e filtros básicos.
- Validação de dados e rotas protegidas por autenticação.
- Interface responsiva em português.

## Arquitetura

O projeto é dividido em duas aplicações:

- `backend/`: API REST em Laravel. Responsável pela autenticação, regras de negócio, validação e persistência.
- `frontend/`: aplicação SPA em Vue 3. Consome a API por meio de requisições HTTP.

O banco MySQL pode ser iniciado localmente com Docker Compose. O frontend é compilado com Vite para gerar os arquivos estáticos de produção.

## Requisitos para execução local

- PHP 8.2 ou superior, com extensões necessárias pelo Laravel e `pdo_mysql`.
- Composer 2.
- Node.js 20 ou superior e npm.
- Docker com Docker Compose, ou uma instalação MySQL 8+.

## Como executar localmente

Os comandos abaixo partem da pasta raiz do projeto.

### 1. Iniciar o banco de dados

Com Docker Compose:

```bash
docker compose up -d db
```

A configuração local de exemplo utiliza:
- Banco: `legalflow`
- Usuário: `legalflow`
- Senha: `legalflow_local_only`

Essas credenciais são exclusivas do ambiente local. **Nunca as reutilize em produção.**

Se preferir usar uma instalação própria do MySQL, crie o banco e o usuário e ajuste as variáveis `DB_*` no arquivo `backend/.env`.

### 2. Configurar e iniciar o backend

No Windows PowerShell:

```powershell
cd backend
Copy-Item .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

No Linux/macOS, substitua o comando de cópia por:

```bash
cp .env.example .env
```

A API ficará disponível em:

`http://127.0.0.1:8000/api`

O seeder local cria uma conta demonstrativa:

- E-mail: `demo@legalflow.local`
- Senha: `Demo@12345`

Essa conta e senha são destinadas exclusivamente ao ambiente local. Não publique credenciais de produção nem reutilize essa senha em serviços externos.

### 3. Configurar e iniciar o frontend

Abra outro terminal na pasta raiz do projeto.

No Windows PowerShell:

```powershell
cd frontend
Copy-Item .env.example .env
npm install
npm run dev
```

No Linux/macOS, use `cp .env.example .env` para copiar o arquivo.

A aplicação ficará disponível em:

`http://localhost:5173`

### 4. Executar os testes automatizados

Na pasta `backend`:

Windows PowerShell:

```powershell
.\vendor\bin\phpunit
```

Linux/macOS:

```bash
./vendor/bin/phpunit
```

Os testes utilizam SQLite em memória conforme a configuração do PHPUnit. A extensão `pdo_sqlite` deve estar habilitada no PHP.

### 5. Gerar o build do frontend

Na pasta `frontend`:

```bash
npm run build
```

Os arquivos de produção serão gerados em `frontend/dist`.

## Principais endpoints da API

| Método | Endpoint | Descrição |
|---|---|---|
| POST | `/api/auth/register` | Registro de usuário |
| POST | `/api/auth/login` | Autenticação |
| POST | `/api/auth/logout` | Revogação do token atual |
| GET | `/api/auth/me` | Dados do usuário autenticado |
| GET | `/api/dashboard` | Indicadores do dashboard |
| GET/POST | `/api/clients` | Listagem e criação de clientes |
| GET/PUT/DELETE | `/api/clients/{id}` | Consulta, atualização e exclusão de cliente |
| GET/POST | `/api/cases` | Listagem e criação de processos |
| GET/PUT/DELETE | `/api/cases/{id}` | Consulta, atualização e exclusão de processo |
| GET/POST | `/api/tasks` | Listagem e criação de tarefas |
| GET/PUT/DELETE | `/api/tasks/{id}` | Consulta, atualização e exclusão de tarefa |
| GET/POST | `/api/documents` | Listagem e envio de documentos |
| GET | `/api/documents/{id}/download` | Download de documento |
| DELETE | `/api/documents/{id}` | Exclusão de documento |

As rotas de registro e login são públicas. As demais rotas listadas exigem autenticação por token Bearer.

## Configurações e segurança

- Os arquivos `.env` contêm configurações locais e não devem ser versionados.
- Gere uma `APP_KEY` própria para cada ambiente.
- Utilize credenciais fortes e exclusivas em produção.
- Mantenha `APP_DEBUG=false` em produção.
- Não utilize a conta demonstrativa local como conta de produção.
- Armazene arquivos enviados em local privado e configure limites de upload.
- Configure HTTPS, backups e monitoramento de logs.
- Não utilize dados reais de clientes ou processos neste projeto demonstrativo.

## Deploy

O LegalFlow está sendo preparado para publicação em uma VPS, utilizando:

- Nginx como servidor web e proxy reverso.
- Frontend Vue 3 servido como arquivos estáticos.
- Backend Laravel executado em container Docker com PHP-FPM.
- MySQL 8.4 em container Docker, com rede interna.
- HTTPS com certificado SSL.

URL planejada:

https://legalflow.lucianoferreiradev.com

O procedimento de implantação está documentado em [`DEPLOY.md`](./DEPLOY.md). A publicação ainda está em preparação e será validada antes de ser considerada concluída.

Desenvolvido como projeto de portfólio.
