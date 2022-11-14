## Paradoxx dev stack

- Backend - Laravel 8, AdminLTE package for admin panel
- Frontend - CSS, JQuery for public; Bootstrap, Vue3 for adminpanel
- Database - MySQL

Wabpack5 for both public and admin fronts, use package commands
(webpack-admin.config.js, webpack-public.config.js configs)

```
npm run webpack-admin - for dev
npm run build-admin - for deploy
npm run webpack-public - for dev
npm run build-public - for deploy
```

Base code structure of Laravel project, source code of frontend in /resources/{js,css}/.

## Install

- Load scripts, /public folder should be the one there domain root looks for index.php and static files
- Config Nginx - https://laravel.com/docs/9.x/deployment
- Create MySql datbase and upload dump from /database/fl_paradox.sql
- For /storage and all subfolders and files set permissions for writing,
same for /public/assets/imgs/pool
- Config /.env file with params of your server APP_URL, DB_*, MAIL_*
- In root folder clear all Laravel caches by command ```php artisan optimize:clear```
- Login to adminpanel /admin/login with credentials admin@admin.com password 111
- Add cron job with your project path, first try it from shell to check for no errors
```* * * * * root cd /var/www/www-root/data/www/paradoxx.online && /usr/bin/php artisan schedule:run > /dev/null 2>&1```

## Testsing

There are PhpUnit test, mostly for covering API consistency.

Test is used on dev server not tuned on production now.

To use this tests first create empty database for tests for example ‘ paradox_test’, then in .env file update TESTS_DB_NAME, MYSQLDUMP_PATH, MYSQL_PATH parameters.

- Run ```php artisan tests:prepare```
This just makes a copy of current data base for tests.
- Then ```php artisan test --env=testing```