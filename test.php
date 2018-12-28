<?php

use diversen\translate\extractor;

// same pattern as above for extraction

$e = new extractor();
$e->defaultLanguage ='en'; // which language will we extract to
$e->setDirsInsideDir('modules/');
$e->setDirsInsideDir('htdocs/templates/');
$e->setSingleDir("vendor/diversen/simple-php-classes");
$e->updateLang();
