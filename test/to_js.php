<?php

$languages = ['en', 'da'];

function lang_export($json)
{
    $js = "const Translation = \n\n";
    $js.= $json . "\n\n";
    $js.= "export {Translation}\n";
    return $js;
}

foreach($languages as $language) {
    $file = "test_app/lang/$language/language.php";
    include $file;
    $js = lang_export(json_encode($LANG));

    $js_lang_path = "test_app/js/lang/$language";
    if (!file_exists($js_lang_path)) {
        mkdir($js_lang_path, 0777, true);
    }

    file_put_contents("$js_lang_path/language.js", $js);
}
