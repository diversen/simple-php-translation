<?php

use Diversen\Lang;

require_once "vendor/autoload.php";

$l = new Lang();

// Most often all translations are placed in a single folder
$l->setSingleDir("app");

// But you can also set dirs, and look for language files inside multiple language dirs, e.g.:
//  
// modules/account
// modules/blog
// and etc. 
// $l->setDirsInsideDir("modules/");

// load language. E.g. danish ('da')
// Will load all 'da' files from above dirs.
//  
// app/lang/da/language.php
// $l->loadLanguage('da');

// Or english
// e.g. app/lang/da/language.php
$l->loadLanguage('en');