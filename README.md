# OMDB!

![CI](https://github.com/rennokki/fabric-interview/workflows/CI/badge.svg?branch=master)

If you are here without context: congrats, you broke the Matrix. 😃👍

Laravel's `serve` command is breaking the `Cache` module (and the matrix), so no cache. 🥲 

## Getting Started

Run the following command:

```bash
cp .env.example .env ; \
    touch database/database.sqlite ; \
    composer install ; \
    npm ci ; \
    npm run build ; \
    php artisan key:generate ; \
    php artisan migrate
```

Add your OMDB API key to your `.env`:

```
OMDB_API_KEY=<your_key>
```

Then run the server:

```bash
php artisan serve
```

Access the interface at `https://127.0.0.1:8000`. 😃👍

![Matrix, Neo "și ăilanți"](assets/screenshot.png)
