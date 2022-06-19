<?php

require "../vendor/autoload.php";

use Diversen\Lang;

$l = new Lang();

// Load translation from this dir
$l->setSingleDir(__DIR__);
$l->loadLanguage('en');

?>

<h1><?=Lang::translate('Welcome to <span class="notranslate">simple php translation</span>');?></h1>
<p><?=Lang::translate("The easiest way to translate PHP apps")?></p>
<p><?=Lang::translate('I think your name is: <span class="notranslate">{name}</span>', ['name' => 'John Doe'])?>

<p id="message"></p>


<script type="module">

    import {Lang} from '/js/lang.js';
    await Lang.load();

    let translated_str = Lang.translate('Activity this week: <span class="notranslate">{week_user_total}</span>', {'week_user_total': 100});
    let message = document.getElementById('message');
    message.innerHTML = translated_str;
    
</script>