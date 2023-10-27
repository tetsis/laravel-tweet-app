# Laravel Tweet App
これはLaravelのサンプルアプリです。
以下の機能を確認するために作成しました。
- MySQLと接続し、データを閲覧・保存できるか
- メールを送信できるか

## コミットする前に
`.env` に変更がある場合は以下のコマンドを実行し、 `.env.encrypted` を更新してください。
```
rm .env.encrypted
php artisan env:encrypt --key=（暗号化キー）
```

# 構築手順
## OS
Ubuntu Server 22.04 LTS
- SSH, HTTPを受信できるようにポートを開けておく
- Azure VMを想定

## SSHでログイン
```
> ssh -i （秘密鍵） azureuser@（サーバのIPアドレス）
```

## アップデート
```
sudo apt update
```

## Apache
- リバースプロキシにApacheを使うときにのみ実行する
- Nginxを使う場合はこの項目の手順は不要

### インストール
```
sudo apt install apache2
```

### 設定
```
sudo vim /etc/apache2/sites-available/000-default.conf
```

- ファイルの中身を以下のようにする
```
<VirtualHost *:80>

        ...

        DocumentRoot /var/www/laravel-tweet-app/public

        ...

        <Directory "/var/www/laravel-tweet-app/public">
                RewriteEngine On
                AllowOverride All
                Allow from All
        </Directory>
</VirtualHost>
```

### mod_rewriteを有効にする
```
sudo a2enmod rewrite
```

### 再起動
```
sudo systemctl restart apache2
```

## Nginx
- リバースプロキシにNginxを使うときにのみ実行する
- Apacheを使う場合はこの項目の手順は不要

### インストール
```
sudo apt install nginx
```

### 設定
```
sudo vim /etc/nginx/sites-available/default
```

- ファイルの中身を以下のようにする
```
server {
        listen 80 default_server;
        listen [::]:80 default_server;

        root /var/www/laravel-tweet-app/public;

        index index.php;

        charset utf-8;

        server_name _;

        location / {
                try_files $uri $uri/ /index.php?$query_string;
        }

        location = /favicon.ico { access_log off; log_not_found off; }
        location = /robots.txt  { access_log off; log_not_found off; }

        error_page 404 /index.php;

        location ~ \.php$ {
                fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
                fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
                include fastcgi_params;
        }

        location ~ /\.(?!well-known).* {
                deny all;
        }
}
```

### 再起動
```
sudo systemctl restart nginx
```

## PHPインストール
```
sudo apt install php
php --version
```

```
PHP 8.1.2-1ubuntu2.14 (cli) (built: Aug 18 2023 11:41:11) (NTS)
```
- 8.1であることを確認する

```
sudo apt install php-fpm php-dom php-curl php-mbstring unzip php-mysql
```

## Composerインストール
```
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"
sudo mv composer.phar /usr/local/bin/composer
composer --version
```

```
Composer version 2.6.5 2023-10-06 10:11:52
```

## MySQL
### インストール
```
sudo apt install mysql-server
sudo systemctl start mysql
sudo systemctl enable mysql
sudo mysql_secure_installation
```

```
Please enter 0 = LOW, 1 = MEDIUM and 2 = STRONG: 0
```
- その他の選択時は全部 y でOK

### データベースを作成
```
sudo mysql -u root
mysql> CREATE USER 'laravel'@'localhost' IDENTIFIED BY 'laravel-tweet-app';
mysql> CREATE DATABASE IF NOT EXISTS laravel;
mysql> GRANT ALL ON laravel.* TO 'laravel'@'localhost';
mysql> FLUSH PRIVILEGES;
mysql> exit
```

### バックアップファイルをリストア
```
mysql -u laravel -p laravel < backup.sql
```
- データベースのダンプデータ `backup.sql` がカレントディレクトリにある、という想定

## ユーザー権限
```
sudo chown -R www-data:www-data /var/www/
sudo chmod g+w -R /var/www/
sudo usermod -aG www-data azureuser
```

- グループ情報を反映させるため、ここで再ログイン

## アプリのソースコードをダウンロード
```
cd /var/www/
git clone https://github.com/tetsis/laravel-tweet-app
cd laravel-tweet-app/
```

## ライブラリをインストール
```
composer install
```

## 環境ファイルを復元
```
php artisan env:decrypt --key=（暗号化キー）
sudo chown -R www-data:www-data /var/www/laravel-tweet-app/
```

## 設定キャッシュ
```
php artisan config:cache
```

## （念のため）リバースプロキシを再起動
- Apacheの場合
```
sudo systemctl restart apache2
```

- Nginxの場合
```
sudo systemctl restart nginx
```

## ブラウザでページの表示を確認
- http://（サーバのIPアドレス）/tweet
- http://（サーバのIPアドレス）/email

# 参考
- [Laravel 10.x デプロイ | ReaDouble](https://readouble.com/laravel/10.x/ja/deployment.html)
- [Deployment | Laravel](https://laravel.com/docs/10.x/deployment)
- [Download Composer](https://getcomposer.org/download/)