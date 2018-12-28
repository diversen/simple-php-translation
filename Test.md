# Kort overblik
&quot;Simple-php-translation&quot; er en simpel løsning til at tilføje oversættelser til dine PHP-apps. Det gør det nemt at oversætte, udtrække oversættelsestrenge og gøre automatisk oversættelse via google translate API. Det er også nemt at tilføje dine filer til en oversættelsestjeneste som [transifex] (https://www.transifex.com/), når du indser, at du har brug for en menneskelig oversættelse.
Installere:
    composer require diversen/simple-php-translation

Bemærk: Eksempelet følger standardindstillingerne. Indstillingerne kan ændres. Men hvis du starter et nyt projekt, så kan du følge denne konvention for nem brug.
Oversættelser placeres i filer kaldet:
    lang/en/language.php
    lang/da/language.php

Fx inde i en blog
    blog/lang/en/language.php

Denne fil kan bestå af dette:
<span class="notranslate"></span>
~~~.php
$LANG = array ();
$LANG['Welcome to my blog'] = 'Welcome to my blog';
~~~
</span>

En dansk oversættelse findes i:
    blog/lang/da/language.php

Og denne fil kan bestå af:
~~~.php
    $LANG = array ();
    $LANG['Welcome to my blog'] = 'Velkommen til min blog';
~~~

# Indlæs sprog
~~~.php
use diversen\lang;

$l = new lang();

// Indstil dirs hvor vi leder efter sprogfiler inde i et sprog dir, fx:// modules/account
// modules/blog 

$l->setDirsInsideDir("modules/");

// Indstil en enkelt dir
$l->setSingleDir("vendor/diversen/simple-php-classes");

// Indlæs sprog. Fx dansk ('da')
// Dette vil indlæse alle `da` filer fra ovenstående mapper.

$l->loadLanguage('da');

// NT: Now all language files are loaded and we can translate~~~

# Oversætte
~~~.php

// enkel
echo lang::translate('Here is a text');

// with substitution and a <span class="notranslate">span</span> tag to indicate that a part of a string should not be translated

echo lang::translate('User with ID <span class="notranslate">{ID}</span> has been locked!', array ('ID' => $id))

~~~

# Uddrag strenge
Dette vil uddrage alle `lang :: translate` opkald, og tilføj nye værdier til en oversættelsesfil.
~~~.php
use diversen\translate\extractor;

// samme mønster som ovenfor til ekstraktion af strenge
$e = new extractor();
$e->defaultLanguage ='en'; // which language will we extract to
$e->setDirsInsideDir('modules/');
$e->setSingleDir("vendor/diversen/simple-php-classes");
$e->updateLang();
~~~

&gt; <span class="notranslate">`$e-&gt;updateLang()`</span> tilføjer kun nye strenge, der findes i kilden, og fjern&gt; strenge, der fjernes fra kilden. Det ved også, om du har ændret&gt; værdien af en oversættelsessnøgle, og lad den være som den er. Den opdaterer kun oversættelsen&gt; filer, når der er fundet en ny nøgleværdi.
# Auto oversætte ved hjælp af Google Translate API
~~~.php

use diversen\translate\google;

// samme mønster som ovenfor for at gøre google automatisk oversættelse.
$t = new google();
$t->target = 'da'; // danish
$t->source = 'en';

$key = 'google api key';
$t->key = $key;
$t->setDirsInsideDir('modules/');
$t->setSingleDir("vendor/diversen/simple-php-classes");
$t->updateLang();
~~~

<span class="notranslate">`$e-&gt;updateLang()`</span> tilføjer kun nye strenge, der findes i kilden, og fjern strenge, der fjernes fra kilden. Det ved også, om du har ændret værdien af en oversættelsessnøgle, og lad den være som den er. Det opdaterer kun oversættelsesfilerne, når der er fundet en ny nøgleværdi.
