# レシピ共有プラットフォーム - 要件定義書

## プロジェクト概要

### プロジェクト名
**RecipeShare（レシピシェア）**

### プロジェクトの目的
techmeets新カリキュラムで学んだ技術を統合した、実践的なレシピ共有Webアプリケーションを開発する。
完全無料で動作するデモアプリケーションとして構築。

### 想定利用シーン
- 料理好きなユーザーが自分のレシピを投稿・共有
- 他のユーザーのレシピを検索・お気に入り保存
- レシピにレビュー・評価を投稿

### 制約条件
- 完全無料で実装（AWS等の有料サービスは使用しない）
- デモレベル（同時アクセス最大3人程度）
- ローカル開発環境で動作
- 無料ホスティングサービスでデプロイ可能

---

## ターゲットユーザー

### メインユーザー
- 年齢：20-50代
- 趣味：料理、食べ歩き
- ITリテラシー：中程度（SNS利用経験あり）

### ユーザーストーリー
1. **投稿者として**：自分のレシピを写真付きで投稿し、他の人と共有したい
2. **閲覧者として**：夕食の献立に困った時、簡単なレシピを検索したい
3. **料理好きとして**：気に入ったレシピをお気に入りに保存し、後で見返したい

---

## 機能要件

### 1. ユーザー認証機能

#### 1.1 ユーザー登録
- メールアドレス
- パスワード
- ユーザー名
- プロフィール画像（任意）

#### 1.2 ログイン/ログアウト
- メールアドレス + パスワードでログイン
- ログアウト機能

#### 1.3 プロフィール編集
- ユーザー名変更
- 自己紹介編集
- プロフィール画像変更

**認証方式：** Laravel Breeze

---

### 2. レシピ管理機能（CRUD）

#### 2.1 レシピ投稿（Create）

**入力項目：**

| 項目 | 入力タイプ | 必須/任意 | バリデーション |
|------|-----------|----------|--------------|
| レシピ名 | テキスト | 必須 | 最大100文字 |
| 説明 | テキストエリア | 必須 | 最大500文字 |
| 調理時間 | 数値 | 必須 | 1-999分 |
| 人数 | 数値 | 必須 | 1-10人 |
| 難易度 | セレクト | 必須 | 簡単/普通/難しい |
| カテゴリー | セレクト | 必須 | 和食/洋食/中華/その他 |
| タグ | チェックボックス（複数選択） | 任意 | 複数選択可 |
| メイン画像 | ファイル | 必須 | JPEG/PNG, 最大2MB |
| サブ画像 | ファイル（複数） | 任意 | 最大3枚、各2MB |
| 材料 | 動的追加フィールド | 必須 | 材料名 + 分量 |
| 作り方 | 動的追加フィールド | 必須 | ステップごとに記載 |
| 公開/非公開 | チェックボックス | 必須 | デフォルト：公開 |

**材料の入力例：**
```
材料1: 豚バラ肉 | 200g
材料2: キャベツ | 1/4個
材料3: 塩こしょう | 少々
```

**作り方の入力例：**
```
ステップ1: キャベツを一口大に切る
ステップ2: フライパンで豚肉を炒める
ステップ3: キャベツを加えて炒める
ステップ4: 塩こしょうで味を整える
```

#### 2.2 レシピ一覧（Read）

**表示項目：**
- メイン画像（サムネイル）
- レシピ名
- 投稿者名
- 調理時間
- 難易度
- 評価（星の平均）
- お気に入り数

**ソート機能：**
- 新着順（デフォルト）
- 人気順（お気に入り数）
- 評価順（高評価順）

**フィルタリング：**
- カテゴリー
- タグ
- 調理時間（15分以内、30分以内、60分以内、60分以上）
- 難易度

**ページネーション：**
- 1ページあたり12件

#### 2.3 レシピ詳細（Read）

**表示項目：**
- メイン画像
- サブ画像（ある場合）
- レシピ名
- 投稿者情報（名前、プロフィール画像）
- 説明
- 調理時間、人数、難易度
- カテゴリー、タグ
- 材料リスト
- 作り方（ステップごと）
- 投稿日時、更新日時
- 評価（星の平均 + レビュー数）
- レビュー一覧
- お気に入りボタン

**アクセス制御：**
- 非公開レシピは投稿者のみ閲覧可能

#### 2.4 レシピ編集（Update）
- 投稿者本人のみ編集可能
- 投稿時と同じフォーム
- 画像の差し替え可能

#### 2.5 レシピ削除（Delete）
- 投稿者本人のみ削除可能
- 確認ダイアログ表示
- 論理削除（deleted_at）

---

### 3. 検索機能

**検索タイプ：**

| 検索タイプ | 検索対象 | 実装方法 |
|-----------|---------|---------|
| キーワード検索 | レシピ名、説明、材料名 | LIKE検索 |
| カテゴリー検索 | カテゴリー | WHERE句 |
| タグ検索 | タグ | リレーション検索 |
| 複合検索 | 上記の組み合わせ | AND検索 |

---

### 4. お気に入り機能

| 機能 | 詳細 |
|------|------|
| お気に入り追加 | レシピをお気に入りに追加（ログイン必須） |
| お気に入り解除 | お気に入りから削除 |
| お気に入り一覧 | 自分のお気に入りレシピ一覧表示（マイページ） |

**実装方法：**
- Ajax（Alpine.js）で非同期追加/削除
- お気に入りボタンのトグル表示

---

### 5. レビュー・評価機能

**レビュー投稿：**
- 星評価（1-5）必須
- コメント（任意、最大300文字）

**レビュー管理：**
- レビュー編集（自分のレビューのみ）
- レビュー削除（自分のレビューのみ）
- レビュー一覧（レシピ詳細ページに表示、新着順）

**制約：**
- 1ユーザー1レシピにつき1レビュー
- ログイン必須

---

### 6. マイページ機能

**表示内容：**
- プロフィール情報（ユーザー名、自己紹介、プロフィール画像）
- 統計情報
  - 投稿レシピ数
  - お気に入りされた合計数
  - 平均評価
- 投稿レシピ一覧（タブ表示）
  - 公開レシピ
  - 非公開レシピ
- お気に入りレシピ一覧

---

## データベース設計

### ER図

```
users（ユーザー）
├─ recipes（レシピ）
   ├─ recipe_images（レシピ画像）
   ├─ ingredients（材料）
   ├─ steps（作り方）
   ├─ recipe_tags（中間テーブル）
   └─ reviews（レビュー）
tags（タグ）
favorites（お気に入り）
categories（カテゴリー）
```

---

### テーブル定義

#### users（ユーザー）

```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    profile_image VARCHAR(255) NULL COMMENT 'storage/profiles/xxx.jpg',
    bio TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

---

#### categories（カテゴリー）

```sql
CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);
```

**初期データ（Seeder）：**
- 和食
- 洋食
- 中華
- イタリアン
- その他

---

#### tags（タグ）

```sql
CREATE TABLE tags (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);
```

**初期データ（Seeder）：**
- 肉料理
- 魚料理
- 野菜料理
- デザート
- 時短
- ヘルシー
- がっつり
- おつまみ

---

#### recipes（レシピ）

```sql
CREATE TABLE recipes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    category_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    cooking_time INT NOT NULL COMMENT '調理時間（分）',
    servings INT NOT NULL COMMENT '人数',
    difficulty ENUM('easy', 'medium', 'hard') NOT NULL,
    main_image VARCHAR(255) NOT NULL COMMENT 'storage/recipes/xxx.jpg',
    is_public BOOLEAN NOT NULL DEFAULT TRUE,
    views_count INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id),
    INDEX idx_user_id (user_id),
    INDEX idx_category_id (category_id),
    INDEX idx_is_public (is_public),
    INDEX idx_created_at (created_at)
);
```

---

#### recipe_images（レシピサブ画像）

```sql
CREATE TABLE recipe_images (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    recipe_id BIGINT UNSIGNED NOT NULL,
    image_path VARCHAR(255) NOT NULL COMMENT 'storage/recipes/xxx.jpg',
    display_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE,
    INDEX idx_recipe_id (recipe_id)
);
```

---

#### ingredients（材料）

```sql
CREATE TABLE ingredients (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    recipe_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(100) NOT NULL,
    quantity VARCHAR(50) NOT NULL,
    display_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE,
    INDEX idx_recipe_id (recipe_id)
);
```

---

#### steps（作り方）

```sql
CREATE TABLE steps (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    recipe_id BIGINT UNSIGNED NOT NULL,
    step_number INT NOT NULL,
    description TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE,
    INDEX idx_recipe_id (recipe_id)
);
```

---

#### recipe_tags（レシピ・タグ中間テーブル）

```sql
CREATE TABLE recipe_tags (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    recipe_id BIGINT UNSIGNED NOT NULL,
    tag_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE,
    UNIQUE KEY unique_recipe_tag (recipe_id, tag_id),
    INDEX idx_recipe_id (recipe_id),
    INDEX idx_tag_id (tag_id)
);
```

---

#### favorites（お気に入り）

```sql
CREATE TABLE favorites (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    recipe_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_recipe (user_id, recipe_id),
    INDEX idx_user_id (user_id),
    INDEX idx_recipe_id (recipe_id)
);
```

---

#### reviews（レビュー）

```sql
CREATE TABLE reviews (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    recipe_id BIGINT UNSIGNED NOT NULL,
    rating TINYINT NOT NULL COMMENT '1-5',
    comment TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_recipe (user_id, recipe_id),
    INDEX idx_recipe_id (recipe_id)
);
```

---

## 技術スタック

### バックエンド
- **PHP**: 8.2以上
- **Laravel**: 11.x
- **MySQL**: 8.0
- **認証**: Laravel Breeze（Blade + Alpine.js）

### フロントエンド
- **Blade**: テンプレートエンジン
- **Tailwind CSS**: 3.x（CSSフレームワーク）
- **Alpine.js**: 3.x（軽量JSフレームワーク）

### 開発環境
- **Docker**: ローカル開発環境
- **Docker Compose**: コンテナオーケストレーション

### ファイルストレージ
- **ローカルストレージ**: `storage/app/public`（シンボリックリンク）
- **保存先**: 
  - プロフィール画像: `storage/app/public/profiles/`
  - レシピ画像: `storage/app/public/recipes/`

### デプロイ（オプション）
- **Render**: 無料Webサービスホスティング
- **Railway**: 無料枠（$5/月クレジット）
- **fly.io**: 無料枠（3VM）

---

## 画面設計

### 画面一覧

#### 公開ページ（未ログインでもアクセス可能）

| 画面名 | URL | 主な機能 |
|--------|-----|---------|
| トップページ | `/` | 新着レシピ6件、人気レシピ6件、検索フォーム |
| レシピ一覧 | `/recipes` | レシピ一覧、フィルタリング、ソート、検索 |
| レシピ詳細 | `/recipes/{id}` | レシピ詳細表示、レビュー一覧 |
| ユーザー登録 | `/register` | ユーザー登録フォーム |
| ログイン | `/login` | ログインフォーム |

#### 認証ページ（ログイン必須）

| 画面名 | URL | 主な機能 |
|--------|-----|---------|
| マイページ | `/mypage` | 投稿レシピ、お気に入り、プロフィール、統計 |
| レシピ投稿 | `/recipes/create` | レシピ投稿フォーム |
| レシピ編集 | `/recipes/{id}/edit` | レシピ編集フォーム |
| プロフィール編集 | `/profile/edit` | プロフィール編集フォーム |

---

### 画面レイアウト（共通）

**ヘッダー（全ページ共通）：**
- ロゴ/サイト名（左）
- 検索バー（中央）
- ナビゲーション（右）
  - 未ログイン時: ログイン、新規登録
  - ログイン時: レシピ投稿、マイページ、ログアウト

**フッター（全ページ共通）：**
- コピーライト
- 簡単な説明文

---

### トップページ（`/`）

**セクション構成：**

1. **ヒーローセクション**
   - キャッチコピー「美味しいレシピを共有しよう」
   - 検索バー
   - 背景画像

2. **新着レシピセクション**
   - 見出し「新着レシピ」
   - レシピカード6件（2列 × 3行）
   - 「もっと見る」リンク → `/recipes?sort=new`

3. **人気レシピセクション**
   - 見出し「人気レシピ」
   - レシピカード6件（2列 × 3行）
   - 「もっと見る」リンク → `/recipes?sort=popular`

**レシピカードの構成：**
```
┌─────────────────┐
│   [画像]         │
│                 │
├─────────────────┤
│ レシピ名         │
│ 投稿者名         │
│ ⏱ 30分 ★★★★☆   │
│ ♡ 12            │
└─────────────────┘
```

---

### レシピ一覧ページ（`/recipes`）

**レイアウト：**

```
┌──────────────────────────────────┐
│ ヘッダー                          │
├──────┬───────────────────────────┤
│      │ ソート: [新着順 ▼]        │
│ フィ │ 検索結果: 24件             │
│ ルタ ├───────────────────────────┤
│ ー   │ [レシピカード] [カード]    │
│      │ [レシピカード] [カード]    │
│ カテ │ [レシピカード] [カード]    │
│ ゴリ │ [レシピカード] [カード]    │
│ ー   │                           │
│ タグ │ ページネーション: 1 2 3 >  │
│ 時間 └───────────────────────────┘
│ 難易 │
│ 度   │
└──────┘
```

**フィルターサイドバー：**
- カテゴリー（ラジオボタン）
- タグ（チェックボックス）
- 調理時間（ラジオボタン）
- 難易度（チェックボックス）
- フィルターリセットボタン

**ソートオプション：**
- 新着順
- 人気順（お気に入り数）
- 評価順

---

### レシピ詳細ページ（`/recipes/{id}`）

**セクション構成：**

1. **レシピヘッダー**
   - レシピ名（大見出し）
   - 投稿者情報（アイコン、名前、投稿日）
   - お気に入りボタン（ハートアイコン）
   - 編集/削除ボタン（投稿者のみ表示）

2. **レシピ情報**
   - メイン画像（大）
   - サブ画像（ある場合、横スクロール）
   - 説明文
   - カテゴリー、タグ
   - 調理時間、人数、難易度
   - 評価（星）、レビュー数

3. **材料セクション**
   ```
   【材料（2人分）】
   - 豚バラ肉 ... 200g
   - キャベツ ... 1/4個
   - 塩こしょう ... 少々
   ```

4. **作り方セクション**
   ```
   【作り方】
   1. キャベツを一口大に切る
   2. フライパンで豚肉を炒める
   3. キャベツを加えて炒める
   4. 塩こしょうで味を整える
   ```

5. **レビューセクション**
   - レビュー投稿フォーム（ログイン時のみ）
   - 既存レビュー一覧
     - 投稿者名、星評価、コメント、投稿日時
     - 編集/削除ボタン（自分のレビューのみ）

---

### レシピ投稿/編集ページ（`/recipes/create`, `/recipes/{id}/edit`）

**フォーム項目：**

```
┌─────────────────────────────┐
│ レシピ投稿                   │
├─────────────────────────────┤
│ レシピ名 *                   │
│ [テキスト入力]               │
│                             │
│ 説明 *                       │
│ [テキストエリア]             │
│                             │
│ カテゴリー * [選択 ▼]        │
│ タグ                         │
│ □ 肉料理 □ 魚料理 □ 野菜料理 │
│ □ デザート □ 時短            │
│                             │
│ 調理時間 * [30] 分           │
│ 人数 * [2] 人分              │
│ 難易度 * [選択 ▼]            │
│                             │
│ メイン画像 * [ファイル選択]  │
│ サブ画像 [ファイル選択] × 3  │
│                             │
│ 【材料】                     │
│ 材料1: [名前] [分量] [削除]  │
│ 材料2: [名前] [分量] [削除]  │
│ [+ 材料を追加]               │
│                             │
│ 【作り方】                   │
│ 1. [説明] [削除]             │
│ 2. [説明] [削除]             │
│ [+ ステップを追加]           │
│                             │
│ □ 非公開にする               │
│                             │
│ [投稿する] [キャンセル]      │
└─────────────────────────────┘
```

**動的フィールド追加：**
- Alpine.jsで「材料を追加」「ステップを追加」を実装
- 削除ボタンでフィールド削除

---

### マイページ（`/mypage`）

**セクション構成：**

1. **プロフィールセクション**
   - プロフィール画像
   - ユーザー名
   - 自己紹介
   - 編集ボタン → `/profile/edit`

2. **統計セクション**
   ```
   投稿レシピ数: 12件
   お気に入りされた数: 45回
   平均評価: ★★★★☆ (4.2)
   ```

3. **タブセクション**
   - タブ1: 投稿レシピ（公開）
   - タブ2: 投稿レシピ（非公開）
   - タブ3: お気に入り

**各タブの表示：**
- レシピカード一覧（グリッド表示）
- ページネーション

---

## バリデーションルール

### レシピ投稿/編集

```php
[
    'title' => 'required|string|max:100',
    'description' => 'required|string|max:500',
    'category_id' => 'required|exists:categories,id',
    'tags' => 'nullable|array',
    'tags.*' => 'exists:tags,id',
    'cooking_time' => 'required|integer|min:1|max:999',
    'servings' => 'required|integer|min:1|max:10',
    'difficulty' => 'required|in:easy,medium,hard',
    'main_image' => 'required|image|mimes:jpeg,png,jpg|max:2048', // 2MB
    'sub_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    'ingredients' => 'required|array|min:1',
    'ingredients.*.name' => 'required|string|max:100',
    'ingredients.*.quantity' => 'required|string|max:50',
    'steps' => 'required|array|min:1',
    'steps.*.description' => 'required|string',
    'is_public' => 'boolean',
]
```

### レビュー投稿

```php
[
    'rating' => 'required|integer|min:1|max:5',
    'comment' => 'nullable|string|max:300',
]
```

### プロフィール編集

```php
[
    'name' => 'required|string|max:100',
    'bio' => 'nullable|string|max:500',
    'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
]
```

---

## ファイル構成

### Laravelプロジェクト構成

```
recipe-share/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── RecipeController.php
│   │   │   ├── FavoriteController.php
│   │   │   ├── ReviewController.php
│   │   │   └── ProfileController.php
│   │   └── Requests/
│   │       ├── RecipeStoreRequest.php
│   │       ├── RecipeUpdateRequest.php
│   │       └── ReviewStoreRequest.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Recipe.php
│   │   ├── Category.php
│   │   ├── Tag.php
│   │   ├── Ingredient.php
│   │   ├── Step.php
│   │   ├── RecipeImage.php
│   │   ├── Favorite.php
│   │   └── Review.php
│   └── Services/
│       ├── RecipeService.php
│       └── ImageService.php
├── database/
│   ├── migrations/
│   │   ├── 2024_01_01_000000_create_users_table.php
│   │   ├── 2024_01_01_000001_create_categories_table.php
│   │   ├── 2024_01_01_000002_create_tags_table.php
│   │   ├── 2024_01_01_000003_create_recipes_table.php
│   │   ├── 2024_01_01_000004_create_recipe_images_table.php
│   │   ├── 2024_01_01_000005_create_ingredients_table.php
│   │   ├── 2024_01_01_000006_create_steps_table.php
│   │   ├── 2024_01_01_000007_create_recipe_tags_table.php
│   │   ├── 2024_01_01_000008_create_favorites_table.php
│   │   └── 2024_01_01_000009_create_reviews_table.php
│   ├── seeders/
│   │   ├── CategorySeeder.php
│   │   ├── TagSeeder.php
│   │   └── DatabaseSeeder.php
│   └── factories/
│       ├── UserFactory.php
│       └── RecipeFactory.php
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   └── app.blade.php
│   │   ├── components/
│   │   │   ├── recipe-card.blade.php
│   │   │   └── header.blade.php
│   │   ├── recipes/
│   │   │   ├── index.blade.php
│   │   │   ├── show.blade.php
│   │   │   ├── create.blade.php
│   │   │   └── edit.blade.php
│   │   ├── mypage/
│   │   │   └── index.blade.php
│   │   └── welcome.blade.php
│   └── css/
│       └── app.css
├── routes/
│   └── web.php
├── storage/
│   └── app/
│       └── public/
│           ├── profiles/
│           └── recipes/
├── docker-compose.yml
└── Dockerfile
```

---

## ルーティング設計

### web.php

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProfileController;

// 公開ページ
Route::get('/', [RecipeController::class, 'welcome'])->name('welcome');
Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');
Route::get('/recipes/{recipe}', [RecipeController::class, 'show'])->name('recipes.show');

// 認証必須ページ
Route::middleware('auth')->group(function () {
    // レシピ
    Route::get('/recipes/create', [RecipeController::class, 'create'])->name('recipes.create');
    Route::post('/recipes', [RecipeController::class, 'store'])->name('recipes.store');
    Route::get('/recipes/{recipe}/edit', [RecipeController::class, 'edit'])->name('recipes.edit');
    Route::put('/recipes/{recipe}', [RecipeController::class, 'update'])->name('recipes.update');
    Route::delete('/recipes/{recipe}', [RecipeController::class, 'destroy'])->name('recipes.destroy');
    
    // お気に入り
    Route::post('/favorites/{recipe}', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{recipe}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
    
    // レビュー
    Route::post('/recipes/{recipe}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    
    // マイページ
    Route::get('/mypage', [ProfileController::class, 'index'])->name('mypage');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Laravel Breeze認証ルート
require __DIR__.'/auth.php';
```

---

## Eloquentモデル設計

### Userモデル

```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_image',
        'bio',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // リレーション
    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
```

---

### Recipeモデル

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recipe extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'cooking_time',
        'servings',
        'difficulty',
        'main_image',
        'is_public',
        'views_count',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    // リレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'recipe_tags');
    }

    public function ingredients()
    {
        return $this->hasMany(Ingredient::class)->orderBy('display_order');
    }

    public function steps()
    {
        return $this->hasMany(Step::class)->orderBy('step_number');
    }

    public function images()
    {
        return $this->hasMany(RecipeImage::class)->orderBy('display_order');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // アクセサ
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function getFavoritesCountAttribute()
    {
        return $this->favorites()->count();
    }

    // スコープ
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeWithRelations($query)
    {
        return $query->with(['user', 'category', 'tags', 'ingredients', 'steps', 'images']);
    }
}
```

---

### その他のモデル

**Category, Tag, Ingredient, Step, RecipeImage, Favorite, Reviewモデルも同様に定義**

リレーションとfillableを適切に設定

---

## Service層設計

### RecipeService

```php
<?php

namespace App\Services;

use App\Models\Recipe;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RecipeService
{
    public function createRecipe(array $data)
    {
        return DB::transaction(function () use ($data) {
            // レシピ作成
            $recipe = Recipe::create([
                'user_id' => auth()->id(),
                'category_id' => $data['category_id'],
                'title' => $data['title'],
                'description' => $data['description'],
                'cooking_time' => $data['cooking_time'],
                'servings' => $data['servings'],
                'difficulty' => $data['difficulty'],
                'main_image' => $this->saveImage($data['main_image'], 'recipes'),
                'is_public' => $data['is_public'] ?? true,
            ]);

            // タグ紐付け
            if (isset($data['tags'])) {
                $recipe->tags()->attach($data['tags']);
            }

            // サブ画像保存
            if (isset($data['sub_images'])) {
                foreach ($data['sub_images'] as $index => $image) {
                    $recipe->images()->create([
                        'image_path' => $this->saveImage($image, 'recipes'),
                        'display_order' => $index + 1,
                    ]);
                }
            }

            // 材料保存
            foreach ($data['ingredients'] as $index => $ingredient) {
                $recipe->ingredients()->create([
                    'name' => $ingredient['name'],
                    'quantity' => $ingredient['quantity'],
                    'display_order' => $index + 1,
                ]);
            }

            // 作り方保存
            foreach ($data['steps'] as $index => $step) {
                $recipe->steps()->create([
                    'step_number' => $index + 1,
                    'description' => $step['description'],
                ]);
            }

            return $recipe;
        });
    }

    public function updateRecipe(Recipe $recipe, array $data)
    {
        return DB::transaction(function () use ($recipe, $data) {
            // レシピ更新
            $updateData = [
                'category_id' => $data['category_id'],
                'title' => $data['title'],
                'description' => $data['description'],
                'cooking_time' => $data['cooking_time'],
                'servings' => $data['servings'],
                'difficulty' => $data['difficulty'],
                'is_public' => $data['is_public'] ?? true,
            ];

            // メイン画像更新
            if (isset($data['main_image'])) {
                // 古い画像削除
                Storage::disk('public')->delete($recipe->main_image);
                $updateData['main_image'] = $this->saveImage($data['main_image'], 'recipes');
            }

            $recipe->update($updateData);

            // タグ再紐付け
            $recipe->tags()->sync($data['tags'] ?? []);

            // サブ画像更新
            if (isset($data['sub_images'])) {
                // 既存画像削除
                foreach ($recipe->images as $image) {
                    Storage::disk('public')->delete($image->image_path);
                }
                $recipe->images()->delete();

                // 新規画像保存
                foreach ($data['sub_images'] as $index => $image) {
                    $recipe->images()->create([
                        'image_path' => $this->saveImage($image, 'recipes'),
                        'display_order' => $index + 1,
                    ]);
                }
            }

            // 材料更新
            $recipe->ingredients()->delete();
            foreach ($data['ingredients'] as $index => $ingredient) {
                $recipe->ingredients()->create([
                    'name' => $ingredient['name'],
                    'quantity' => $ingredient['quantity'],
                    'display_order' => $index + 1,
                ]);
            }

            // 作り方更新
            $recipe->steps()->delete();
            foreach ($data['steps'] as $index => $step) {
                $recipe->steps()->create([
                    'step_number' => $index + 1,
                    'description' => $step['description'],
                ]);
            }

            return $recipe;
        });
    }

    private function saveImage($image, $directory)
    {
        $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        $path = $image->storeAs($directory, $filename, 'public');
        return $path;
    }
}
```

---

## Docker環境構成

### docker-compose.yml

```yaml
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: recipe-app
    volumes:
      - .:/var/www/html
    networks:
      - recipe-network
    depends_on:
      - db

  web:
    image: nginx:alpine
    container_name: recipe-web
    ports:
      - "8000:80"
    volumes:
      - .:/var/www/html
      - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf
    networks:
      - recipe-network
    depends_on:
      - app

  db:
    image: mysql:8.0
    container_name: recipe-db
    environment:
      MYSQL_DATABASE: recipe_share
      MYSQL_ROOT_PASSWORD: root
      MYSQL_USER: recipe_user
      MYSQL_PASSWORD: recipe_pass
    ports:
      - "3306:3306"
    volumes:
      - db-data:/var/lib/mysql
    networks:
      - recipe-network

networks:
  recipe-network:
    driver: bridge

volumes:
  db-data:
```

---

### Dockerfile

```dockerfile
FROM php:8.2-fpm

# 必要なパッケージインストール
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# PHP拡張インストール
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 権限設定
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage
```

---

### Nginx設定（docker/nginx/default.conf）

```nginx
server {
    listen 80;
    index index.php index.html;
    server_name localhost;
    root /var/www/html/public;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

---

## セットアップ手順

### 1. プロジェクト初期化

```bash
# Laravelプロジェクト作成
composer create-project laravel/laravel recipe-share
cd recipe-share

# Laravel Breezeインストール
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install && npm run build

# Docker起動
docker-compose up -d

# マイグレーション実行
docker-compose exec app php artisan migrate

# シーダー実行
docker-compose exec app php artisan db:seed

# ストレージリンク作成
docker-compose exec app php artisan storage:link
```

### 2. 環境変数設定（.env）

```env
APP_NAME="RecipeShare"
APP_ENV=local
APP_KEY=base64:xxxxxxxxxxxx
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=recipe_share
DB_USERNAME=recipe_user
DB_PASSWORD=recipe_pass

FILESYSTEM_DISK=public
```

### 3. アクセス

```
http://localhost:8000
```

---

## テストデータ作成

### Seeder作成

**CategorySeeder:**
```php
DB::table('categories')->insert([
    ['name' => '和食'],
    ['name' => '洋食'],
    ['name' => '中華'],
    ['name' => 'イタリアン'],
    ['name' => 'その他'],
]);
```

**TagSeeder:**
```php
DB::table('tags')->insert([
    ['name' => '肉料理'],
    ['name' => '魚料理'],
    ['name' => '野菜料理'],
    ['name' => 'デザート'],
    ['name' => '時短'],
    ['name' => 'ヘルシー'],
    ['name' => 'がっつり'],
    ['name' => 'おつまみ'],
]);
```

**ダミーレシピ作成（Factory）:**
- UserFactory で10ユーザー作成
- RecipeFactory で各ユーザー3-5レシピ作成

---

## デプロイ（Render - 無料）

### 手順

1. **GitHubリポジトリにプッシュ**

2. **Render.comでWebサービス作成**
   - Build Command: `composer install && php artisan migrate --force && php artisan db:seed --force`
   - Start Command: `php artisan serve --host=0.0.0.0 --port=$PORT`

3. **環境変数設定**
   - Renderダッシュボードで.envの内容を設定

4. **MySQLデータベース作成**
   - Renderで無料MySQLインスタンス作成
   - DB接続情報を環境変数に設定

---

## パフォーマンス最適化

### N+1問題対策

```php
// レシピ一覧
Recipe::with(['user', 'category', 'tags'])
    ->withCount(['favorites', 'reviews'])
    ->withAvg('reviews', 'rating')
    ->public()
    ->latest()
    ->paginate(12);
```

### 画像最適化

- アップロード時に自動リサイズ（Intervention Image）
- サムネイル生成

### キャッシュ（オプション）

- カテゴリー、タグマスタをキャッシュ
- 人気レシピをキャッシュ（5分）

---

## セキュリティ対策

### 実装済み対策

- **XSS**: Blade自動エスケープ
- **CSRF**: Laravel標準トークン
- **SQLインジェクション**: Eloquent ORM
- **パスワード**: bcryptハッシュ化
- **ファイルアップロード**: 
  - 拡張子チェック（jpeg, png, jpg のみ）
  - ファイルサイズ制限（2MB）
  - MIMEタイプチェック

### 認可（Policy）

```php
// RecipePolicy
public function update(User $user, Recipe $recipe)
{
    return $user->id === $recipe->user_id;
}

public function delete(User $user, Recipe $recipe)
{
    return $user->id === $recipe->user_id;
}
```

---

## 今後の拡張（オプション）

### フェーズ2
- [ ] コメント機能
- [ ] いいね機能
- [ ] フォロー機能
- [ ] タイムライン

### フェーズ3
- [ ] React/Vue.js化
- [ ] API化（Laravel Sanctum）
- [ ] 管理者機能

---

## 補足

### 画像について
- すべて`storage/app/public`に保存
- シンボリックリンク: `php artisan storage:link`
- 表示: `asset('storage/recipes/xxx.jpg')`

### Alpine.jsの使用例
- お気に入りボタンのトグル
- 動的フィールド追加（材料、作り方）
- モーダル表示

### Tailwind CSSのビルド
```bash
npm run dev  # 開発時
npm run build  # 本番ビルド
```

---

## 完成イメージ

このアプリケーションは以下の特徴を持ちます：

✅ 完全無料で動作  
✅ Docker環境で即座に起動  
✅ Render等の無料ホスティングにデプロイ可能  
✅ レスポンシブデザイン  
✅ 実践的なCRUD操作  
✅ リレーションシップの実装  
✅ 画像アップロード機能  
✅ 検索・フィルタリング機能  
✅ お気に入り・レビュー機能  

