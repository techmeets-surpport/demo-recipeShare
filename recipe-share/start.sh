#!/bin/bash

set -e

echo "=== Starting RecipeShare ==="

# 環境変数の確認
echo "DB_CONNECTION: ${DB_CONNECTION:-not set}"
echo "DATABASE_URL is set: $([ -n "$DATABASE_URL" ] && echo 'yes' || echo 'no')"

# DB_CONNECTIONがなければpgsqlをデフォルトに
export DB_CONNECTION=${DB_CONNECTION:-pgsql}

# APP_ENVがなければproductionをデフォルトに
export APP_ENV=${APP_ENV:-production}
export APP_DEBUG=${APP_DEBUG:-false}

# PHPドライバ確認
echo "=== Installed PHP extensions ==="
php -m | grep -i pdo

# マイグレーション実行
echo "=== Running migrations ==="
php artisan migrate --force || echo "Migration failed, continuing..."

# シーダー実行（初回のみ）
echo "=== Running seeders ==="
php artisan db:seed --force || echo "Seeder already run or failed, continuing..."

# キャッシュクリア・再生成
echo "=== Caching config ==="
php artisan config:cache
php artisan route:cache
php artisan view:cache

# ストレージリンク作成
echo "=== Creating storage link ==="
php artisan storage:link || true

echo "=== Starting Apache ==="
# Apache起動
apache2-foreground
