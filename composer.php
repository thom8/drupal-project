<?php

require('./vendor/autoload.php');

use DrupalProject\composer\WebComposer;
use Symfony\Component\HttpFoundation\Request;

$request = Request::createFromGlobals();
$composer = new WebComposer($request);
$output = $composer->run();

print '<pre>' . $output . '</pre>';
