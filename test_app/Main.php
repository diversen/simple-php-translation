<?php

require "vendor/autoload.php";

use Diversen\Lang;

$l = new Lang();

// Load translation from a single dir
$l->setSingleDir("test_app");
$l->loadLanguage('en');

?>

<!-- span class="notranslate" signals to google that this part of the string should'nt be translated --> 
<h1><?=Lang::translate('Welcome to <span class="notranslate">simple php translation</span>');?></h1>

<p><?=Lang::translate("The easiest way to translate PHP apps")?></p>

<!-- {name} is a placeholder which is substituted with 'John Doe' -->
<p><?=Lang::translate('I think your name is: <span class="notranslate">{name}</span>', ['name' => 'John Doe'])?>

