#!/usr/bin/env bash
set -euo pipefail

# deploy_binabola.sh
# Usage: ./deploy_binabola.sh /path/to/binabola.zip
# If no argument provided, defaults to $HOME/binabola.zip

ZIP_PATH="${1:-$HOME/binabola.zip}"
TARGET_DIR="/var/www/binabola"
TMP_DIR="/tmp/deploy_binabola_$$"

echo "ZIP_PATH=$ZIP_PATH"
if [ ! -f "$ZIP_PATH" ]; then
  echo "Error: ZIP not found at $ZIP_PATH"
  exit 1
fi

echo "Creating temporary work dir $TMP_DIR"
mkdir -p "$TMP_DIR"

echo "Extracting zip to temp dir..."
unzip -o "$ZIP_PATH" -d "$TMP_DIR"

# Detect webserver user
WEBSERVER_USER=""
if pgrep -x nginx >/dev/null 2>&1; then
  WEBSERVER_USER=$(ps -o user= $(pgrep -o nginx) | head -n1)
elif pgrep -x httpd >/dev/null 2>&1; then
  WEBSERVER_USER=$(ps -o user= $(pgrep -o httpd) | head -n1)
elif pgrep -x apache2 >/dev/null 2>&1; then
  WEBSERVER_USER=$(ps -o user= $(pgrep -o apache2) | head -n1)
else
  WEBSERVER_USER="www-data"
fi
WEBSERVER_USER=${WEBSERVER_USER:-www-data}
echo "Detected webserver user: $WEBSERVER_USER"

echo "Creating target directory $TARGET_DIR (sudo if needed)..."
sudo mkdir -p "$TARGET_DIR"

echo "Copying files to $TARGET_DIR (preserve permissions)..."
# Remove old content then copy (safe)
sudo rm -rf "${TARGET_DIR:?}/"*
sudo cp -a "$TMP_DIR/." "$TARGET_DIR/"

echo "Setting ownership to $WEBSERVER_USER..."
sudo chown -R "$WEBSERVER_USER:$WEBSERVER_USER" "$TARGET_DIR"

echo "Ensure permissions for storage and cache..."
sudo chmod -R 775 "$TARGET_DIR/storage" "$TARGET_DIR/bootstrap/cache" || true
sudo chown -R "$WEBSERVER_USER:$WEBSERVER_USER" "$TARGET_DIR/storage" "$TARGET_DIR/bootstrap/cache" || true

# Run composer install (as current user; composer must be available)
if command -v composer >/dev/null 2>&1; then
  echo "Running composer install..."
  (cd "$TARGET_DIR" && composer install --no-dev --optimize-autoloader --no-interaction)
else
  echo "Composer not found in PATH. Skipping composer install. Please install composer or run manually."
fi

# .env handling
if [ ! -f "$TARGET_DIR/.env" ]; then
  if [ -f "$TARGET_DIR/.env.example" ]; then
    echo "Copying .env.example to .env"
    sudo cp "$TARGET_DIR/.env.example" "$TARGET_DIR/.env"
    sudo chown "$WEBSERVER_USER:$WEBSERVER_USER" "$TARGET_DIR/.env"
    echo "You MUST update .env (APP_URL, DB credentials) before using the app."
  else
    echo "No .env or .env.example found — create your .env manually."
  fi
fi

# Artisan key
if command -v php >/dev/null 2>&1; then
  echo "Generating APP key if missing..."
  (cd "$TARGET_DIR" && sudo -u "$WEBSERVER_USER" php artisan key:generate --force) || true
else
  echo "php not found in PATH; skip artisan commands."
fi

# Cache config and routes
if command -v php >/dev/null 2>&1; then
  echo "Caching config and routes..."
  (cd "$TARGET_DIR" && sudo -u "$WEBSERVER_USER" php artisan config:cache || true)
  (cd "$TARGET_DIR" && sudo -u "$WEBSERVER_USER" php artisan route:cache || true)
  (cd "$TARGET_DIR" && sudo -u "$WEBSERVER_USER" php artisan view:cache || true)
fi

echo "Cleaning up temporary files..."
rm -rf "$TMP_DIR"

echo "Deployment finished. Target: $TARGET_DIR"
echo "Next: verify web server config and visit the site."
