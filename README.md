# sowsow

## 概要
sowsowはPHPとJavaScriptで構築されたWebアプリケーションです。主にユーザー管理やログ管理などの機能を提供します。

## ディレクトリ構成
- `main.php` : メインのエントリポイント
- `sowsow.js` : クライアントサイドのJavaScript
- `css/` : スタイルシート（PC、タブレット、モバイル用に分割）
- `images/` : 画像ファイル
- `other/` : 各種PHPスクリプト（DB操作、認証、ユーザー管理など）

## 必要環境
- PHP 7.x 以上
- Webサーバー（Apache, Nginx等）
- 推奨: MySQL等のデータベース

## セットアップ方法
1. 本リポジトリをWebサーバーの公開ディレクトリに配置
2. 必要に応じて`other/sowsow_DB.php`等のDB接続情報を編集
3. Webサーバーを起動し、`main.php`にアクセス

## ライセンス
このプロジェクトはMITライセンスの下で公開されています。
