<?php

use App\Support\PhpCsFixer;

$config = require __DIR__ . '/standards/.php-cs-fixer.dist.php';

return $config->setFinder(PhpCsFixer::getFinder()->exclude(['tests/Fixtures']));
