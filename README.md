# hurima
模擬案件「フリマアプリ」

## プロジェクト概要
本プロジェクトは、実践学習ターム 模擬案件初級「フリマアプリ」の開発プロジェクトです。
Laravel 8.75を使用したフリマアプリケーションです。

## 環境構築

### 前提条件
- Docker Desktopがインストールされていること
- Gitがインストールされていること

### Dockerビルド
1. リポジトリをクローン
```bash
git clone git@github.com:syamuichigo/hurima.git
cd hurima
```

2. Docker Desktopアプリを立ち上げる

3. Dockerコンテナをビルドして起動
```bash
docker-compose up -d --build
```

**注意**: MySQLはOSによって起動しない場合があるので、それぞれのPCに合わせてdocker-compose.ymlファイルを編集してください。

### Laravel環境構築
1. PHPコンテナに接続
```bash
docker-compose exec php bash
```

2. Composerで依存パッケージをインストール
```bash
composer install
```

3. 環境変数ファイルの作成
```bash
cp .env.example .env
```

4. .envファイルに以下の環境変数を追加
```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass

# Stripe設定（クレジットカード支払い用）
STRIPE_KEY=<your_publishable_key>
STRIPE_SECRET=<your_secret_key>
```

**Stripe APIキーの取得方法:**
1. [Stripe](https://stripe.com/jp)にアカウントを作成
2. ダッシュボードの「開発者」→「APIキー」から公開可能キーとシークレットキーを取得
3. テスト環境では公開可能キーが「pk_test_」、シークレットキーが「sk_test_」で始まることを確認

5. .envファイルから以下の環境変数を削除（存在する場合）
```env
MAIL_FROM_ADDRESS=null
```

6. アプリケーションキーの生成
```bash
php artisan key:generate
```

7. マイグレーションの実行
```bash
php artisan migrate
```

**注意**: マイグレーションファイルは以下のように構成されています：
- `create_users_table.php`: ユーザー情報（Laravel標準）
- `create_password_resets_table.php`: パスワードリセット（Laravel標準）
- `add_two_factor_columns_to_users_table.php`: 2要素認証カラム
- `add_profile_columns_to_users_table.php`: プロフィール情報をusersに統合
- `create_categories_table.php`: カテゴリー
- `create_contents_table.php`: 商品情報（condition文字列、seller_idで出品者管理）
- `create_content_category_table.php`: 商品とカテゴリーの中間テーブル
- `create_comments_table.php`: コメント
- `create_purchase_table_with_rating.php`: 購入情報（評価カラム統合）
- `create_favorites_table.php`: お気に入り
- `create_messages_table.php`: 取引メッセージ

既存のデータベースを使用している場合、以下のいずれかの方法でマイグレーションを実行してください：
- 新規環境の場合: `php artisan migrate` を実行
- 既存環境の場合: `php artisan migrate:fresh` を実行（データベースをリセットして再実行）

8. シーディングの実行（ダミーデータの作成）
```bash
php artisan db:seed
```

## 使用技術(実行環境)
- PHP 7.4-fpm
- Laravel 8.75
- MySQL 8.0.26
- Nginx 1.21.1
- Docker / Docker Compose
- Stripe (クレジットカード支払い)

## ER図
![ER図](er.png)

## データベース構成
- users: ユーザー情報（プロフィール情報を統合: image, name, postcode, address, building）
- password_resets: パスワードリセット（Laravel標準）
- categories: カテゴリー情報
- contents: 商品情報（category_id、seller_id、condition文字列、複数カテゴリはcontent_category経由）
- content_category: 商品とカテゴリーの中間テーブル（content_id、category_idに依存）
- comments: コメント情報（content_id、user_idに依存）
- purchase: 購入情報（user_id、content_idに依存、status、buyer_rating、seller_ratingで評価を統合）
- favorites: お気に入り情報（content_id、user_idに依存）
- messages: メッセージ情報（purchase_id、user_idに依存、取引中のチャット）

## テストユーザー情報
シーディング実行後、以下の3人のテストユーザーが作成されます：

**ユーザー1**
- username: user1
- email: user1@example.com
- password: password

**ユーザー2**
- username: user2
- email: user2@example.com
- password: password

**ユーザー3**
- username: user3
- email: user3@example.com
- password: password

## URL
- 開発環境：http://localhost/
- phpMyAdmin：http://localhost:8080/

## テスト実行
PHPUnitを使用したテストを実行する場合：
```bash
docker-compose exec php bash
php artisan test
```

または
```bash
docker-compose exec php bash
./vendor/bin/phpunit
```