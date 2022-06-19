<?php

use Diversen\Translate\GoogleTranslate;

include_once "vendor/autoload.php";

// Google translator needs this. Substitue with path to your own .json file  
putenv("GOOGLE_APPLICATION_CREDENTIALS=google_json/pebble-2c949028ebcc.json");

// Translate all english strings to danish language file which will be placed in app/lang/da/language.php
$t = new GoogleTranslate();
$t->target = 'da'; // danish
$t->source = 'en';

$t->setSingleDir("test_app");
$t->updateLang();
