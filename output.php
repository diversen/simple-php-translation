<?php

include_once "vendor/autoload.php";

use diversen\lang;

$l = new lang();

$l->setSingleDir("test");

// load language. E.g. danish ('da')
// Will load all 'da' files from above dirs.

$l->loadLanguage('da');

include_once "test/README.php";
