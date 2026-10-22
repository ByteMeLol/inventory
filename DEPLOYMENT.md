# 2BROS Docker production deployment

This deployment runs Laravel behind the existing Nginx Proxy Manager (NPM).
NPM terminates HTTPS and forwards traffic to the host port published by the
`web` container. SQLite is stored in the persistent `app_storage` Docker
volume and is not exposed as a network service.

## 1. Prepare DNS and the server

Create an `A` record such as `app.example.com` pointing to the server's public
IP. Open only ports `80` and `443` for NPM.

Install Docker Engine and the Compose plugin on the server, then verify:

```sh
docker --version
docker compose version
```

## 2. Copy the project

```sh
sudo mkdir -p /opt/2bros
sudo chown "$USER":"$USER" /opt/2bros
git clone YOUR_REPOSITORY_URL /opt/2bros
cd /opt/2bros
```

Do not commit `.env`, passwords, `APP_KEY`, or database dumps.

## 3. Create the production environment

```sh
cp .env.example .env
chmod 600 .env
```

Set at least these values in `.env`:

```dotenv
APP_NAME="2BROS"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://app.example.com
APP_KEY=

DB_CONNECTION=sqlite
DB_DATABASE=/var/www/html/storage/app/database.sqlite
APP_PORT=8080

SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true
QUEUE_CONNECTION=database
CACHE_STORE=database
FILESYSTEM_DISK=public
LOG_CHANNEL=stack
LOG_LEVEL=warning
```

Generate secure values locally or on the server:

```sh
openssl rand -base64 32
```

Generate the Laravel key after the containers are built:

```sh
docker compose build app web
docker compose run --rm --no-deps app php artisan key:generate --force
```

## 4. Build and start the stack

```sh
docker compose build --pull app web
docker compose up -d app web queue scheduler
```

Check the services:

```sh
docker compose ps
docker compose logs --tail=100 web app
```

## 5. Initialize Laravel

Run migrations explicitly:

```sh
docker compose exec app php artisan migrate --force
docker compose exec app php artisan storage:link
docker compose exec app php artisan optimize
```

If this is a new installation and seeders exist:

```sh
docker compose exec app php artisan db:seed --force
```

Do not run `migrate:fresh` on production.

## 6. Configure Nginx Proxy Manager

In NPM, create a Proxy Host:

- Domain Names: `app.example.com`
- Scheme: `http`
- Forward Hostname/IP: the Docker host's private IP, for example `127.0.0.1` if NPM can reach the host loopback, otherwise the host LAN IP
- Forward Port: `8080`
- Enable Websockets Support
- Enable Block Common Exploits

Request a Let's Encrypt certificate for the domain and enable:

- Force SSL
- HTTP/2 Support
- HSTS only after HTTPS is confirmed working

If NPM is in a separate Docker network and cannot reach the host-published port,
attach both projects to a shared external Docker network and proxy to `web:80`
instead.

## 7. Verify the application

```sh
curl -I https://app.example.com
docker compose exec app php artisan about
docker compose exec app php artisan migrate:status
docker compose exec app php artisan queue:monitor
```

Log in through HTTPS and verify:

1. Company scope selection
2. Inventory creation
3. Supplier and purchase creation
4. POS sale with cash or bank transfer
5. Finance ledger entry
6. File/avatar upload
7. Invoice PDF download

## 8. Updates

From `/opt/2bros`:

```sh
git pull --ff-only
docker compose build --pull app web
docker compose up -d app web queue scheduler
docker compose exec app php artisan migrate --force
docker compose exec app php artisan optimize
docker compose restart queue scheduler
```

Always take a database backup before migrations.

## 9. Backups

Create a compressed SQLite backup:

```sh
mkdir -p /opt/backups/2bros
docker compose exec -T app sh -c \
  'sqlite3 /var/www/html/storage/app/database.sqlite ".backup /tmp/2bros.sqlite"'
docker compose cp app:/tmp/2bros.sqlite \
  "/opt/backups/2bros/2bros-$(date +%Y%m%d-%H%M%S).sqlite"
```

Copy backups off the server using your secured backup provider. Test a restore
regularly; a backup that has never been restored is not verified.

The SQLite database and uploaded files live in the `app_storage` Docker volume.
Back up that volume or move production uploads to S3-compatible storage before
relying on it for business-critical documents.

## 10. Operational commands

```sh
docker compose ps
docker compose logs -f web
docker compose logs -f queue
docker compose restart web app queue scheduler
docker compose exec app php artisan down
docker compose exec app php artisan up
```

Never run `docker compose down -v` on production; it deletes the named database
and storage volumes.
