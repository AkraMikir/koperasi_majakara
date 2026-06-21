# 🚀 Panduan Deploy Koperasi Majakara ke VPS Hostinger

## Prasyarat VPS

- VPS Hostinger minimal **2 GB RAM** (disarankan 4 GB)
- OS: Ubuntu 22.04 LTS
- Domain sudah diarahkan ke IP VPS (A record)

---

## Langkah 1 — Install Docker di VPS

Hubungkan ke VPS via SSH:
```bash
ssh root@IP_VPS_ANDA
```

Install Docker Engine:
```bash
curl -fsSL https://get.docker.com | sh
systemctl enable docker
systemctl start docker
docker --version
```

Install Docker Compose plugin:
```bash
apt-get install -y docker-compose-plugin
docker compose version
```

---

## Langkah 2 — Upload Projek ke VPS

### Opsi A — Via Git (disarankan)
Di VPS, clone repository:
```bash
cd /var/www
git clone https://github.com/USERNAME/koperasi_majakara.git
cd koperasi_majakara
```

### Opsi B — Via SCP (upload dari Windows)
Di komputer lokal (PowerShell):
```powershell
scp -r d:\project\koperasi_majakara root@IP_VPS:/var/www/koperasi_majakara
```

---

## Langkah 3 — Setup File .env Production

Di VPS dalam folder projek:
```bash
cp .env.production.example .env
nano .env
```

**Isi semua nilai yang ditandai komentar:**
- `APP_KEY` — generate dulu: `php artisan key:generate --show` (dari lokal)
- `APP_URL` — `https://majakara.cloud`
- `DB_PASSWORD` — password MySQL yang kuat
- `DB_ROOT_PASSWORD` — password root MySQL yang kuat
- `REDIS_PASSWORD` — password Redis yang kuat
- `SESSION_DOMAIN` — `.majakara.cloud`
- `FONNTE_API_KEY` — API key Fonnte Anda

---

## Langkah 4 — Konfigurasi Domain di Nginx

Edit file Nginx config:
```bash
nano docker/nginx/default.conf
```

Ganti `majakara.cloud` dengan domain Anda di baris:
```nginx
server_name majakara.cloud www.majakara.cloud;
```

---

## Langkah 5 — Build & Jalankan Container

```bash
# Build image (pertama kali, bisa makan waktu 5-15 menit)
docker compose build --no-cache

# Jalankan semua service
docker compose up -d

# Cek status
docker compose ps

# Lihat logs
docker compose logs -f app
```

Jika berhasil, output `docker compose ps` akan menampilkan:
```
NAME                 STATUS
koperasi_nginx       Up
koperasi_app         Up
koperasi_mysql       Up (healthy)
koperasi_redis       Up (healthy)
koperasi_queue       Up
```

---

## Langkah 6 — Setup Let's Encrypt SSL

> **Pastikan domain sudah pointing ke IP VPS terlebih dahulu!**

**6a. Jalankan Certbot untuk generate sertifikat:**
```bash
docker compose --profile ssl run --rm certbot certonly \
  --webroot \
  --webroot-path=/var/www/certbot \
  --email admin@majakara.cloud \
  --agree-tos \
  --no-eff-email \
  -d majakara.cloud \
  -d www.majakara.cloud
```

**6b. Aktifkan HTTPS di Nginx config:**

Edit `docker/nginx/default.conf`:
1. Comment out blok HTTP `location /` yang forward ke PHP
2. Uncomment baris `return 301 https://...`
3. Uncomment seluruh blok `server { listen 443 ssl ... }`
4. Ganti semua `majakara.cloud` dengan domain Anda

**6c. Reload Nginx:**
```bash
docker compose exec nginx nginx -s reload
```

**6d. Auto-renew SSL (cron job):**
```bash
crontab -e
```
Tambahkan:
```cron
0 3 * * 1 cd /var/www/koperasi_majakara && docker compose --profile ssl run --rm certbot renew && docker compose exec nginx nginx -s reload
```

---

## Langkah 7 — Verifikasi

```bash
# Cek database migration
docker compose exec app php artisan migrate:status

# Cek artisan
docker compose exec app php artisan --version

# Test Redis connection
docker compose exec app php artisan tinker --execute="Cache::put('test', 'ok', 10); echo Cache::get('test');"

# Cek queue worker
docker compose exec queue php artisan queue:monitor

# Cek Tesseract OCR
docker compose exec app tesseract --version
```

Buka browser: `https://majakara.cloud` — landing page harus muncul ✅

---

## Perintah Berguna Sehari-hari

```bash
# Masuk ke container app
docker compose exec app bash

# Jalankan artisan command
docker compose exec app php artisan migrate
docker compose exec app php artisan cache:clear
docker compose exec app php artisan queue:restart

# Lihat logs
docker compose logs -f app
docker compose logs -f nginx
docker compose logs -f queue

# Restart service tertentu
docker compose restart app
docker compose restart nginx

# Update aplikasi (setelah git pull)
git pull
docker compose build app queue
docker compose up -d app queue
docker compose exec app php artisan migrate --force
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache

# Backup database
docker compose exec mysql mysqldump -u root -p${DB_ROOT_PASSWORD} koperasi_majakara > backup_$(date +%Y%m%d).sql

# Stop semua
docker compose down

# Stop dan hapus semua data (HATI-HATI!)
docker compose down -v
```

---

## Troubleshooting

### Container app tidak start
```bash
docker compose logs app
# Cek error di logs
```

### Database connection refused
```bash
# Tunggu MySQL healthy dulu
docker compose ps mysql
# Jika status tidak healthy, cek:
docker compose logs mysql
```

### OCR tidak berjalan
```bash
docker compose exec app tesseract --list-langs
# Harus ada 'ind' dalam list
```

### Permission error storage
```bash
docker compose exec app chown -R www-data:www-data storage bootstrap/cache
docker compose exec app chmod -R 775 storage bootstrap/cache
```

### Queue tidak memproses job
```bash
docker compose exec app php artisan queue:work --once
docker compose logs queue
```
