<?php

include_once "vendor/autoload.php";

use Diversen\Translate\Extractor;

// Extract a all strings into app/lang/en/language.php
$e = new Extractor();
$e->defaultLanguage ='en'; // which language will we extract to

// Most often you will just use a single dir. Like this
$e->setSingleDir("app");
$e->updateLang();
