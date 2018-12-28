<?php

use diversen\lang;

?>
# <?=lang::translate('Brief Overview');?>

<?=lang::translate('The `simple-php-translation` is a simple solution for adding 
translations to your PHP apps. It makes it easy to translate,
extract translation strings, and to do auto-translation through 
google translate API. It is also easy to add your files to a 
translation service like [transifex](https://www.transifex.com/)
when you realise, that you need a human translation.');?>

<?=lang::translate('Install:');?>

    composer require diversen/simple-php-translation

<?=lang::translate('Note: Example follows default settings. Settings can be changed. But if 
    You are starting up a new project, then you could follow this convention for ease of use.'); ?>

<?=lang::translate('Translations are placed in files called:');?>

    lang/en/language.php
    lang/da/language.php

<?=lang::translate('E.g. inside a blog');?>

    blog/lang/en/language.php

<?=lang::translate('This file could consist of this:');?>

<span class="notranslate"></span>
~~~.php
$LANG = array ();
$LANG['Welcome to my blog'] = 'Welcome to my blog';
~~~
</span>

<?=lang::translate('A Danish translation will then be found in: ') ?>

    blog/lang/da/language.php

<?=lang::translate('And this file could consists of: ');?>

~~~.php
    $LANG = array ();
    $LANG['Welcome to my blog'] = 'Velkommen til min blog';
~~~

<?=lang::translate('# Load language'); ?>

~~~.php
use diversen\lang;

$l = new lang();

// <?=lang::translate('Set dirs where we look for language files inside a language dir, e.g.:')?>
// modules/account
// modules/blog 

$l->setDirsInsideDir("modules/");

// <?=lang::translate('Set a single dir');?>

$l->setSingleDir("vendor/diversen/simple-php-classes");

// <?=lang::translate('Load language. E.g. danish');?> ('da')
// <?=lang::translate('This will load all `da` files from above directories');?>.

$l->loadLanguage('da');

// <?=lang::translate('Now all language files are loaded and we can translate')?>
~~~

# <?=lang::translate('Translate'); ?>

~~~.php

// <?=lang::translate('simple');?>

echo lang::translate('Here is a text');

// with substitution and a <span class="notranslate">span</span> tag to indicate that a part of a string should not be translated

echo lang::translate('User with ID <span class="notranslate">{ID}</span> has been locked!', array ('ID' => $id))

~~~

<?=lang::translate('# Extract strings'); ?>

<?=lang::translate('This will extract all `lang::translate` calls, and add new values to a translate file.');?>

~~~.php
use diversen\translate\extractor;

// <?=lang::translate('same pattern as above for extraction of strings');?>

$e = new extractor();
$e->defaultLanguage ='en'; // which language will we extract to
$e->setDirsInsideDir('modules/');
$e->setSingleDir("vendor/diversen/simple-php-classes");
$e->updateLang();
~~~

<?=lang::translate('
> The <span class="notranslate">`$e->updateLang()`</span> call only add new strings found in the source, and remove
> strings that are removed from the source. It also knows if you have changed 
> the value of a translation key, and leave it as it is. It only updates the translation
> files, when a new key value is found.'); ?>

<?=lang::translate('# Auto translate using google translate API'); ?>

~~~.php

use diversen\translate\google;

// <?=lang::translate('same pattern as above for doing google auto translation.');?>

$t = new google();
$t->target = 'da'; // danish
$t->source = 'en';

$key = 'google api key';
$t->key = $key;
$t->setDirsInsideDir('modules/');
$t->setSingleDir("vendor/diversen/simple-php-classes");
$t->updateLang();
~~~

<?=lang::translate('The <span class="notranslate">`$e->updateLang()`</span> call only add new strings found in the source, 
and remove strings that are removed from the source. It also knows if you have changed
the value of a translation key, and leave it as it is. It only updates the translation
files, when a new key value is found.');?>

