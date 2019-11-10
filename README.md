# Brief Overview

The `simple-php-translation` is a simple solution for adding 
translations to your PHP apps. It makes it easy to translate,
extract translation strings, and to do auto-translation through 
google translate API. It is also easy to add your files to a 
translation service like [transifex](https://www.transifex.com/)
when you realise, that you need a human translation. 

Install: 

    composer require diversen/simple-php-translation

> Note: Example follows default settings. Settings can be changed. But if
> You are starting up a new project, then you could follow this convention
> for ease of use. 

Translations are placed in files called:

    lang/en/language.php
    lang/da/language.php

E.g. inside your app

    app/lang/en/language.php

The `en/language.php` file could consist of this:

~~~.php
$LANG = array ();
$LANG['Welcome to my blog'] = 'Welcome to my blog';
~~~

A Danish translation will could be found in: 

    app/lang/da/language.php

And this file could consists of: 

~~~.php
    $LANG = array ();
    $LANG['Welcome to my blog'] = 'Velkommen til min blog';
~~~

# Load language

~~~.php
use Diversen\Lang;

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

// now all language files are loaded, and we can translate
~~~

# Translate

~~~.php

// simple

use Diversen\Lang;

echo Lang::translate('Here is a text');

// with substitution and a span to indicate that a part of a string should not be translated

echo Lang::translate('User with ID <span class="notranslate">{ID}</span> has been locked!', array ('ID' => $id))

~~~

# Extract strings 

This will extract all `Lang::translate` calls, and add new values to a translate file. 

~~~.php
use Diversen\Translate\Extractor;

// same pattern as above for extraction

$e = new Extractor();
$e->defaultLanguage ='en'; // which language will we extract to

// Most often you will just use a single dir. Like this
$e->setSingleDir("app");

// Set multiple dirs, like this:
// This will create translation folders in e.g. modules/blog, modules/account
// $e->setDirsInsideDir('modules/');

$e->updateLang();
~~~

> The `$e->updateLang()` call only add new strings found in the source, and remove
> strings that are removed from the source. It also knows if you have changed 
> the value of a translation key, then it will leave the value as it is. 
> It only updates the translation files, when a new key value is found.

# Auto translate using google translate API

You will need to setup google cloud translation: 

[https://cloud.google.com/translate/docs/basic/setup-basic](https://cloud.google.com/translate/docs/basic/setup-basic)

You will need to enable the composer package `google/cloud-translate`

    composer require google/cloud-translate

Remember to export the GOOGLE_APPLICATION_CREDENTIALS or you will not be able to run the script. 

~~~.php


// same pattern as above for google auto translation.
use Diversen\Translate\GoogleTranslate;

include_once "vendor/autoload.php";

$t = new GoogleTranslate();
$t->target = 'da'; // danish
$t->source = 'en';

$t->setSingleDir("app");

// Or set multiple dirs like this:
// This will create translation folders in e.g. modules/blog, modules/account
// $e->setDirsInsideDir('modules/');

$t->updateLang();

~~~

> The `$e->updateLang()` call only add new strings found in the source, and remove
> strings that are removed from the source. It also knows if you have changed 
> the value of a translation key, and leave it as it is. It only updates the translation
> files, when a new key value is found.
