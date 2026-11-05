# LegalFlow — implantação em produção (VPS)

Esta configuração mantém o Nginx já instalado no host e executa PHP-FPM e MySQL em containers separados. O MySQL não publica nenhuma porta no host; o PHP-FPM publica somente em `127.0.0.1:9000`.

## 1. Estrutura esperada na VPS

```text
/var/www/legalflow/
├── backend/
│   └── (código Laravel, incluindo Dockerfile)
├── frontend/
│   └── dist/
└── deploy/
    ├── docker-compose.production.yml
    ├── .env.production
    └── nginx-legalflow.conf
```

Copie o conteúdo do projeto para esses diretórios, preservando a estrutura. O Dockerfile deve ficar em `/var/www/legalflow/backend/Dockerfile`.

## 2. Configurar variáveis de produção

Na VPS, dentro de `/var/www/legalflow/deploy`:

```bash
cp .env.production.example .env.production
chmod 600 .env.production
```

Edite `.env.production` e defina senhas fortes e exclusivas para `DB_PASSWORD` e `MYSQL_ROOT_PASSWORD`. Não reutilize as credenciais locais.

Gere a chave Laravel:

```bash
docker run --rm php:8.4-cli php -r 'echo "base64:".base64_encode(random_bytes(32)).PHP_EOL;'
```

Copie o resultado para `APP_KEY` em `.env.production`. Não compartilhe nem publique esse arquivo.

## 3. Construir e iniciar containers

Execute dentro de `/var/www/legalflow/deploy`:

```bash
docker compose --env-file .env.production -f docker-compose.production.yml config
docker compose --env-file .env.production -f docker-compose.production.yml up -d --build
docker compose --env-file .env.production -f docker-compose.production.yml ps
```

O comando `config` valida a composição e a presença das variáveis antes de iniciar os serviços.

## 4. Preparar Laravel

```bash
docker compose --env-file .env.production -f docker-compose.production.yml exec app php artisan migrate --force
docker compose --env-file .env.production -f docker-compose.production.yml exec app php artisan optimize
```

Não execute `db:seed` em produção sem revisar os seeders e os dados que eles criam.

## 5. Publicar frontend e configurar Nginx

Compile o frontend com `VITE_API_BASE_URL=/api` e publique o conteúdo de `frontend/dist` em `/var/www/legalflow/frontend/dist`.

Copie `nginx-legalflow.conf.example` para `/etc/nginx/sites-available/legalflow`, crie o link em `sites-enabled`, valide com `nginx -t` e só então recarregue o Nginx. Faça backup dos arquivos de configuração antes de alterar o host.

Depois que o domínio responder por HTTP, configure HTTPS com Certbot. Não substitua nem edite os virtual hosts dos outros projetos.

## 6. Verificações

```bash
docker compose --env-file .env.production -f docker-compose.production.yml logs --tail=100 app
docker compose --env-file .env.production -f docker-compose.production.yml logs --tail=100 db
curl -I http://127.0.0.1
```

Valide o acesso ao site, login, operações da API e upload/download de documentos usando somente dados fictícios.

## Observações

- O banco usa volume Docker persistente e não tem porta publicada no host.
- O PHP-FPM fica acessível somente pelo loopback do host.
- O volume `legalflow_storage` persiste os arquivos privados enviados.
- A configuração usa `QUEUE_CONNECTION=sync`; não há worker systemd.
- Faça backup do banco e dos arquivos enviados antes de atualizações.
- Para atualizar, faça backup, atualize os arquivos e execute novamente `docker compose ... up -d --build`.
