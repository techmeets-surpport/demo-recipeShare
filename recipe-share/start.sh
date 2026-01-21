#!/bin/bash

# マイグレーション実行
php artisan migrate --force

# キャッシュクリア・再生成
php artisan config:cache
php artisan route:cache
php artisan view:cache

# ストレージリンク作成
php artisan storage:link || true

# Apache起動
apache2-foreground
