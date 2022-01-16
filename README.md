## Project Setup

```
composer should be installed
node js and npm should be installed

open two terminals in project path
1 for frontend
1 for backend
```

## BackEnd setup

```
in root directory create .env file
use this command in terminal 1
cp .env.example .env
```

## Replace below and update .env
```
DB_CONNECTION=sqlite
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=/home/dissanayake/DEVS/PRACTICAL/openWeather_madusanka/database/database.sqlite
DB_USERNAME=root
DB_PASSWORD=
DB_FOREIGN_KEYS=true

make sure to replace DB_DATABASE with your absolute path

now in same terminal run below commands
composer update or composer install
php artisan migrate
php artisan key:generate
php artisan serve
```

## FrontEnd setup

```
in terminal 2 run below commands
cd frontend
npm i
npm run serve

and open the url in prefered browser
```

## Note
```
if needed delete the database/database.slite file and create new file with
touch database/database.sqlite
and then run

php artisan migrate
```
