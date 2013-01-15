<?php

use Symfony\Component\ClassLoader\UniversalClassLoader;

require_once __DIR__.'/vendor/autoload.php';


$Loader = new UniversalClassLoader();

$Loader->registerNamespace('API', __DIR__);

$Loader->registerNamespace('Vendor', __DIR__.'/vendor');
$Loader->registerNamespace('Test', __DIR__.'/test');

$Loader->useIncludePath(true);
$Loader->registerPrefixFallback(__DIR__.'/API');


$Loader->register();

