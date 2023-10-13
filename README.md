# Laravel Tweet App
これはLaravelのサンプルアプリです。
以下の機能を確認するために作成しました。
- MySQLと接続し、データの閲覧・保存ができるか
- メール送信ができるか

# コミットする前に
`.env` に変更がある場合は以下のコマンドを実行し、 `.env.encrypted` を更新してください。
```
rm .env.encrypted
php artisan env:encrypt --key=（暗号化キー）
```