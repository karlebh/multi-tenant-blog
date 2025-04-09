# Multi Tenant Blog

To run please follow these instructions:

1. Clone the repository.
2. cd into it.
3. run `composer install` command.
4. run `php artisan key:generate` command.
5. run `npm install` command.
6. cd into the folder in a separate terminal and run `npm run dev`.
7. Create a `.env` from from the `.env.example` file.
8. set up the database. You can use sqlite by replacing the database configurations with

```
    DB_CONNECTION=sqlite
    DB_DATABASE=database/database.sqlite
```

9. run `php artisan migrate --seed` to migrate and seed the database.
10. run `php artisan serve`.
11. Go to the given address in the terminal in your browser.

The api collection is included in the root directory as `Multi Tenant Blog.postman_collection.json`
