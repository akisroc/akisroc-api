## Akisroc JDR API

--------

### Dev installation
```bash
# Generating JWT keys

mkdir config/jwt
openssl genrsa -out config/jwt/private.pem -aes256 4096
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
```
```bash
composer install
```

--------

### Prod installation
```bash
# Generating JWT keys

mkdir config/jwt
openssl genrsa -out config/jwt/private.pem -aes256 4096
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
```
```bash
composer install --no-dev -o -a
php bin/console cache:clear --env=prod --no-debug
php bin/console doctrine:migrations:migrate
```
