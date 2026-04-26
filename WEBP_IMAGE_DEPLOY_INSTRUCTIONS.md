# WebP image uploads — local & production

The app converts uploaded images to **WebP** using **Intervention Image**. PHP needs **GD** (recommended) or **Imagick** installed and enabled, or you will see errors about a missing driver.

---

## 1. After pulling these changes (every environment)

From the project root:

```bash
composer install --no-dev --optimize-autoloader
```

(Use `composer install` with dev dependencies on local dev machines if you use them.)

```bash
php artisan config:clear
php artisan cache:clear
```

Optionally on production, after your `.env` is correct:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 2. Enable the image driver (required)

### Option A — GD (typical, recommended)

**Windows (XAMPP / similar)**

1. Open `php.ini` (e.g. `C:\xampp\php\php.ini`).
2. Find and **uncomment** (remove the leading `;`):
   - `extension=gd`
   - or `extension=php_gd.dll` (depends on the package).
3. **Restart** Apache (or your web server / PHP in XAMPP Control Panel).
4. Confirm: create a one-line PHP file with `<?php phpinfo();` and check that a **gd** section appears, or run:
   - `path\to\php.exe -m` and look for `gd`.

**Linux (Debian / Ubuntu, example for PHP 8.2)**

```bash
sudo apt update
sudo apt install php8.2-gd
sudo systemctl restart php8.2-fpm
# If you use Apache with mod_php:
sudo systemctl restart apache2
```

Adjust `8.2` to your actual PHP version.

**WebP note:** most modern `php-gd` packages include WebP. If encoding WebP still fails, install the system WebP library (e.g. `libwebp`) using your distribution’s packages and restart PHP.

---

### Option B — Imagick (if you cannot use GD)

```bash
# Debian/Ubuntu example
sudo apt install php8.2-imagick
sudo systemctl restart php8.2-fpm
```

The app will use **GD when available**; if GD is not loaded, it will try **Imagick** automatically.

---

## 3. File permissions (production)

Ensure the web server can write to public asset folders you use (e.g. `public/website/`, `storage/`, and the `storage` link if you use `public/storage`).

```bash
php artisan storage:link
chown -R www-data:www-data storage bootstrap/cache
# use your real user/group for PHP-FPM
```

---

## 4. Quick verification

- Visit any page that **uploads an image** (e.g. create school with logo) and confirm it saves without a driver error.
- In PHP, `extension_loaded('gd')` or `extension_loaded('imagick')` should be true in the **same PHP** that serves the site (FPM, Apache module, or CLI if you only test via CLI — they can differ, so test through the website).

---

## 5. Troubleshooting: “GD/Imagick” error in the browser, but `php -m` shows `gd` in a terminal

The **CLI** and **Apache (or Nginx + PHP-FPM)** can use **different** `php.ini` files or different PHP executables.

1. In the project root, run:  
   `path\to\php.exe -i`  
   Note **Loaded Configuration File** (e.g. `B:\xampp\php\php.ini`).
2. In the browser, open a one-off `phpinfo()` page in your vhost (or a temporary route in Laravel that calls `phpinfo()` only in `local` / debug) and check:
   - **Loaded Configuration File** (must be the one where `extension=gd` is set).
   - A **gd** section must appear.
3. If you use XAMPP, after editing `php.ini`, **restart Apache** from the XAMPP control panel.
4. If you use **multiple PHP** installations (Laragon, another Apache, Docker), the site’s PHP may not be the same as `php` in your PATH.

---

## 6. Emergency fallback (not recommended for production)

If you need uploads to work **before** you can fix the server, add to `.env`:

```env
IMAGE_NO_DRIVER_STORE_ORIGINAL=true
```

Then run:

```bash
php artisan config:clear
```

The app will **store uploaded images in their original format** (JPG, PNG, etc.) instead of WebP. **Turn this off** and enable `ext-gd` (or `ext-imagick`) on the server for normal operation.

---

## 7. Dependency reminder

`intervention/image` is listed in `composer.json`. Deployment must run `composer install` on the server so that package is present.
