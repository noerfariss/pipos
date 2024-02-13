# Pipos :: Point of sales application

### Requirement
- PHP min. 8.1
- MariaDB / MySQL
- Laravel 10
- The PHP extension must be activated. (gd)

### Installation

Clone this repository onto your local PC / VPS
```
git clone https://github.com/noerfariss/pipos.git
```

Copy .env from .env.example
```
cp .env.example .env
```

Install Composer
```
composer install
```

Generate key with artisan
```
php artisan key:generate
```

Crete symlink storage
```
php artisan storage:link
```

If you are using Debian/Ubuntu OS, ensure that the 'storage' folder in Pipos has www-data ownership permissions.


Create dummy data
```
php artisan migrate --seed
```




#### Enjoy this code :) and don't forget running the website
```
php artisan serve
```

or if you are using the laragon, access it from the browser with http://pipos.test
