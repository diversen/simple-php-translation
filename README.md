# Brief Overview

The `simple-php-translation` is a simple solution for adding 
translations to your PHP apps.

Install: 

    composer require diversen/simple-php-translation

If you want to use google auto translate, require the following:

    composer require google/cloud-translate

Translations are placed in files named:

    lang/en/language.php
    lang/da/language.php

E.g. inside your app `test_app`

    test_app/lang/en/language.php

The `language.php` file could consist of this:

~~~.php
$LANG = array ();
$LANG['Welcome to my blog'] = 'Welcome to my blog';
~~~

A Danish translation could be found in: 

    test_app/lang/da/language.php

And this `language.php` could consist of: 

~~~.php
    $LANG = array ();
    $LANG['Welcome to my blog'] = 'Velkommen til min blog';
~~~

# Load language

~~~.php
use Diversen\Lang;

$l = new Lang();

// Most often all translations are placed in a single folder
$l->setSingleDir("test_app");

// But you can also set dirs, and look for language files inside multiple language dirs:
// $l->setDirsInsideDir("modules/");

// load language. E.g. danish ('da')
// $l->loadLanguage('da');

// Or english
// e.g. test_app/lang/da/language.php
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

This will extract all `Lang::translate` calls, and add new values to translation files. 

~~~.php
use Diversen\Translate\Extractor;

// same pattern as above for extraction

$e = new Extractor();
$e->defaultLanguage ='en'; // which language will we extract to

// Most often you will just use a single dir. Like this
$e->setSingleDir("test_app");

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

Then require the composer package `google/cloud-translate`

    composer require google/cloud-translate

Remember to export the GOOGLE_APPLICATION_CREDENTIALS or you will not be able to run the script. 

~~~.php

// same pattern as above for google auto translation.
use Diversen\Translate\GoogleTranslate;

include_once "vendor/autoload.php";

// Google translator needs this. Substitue with path to your own .json file  
putenv("GOOGLE_APPLICATION_CREDENTIALS=config-locale/pebble-2c949028ebcc.json");

$t = new GoogleTranslate();
$t->target = 'da'; // danish
$t->source = 'en';

$t->setSingleDir("app");

// Or set multiple dirs like this:
// This will create translation folders in e.g. modules/blog, modules/account
// $e->setDirsInsideDir('modules/');

$t->updateLang();

~~~

# Demo 

Inside the [test_app/](test_app/) directory, there is a small php app consisting of 
one PHP file: [test_app/index.php](test_app/index.php). There is also included javascript
in this file. The javascript `Lang.translate` method will also be extracted when using
the `Extractor` class. 

The Javascript file [test_app/js/lang.js](test_app/js/lang.js) will do the translation. 

Start the app:

    php -S localhost:8000 -t test_app

Visit http://localhost:8000

You can also test the danish translation at: 

http://localhost:8000?lang=da

I there is no translations then any string will get the "NT: " (Not Translated) prefix. 

You can test this by removing the `en` language file directory:

    rm -rf test_app/lang/en

Extract the english translation `en` using the script
[test/extract.php](test/extract.php).

    php test/extract.php

Reload the browser. 

Now all translation are loaded from a file and the prefix 'NT: ' is 
removed. 

There is also a small script for translating into danish (`da`). 
[test/google_translate.php](test/google_translate.php)

In order to use this script, you will need to setup a `google service account`. 
Then change this part of the script: 

    putenv("GOOGLE_APPLICATION_CREDENTIALS=google_json/pebble-2c949028ebcc.json");

To: 

    putenv("GOOGLE_APPLICATION_CREDENTIALS=path/to/service-account-your-key.json");

Now you can run: 

    php test/google_translate.php

The translation will look like this:
[test_app/lang/da/language.php](test_app/lang/da/language.php)

Finally there is [test/to_js.php](test/to_js.php) script which translates into 
js module exports, which then can be loaded and translated by the EMS module
[test_app/js/lang.js](test_app/js/lang.js)

# License

MIT © [Dennis Iversen](https://github.com/diversen)


