#!/bin/bash

./vendor/bin/sail up -d

./vendor/bin/sail composer install

cp .env.example .env

./vendor/bin/sail php artisan key:generate

cp .env .env.testing

./vendor/bin/sail php artisan migrate:fresh --seed

./vendor/bin/sail  php artisan queue:work


