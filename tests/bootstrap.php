<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

new Dotenv()->bootEnv(dirname(__DIR__).'/.env');

if (($_SERVER['APP_DEBUG'] ?? '0') === '1') {
    umask(0000);
}
