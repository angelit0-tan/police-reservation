### `Setup`

Add .env:

```bash
Rename .env.example into .env
```
Set Your TimeZone

```bash
Go to: config/app.php and set the timezone value according to your region or state

Example:

'timezone' => 'Europe/Prague'

or

'timezone' => 'UTC'
```

Build Docker Image:

```bash
docker compose up -d --build
```

Make sure to install the dependencies and migration:

```bash
# composer
docker-compose exec app composer install

# npm
docker-compose exec app npm install

# key generate
docker-compose exec app php artisan key:generate

# migrate
docker-compose exec app php artisan migrate

# config clear
docker-compose exec app php artisan config:clear
```

Run the system:

```bash
# run
docker-compose exec app npm run dev
```

Access Localhost:
```bash
http://localhost:1241/
```

