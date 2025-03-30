<?php 

require __DIR__ . '/vendor/autoload.php';

use Webchat\Helper;
use Webchat\Utils;

echo Helper::sayHello();
echo Utils::add(2, 3);   // 5