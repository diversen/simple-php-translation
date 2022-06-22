<?php

include_once "vendor/autoload.php";

use Diversen\Translate\Extractor;

// Extract all strings into test_app/lang/en/language.php
$e = new Extractor();
$e->defaultLanguage ='en';

// Search in all files in the test_app/ dir
$e->setSingleDir("test_app");

// Update the translation with new strings
$e->updateLang();
