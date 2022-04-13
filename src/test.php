<?php

// same pattern as above for google auto translation.
use Diversen\Translate\GoogleTranslate;

include_once "vendor/autoload.php";

// Google translator needs this. Substitue with path to your own .json file  
putenv("GOOGLE_APPLICATION_CREDENTIALS=config-locale/pebble-2c949028ebcc.json");

$t = new GoogleTranslate();
$t->target = 'da'; // danish
$t->source = 'en';

$t->setSingleDir("app");

// Or set multiple dirs like this:
// This will create translation folders in e.g. modules/blog, modules/account
// $e->setDirsInsideDir('modules/');

$t->updateLang();