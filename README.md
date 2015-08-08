# Load translations

> Note: Example follows default settings: 

Translation are placed in files called `language.php`. 

E.g. inside a blog

    blog/lang/en/language.php

This file could consist of this:

~~~.php
$LANG = array ();
$LANG['Welcome to my blog'] = 'Welcome to my blog';
~~~

A Danish translation could be found in: 

    blog/lang/da/language.php

And this file could consists of 

~~~.php
    $LANG = array ();
    $LANG['Welcome to my blog'] = 'Velkommen til min blog';
~~~


~~~.php
use diversen\lang;

$l = new lang();

// Look for language files inside, e.g.: 
// modules/account/lang
// modules/blog/lang
// and etc. 
$l->setDirsInsideDir("modules/*");

// Look for language files inside, e.g.: 
// templates/main/lang/
// templates/sub/lang/
$l->setDirsInsideDir("htdocs/templates/*");

// Set a single dir
$l->setSingleDir("vendor/diversen/simple-php-classes");

// load language. e.g. danish ('da')

$l->loadLanguage('da);

// now all language files are loaded, and we can translate
~~~

# Translate

~~~.php

use diversen\lang;

// simple
echo lang::translate('Here is a text');

// with substitution and a span to indicate that a part of a string does not have to be translated
echo lang::translate('User with ID <span class="notranslate">{ID}</span> has been locked!', array ('ID' => $id))

~~~

# Extract strings 

Will can extract all `lang::translate` calls, and add the values to a translate file. 

~~~.php
use diversen\translate\extractor;

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

$t = new google();
$t->target = 'da'; // danish
$t->source = 'en';

$key = 'google api key';
$t->key = $key;
$t->setDirsInsideDir('modules/*');
$t->setDirsInsideDir('/htdocs/templates/*');  
$t->setSingleDir("vendor/diversen/simple-php-classes");
$t->updateLang();
~~~
