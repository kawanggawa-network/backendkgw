#!/usr/bin/env bash
composer install;
php artisan migrate;
php artisan optimize:clear;
php artisan optimize;
php artisan db:seed;
