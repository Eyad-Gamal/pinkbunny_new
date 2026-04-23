# Pink Bunny E-commerce Project TODO

## Initial Setup (Approved Plan)

1. ✅ Create this TODO.md
2. ✅ Install fresh Laravel project (v12.12.2)
3. ⚠️ Manual .env DB update (DB_DATABASE=pink_bunny_db, DB_USERNAME=root, DB_PASSWORD=)
4. Install Laravel Breeze (composer deps complete, run `php artisan breeze:install blade` after autoload)
5. Setup Tailwind CSS (npm run build)
6. ✅ Complete: Create database migrations (8/8 done, users extended)
7. ✅ Complete: Create Eloquent models (7/7 done + User relationships)
8. Scaffold controllers (ProductController, etc.)
9. Create Blade views for core pages
10. Implement features (auth, catalog, cart, etc.)
11. Run migrations (`php artisan migrate`)
12. Seed data and test (`php artisan serve`)

**Status: DB scaffolding ready! Composer hanging - user can `taskkill /f /im php.exe` to clean terminals, then `composer dump-autoload` + `php artisan migrate`. Next: Controllers & Homepage on request.**
