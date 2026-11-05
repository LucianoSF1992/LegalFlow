# Deploy — LegalFlow

Este guia descreve a implantação planejada para `legalflow.lucianoferreiradev.com` usando o Nginx já instalado no VPS e containers Docker independentes para PHP-FPM e MySQL. O procedimento deve ser executado por etapas, após conferir os serviços existentes.

## Arquitetura

- Nginx do host serve `frontend/dist` e encaminha `/api/` para PHP-FPM em `127.0.0.1:9000`.
- Laravel roda em um container PHP 8.4-FPM.
- MySQL 8.4 roda em container na rede privada do Compose, sem porta publicada no host.
- O banco e os documentos privados usam volumes Docker persistentes.
- O frontend usa `VITE_API_BASE_URL=/api`, mantendo frontend e API no mesmo domínio.

## 1. DNS

No painel DNS, crie um registro:

- Tipo: `A`
- Nome/Host: `legalflow`
- Valor: IPv4 público do VPS
- TTL: padrão

Não altere os registros do domínio raiz nem do `www`. Valide:

```bash
getent ahostsv4 legalflow.lucianoferreiradev.com
```

O endereço retornado deve corresponder ao IP público do VPS.

## 2. Estrutura esperada no VPS

```text
/var/www/legalflow/
├── backend/
│   ├── Dockerfile
│   └── (código Laravel)
├── frontend/
│   └── dist/
└── deploy/
    ├── docker-compose.production.yml
    ├── .env.production
    └── nginx-legalflow.conf
```

O arquivo `backend/.dockerignore` deve acompanhar o Dockerfile. Não copie arquivos `.env` locais para o servidor.

## 3. Configurar ambiente de produção

Na VPS, crie `/var/www/legalflow/deploy/.env.production` a partir de `deploy/.env.production.example`.

Preencha valores exclusivos e fortes para:

- `DB_PASSWORD`
- `MYSQL_ROOT_PASSWORD`
- `APP_KEY`

Gere uma chave Laravel sem reutilizar a chave local:

```bash
docker run --rm php:8.4-cli php -r 'echo "base64:".base64_encode(random_bytes(32)).PHP_EOL;'
```

Copie o resultado para `APP_KEY`. Proteja o arquivo:

```bash
chmod 600 /var/www/legalflow/deploy/.env.production
```

Nunca publique ou compartilhe esse arquivo.

## 4. Construir e iniciar os containers

Entre em `/var/www/legalflow/deploy` e valide a configuração antes de iniciar:

```bash
docker compose --env-file .env.production -f docker-compose.production.yml config
```

Se a validação estiver correta:

```bash
docker compose --env-file .env.production -f docker-compose.production.yml up -d --build
docker compose --env-file .env.production -f docker-compose.production.yml ps
```

O serviço PHP-FPM publica a porta `9000` somente em `127.0.0.1`. O MySQL não publica porta no host.

## 5. Preparar Laravel

Execute:

```bash
docker compose --env-file .env.production -f docker-compose.production.yml exec app php artisan migrate --force
docker compose --env-file .env.production -f docker-compose.production.yml exec app php artisan optimize
```

Não execute `db:seed` em produção. O seeder do usuário de demonstração foi limitado ao ambiente `local`.

A configuração usa `SESSION_DRIVER=file`, `CACHE_STORE=file` e `QUEUE_CONNECTION=sync`; portanto, não requer worker systemd neste momento.

## 6. Publicar frontend

No ambiente de build, configure `frontend/.env.production` com:

```dotenv
VITE_API_BASE_URL=/api
```

Compile:

```bash
cd /var/www/legalflow/frontend
npm ci
npm run build
```

Publique o conteúdo de `frontend/dist` no caminho `/var/www/legalflow/frontend/dist` do VPS. O Nginx do host precisa ter permissão de leitura desses arquivos.

## 7. Configurar Nginx

Copie `deploy/nginx-legalflow.conf.example` para:

`/etc/nginx/sites-available/legalflow`

O arquivo encaminha `/api/` para `127.0.0.1:9000` e serve o frontend estático. Habilite o novo site por symlink em `sites-enabled`, sem substituir ou editar os virtual hosts dos outros projetos.

Antes de recarregar:

```bash
sudo nginx -t
```

Somente se o teste passar:

```bash
sudo systemctl reload nginx
```

Faça backup das configurações existentes antes de qualquer alteração.

## 8. HTTPS

Depois que o domínio responder por HTTP e a porta 80 estiver acessível, configure o certificado:

```bash
sudo certbot --nginx -d legalflow.lucianoferreiradev.com
```

Valide a renovação:

```bash
sudo certbot renew --dry-run
```

## 9. Verificações

Consulte o estado e os logs:

```bash
docker compose --env-file .env.production -f docker-compose.production.yml ps
docker compose --env-file .env.production -f docker-compose.production.yml logs --tail=100 app
docker compose --env-file .env.production -f docker-compose.production.yml logs --tail=100 db
```

Valide no navegador:

- abertura do frontend;
- login e logout;
- persistência da sessão após atualizar a página;
- operações de clientes, processos e tarefas;
- upload, download e exclusão de documentos.

Use exclusivamente dados fictícios na demonstração.

## Rollback e manutenção

Antes de atualizações, faça backup do banco e dos documentos privados. Preserve as configurações Nginx existentes. Para atualizar a aplicação, publique a versão revisada e execute novamente o `docker compose ... up -d --build` após validar a configuração.

Não remova volumes Docker nem reverta migrations destrutivas sem um plano de recuperação.

## Observação

Este documento descreve o procedimento planejado. A implantação real só deve começar após uma nova inspeção somente leitura do VPS e a validação de cada etapa. Não instale PHP-FPM ou MySQL diretamente no host para esta arquitetura.
