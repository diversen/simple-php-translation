<?php

include_once "vendor/autoload.php";

use diversen\translate\extractor;

// same pattern as above for extraction

$e = new extractor();
$e->defaultLanguage ='en'; // which language will we extract to
$e->setSingleDir("test");
$e->updateLang();
