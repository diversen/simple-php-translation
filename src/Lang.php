<?php

namespace Diversen;

use Diversen\Translate\Extractor;

/**
 * File contains contains class creating simple translation
 *
 * @package    lang
 */

/**
 * Class for doing simple translations
 *
 * @package    lang
 */
class Lang extends Extractor {


    /**
     * var holding the translation table
     * @var array $dict
     */
    public static $dict = array ();
    



    public static $ignore = false;
    
    /**
     * loads all language files
     * @param string $language, e.g. 'en'
     */
    public function loadLanguage ($language) {
        if (!$language) {
            $language = 'en';
        }
        
        foreach($this->dirs as $dir) {
            $file = $dir . '/' . $this->translateDir . "/$language" . "/" . $this->translateFile;
            
            if (file_exists($file)) {
                include $file;
                if (!empty($LANG)) {      
                    self::$dict+=$LANG;
                }
            }
        }
    }



    /**
     * method for doing translations. If a translation is not found we
     * prepend the untranslated string with 'NT' (needs translation)
     *
     * @param   string  $sentence the sentence to translate.
     * @param   array   $substitute array with substitution to perform on sentence.
     *                  e.g. array ('100$', 'username')
     *                  in the string to be translated you will then have e.g.
     *                  $LANG['module_string'] = "You will be charged {AMOUNT} dear {USER_NAME}"
     * @param   array   $options array ('no_translate' => 1) set this and you string
     *                  will be returned as it is.  
     * @return  string  $str translated string
     *                  if no translation is found in translation registry,
     *                  the string suplied will have "NT: " prepended. 
     *                  (Not Translated)
     * 
     */
    public static function translate($sentence, $substitute = array(), $options = array ()){

        if (isset($options['no_translate'])) {
            return $sentence;
        }
        
        if (isset(self::$dict[$sentence])){
            $translated_sentence=self::$dict[$sentence];
            
            if (!empty($substitute)){
                foreach ($substitute as $key => $val) {
                    $translated_sentence = str_replace('{'.$key.'}', $val, $translated_sentence);
                }
            }
            return $translated_sentence;
        } else {
            if (!empty($substitute)){
                foreach ($substitute as $key => $val) {
                    $sentence = str_replace('{'.$key.'}', $val, $sentence);
                }
            }
            // don't add NT
            if (self::$ignore) {
                return $sentence;
            } else {
                return "NT: $sentence";
            }
        }
    } 

    /**
     * Shortcut of Lang::translate
     */
    public static function t($sentence, $substitute = array(), $options = array ()){
        return self::translate($sentence, $substitute, $options);
    }
}
