# sowsow

## 概要

sowsow は PHP + MySQL + jQuery で構築されたシンプルな掲示板アプリです。ログイン/ログアウト、新規登録、投稿（カテゴリ付き）、検索、ページング、管理者による削除やアカウント管理ができます。

### 主な機能

- ユーザー認証（ログイン・新規登録、bcrypt によるパスワードハッシュ）
- 投稿作成（カテゴリ・メッセージ、簡易バリデーション、二重送信防止トークン）
- 投稿一覧・ページング（10 件/ページ）
- 部分一致検索（投稿者 ID・メッセージ）
- 管理機能（投稿削除、アカウントの管理者 ON/OFF 切り替え）
- レスポンシブ対応（PC/タブレット/モバイル用 CSS）

## 動作要件

- PHP 7.4 以上（PDO MySQL が有効であること）
- MySQL 5.7+ もしくは MariaDB 10+（UTF-8/utf8mb4 推奨）
- Web サーバー（Apache/Nginx）または PHP 組み込みサーバー
- jQuery 3.x（CDN またはローカル設置）

## ディレクトリ構成

- `main.php` … メイン画面（一覧・検索・投稿モーダル等）
- `sowsow.js` … フロント挙動（UI アニメーション、簡易バリデーション等）
- `css/` … CSS（`full/` `middle/` `mobile/` の 3 段階でレスポンシブ）
- `images/` … 画像アセット（アイコン等）
- `other/`
  - `login.php` / `logout.php` … 認証画面
  - `insert.php` / `delete.php` … 投稿作成・削除
  - `signup_result.php` … 新規登録結果
  - `user_edit.php` … 管理者用アカウント管理
  - `sowsow_DB.php` … DB 接続（編集ポイント）
  - `sowsow_DB_saver.php` … 別環境向け接続例
  - `system.php` … サニタイズ関数やセッション関連

## セキュリティに関する注意

- パスワードは `password_hash` によりハッシュ化されていますが、DB 接続情報など機微情報は環境に隔離（環境変数やサーバー側設定）し、公開リポジトリに含めないでください。
- 投稿やフォームには簡易の二重送信防止トークンがありますが、実運用では CSRF 対策やより厳密なバリデーションの追加を推奨します。
- 本番運用では HTTPS を有効にしてください。

## 作成者

**仲澤勇樹 (Nakazawa Yuuki)**

- GitHub: [@YuukiNakazawa0731](https://github.com/YuukiNakazawa0731)
- Portfolio: [Full Throttle Vue](https://yuukinakazawa0731.github.io/full_throttle_v/)
