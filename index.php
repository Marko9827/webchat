<?php 

require __DIR__ . '/vendor/autoload.php';

use marko9827\Webchat\Helper;
use marko9827\Webchat\Utils;

echo Helper::sayHello();
echo Utils::add(2, 3);   // 5