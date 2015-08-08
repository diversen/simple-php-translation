# Brief Overview

The `simple-php-translation` is a simple solution for adding 
translations to your PHP apps. It makes it easy to translate,
extract translation strings, and to do auto-translation through 
google translate API.

Install: 

    composer require diversen/simple-php-translation

> Note: Example follows default settings. Settings can be changed. But if
> You are starting a new project, then you could follow this convention
> for ease of use. 

Translations are placed in files called:

    lang/en/language.php
    lang/da/language.php

E.g. inside a blog

    blog/lang/en/language.php

This file could consist of this:

~~~.php
$LANG = array ();
$LANG['Welcome to my blog'] = 'Welcome to my blog';
~~~

A Danish translation should then be found in: 

    blog/lang/da/language.php

And this file could consists of: 

~~~.php
    $LANG = array ();
    $LANG['Welcome to my blog'] = 'Velkommen til min blog';
~~~

# Load language

~~~.php
use diversen\lang;

$l = new lang();

// Set dirs where we look for language files inside lang dir, e.g.: 
// modules/account
// modules/blog
// and etc. 

$l->setDirsInsideDir("modules/*");

// Set another dir
// templates/main
// templates/sub

$l->setDirsInsideDir("htdocs/templates/*");

// Set a single dir

$l->setSingleDir("vendor/diversen/simple-php-classes");

// load language. E.g. danish ('da')
// Will load all 'da' files from above dirs.

$l->loadLanguage('da);

// now all language files are loaded, and we can translate
~~~

# Translate

~~~.php

// simple

echo lang::translate('Here is a text');

// with substitution and a span to indicate that a part of a string should not be translated

echo lang::translate('User with ID <span class="notranslate">{ID}</span> has been locked!', array ('ID' => $id))

~~~

# Extract strings 

This will extract all `lang::translate` calls, and add the values to a translate file. 

~~~.php
use diversen\translate\extractor;

// same pattern as above for extraction

$e = new extractor();
$e->defaultLanguage ='en'; // which language will we extract to
$e->setDirsInsideDir('modules/*');
$e->setDirsInsideDir('htdocs/templates/*');
$e->setSingleDir("vendor/diversen/simple-php-classes");
$e->updateLang();
~~~

# Auto translate translations using google

~~~.php

use diversen\translate\google;

// same pattern as above for google auto translation.

$t = new google();
$t->target = 'da'; // danish
$t->source = 'en';

$key = 'google api key';
$t->key = $key;
$t->setDirsInsideDir('modules/*');
$t->setDirsInsideDir('/htdocs/templates/*');  
$t->setSingleDir("vendor/diversen/simple-php-classes");
$t->updateLang();

> The `$e->updateLang()` call is clever enough to only add new strings, and remove
> strings that are removed from the source.  

~~~
