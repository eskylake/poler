<?php

require_once '../vendor/autoload.php';
require_once './GlobalFunctions.php';

use App\core\Autoload;

spl_autoload_register(__NAMESPACE__ . 'App\core\Autoload::loader');

(new Autoload)->run();