<?php

namespace Diversen;

use Diversen\Translate\Extractor;

class Lang extends Extractor {


    /**
     * @var array $dict var holding the translation table
     */
    public static $dict = [];

    /**
     * Add 'NT:' before strings. 
     */
    public static $hide_nt = false;
    
    /**
     * Load a language
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
                    self::$dict = array_merge(self::$dict, $LANG);
                }
            }
        }
    }

    /**
     * Translatate a string
     *
     * @param   string  $sentence the sentence to translate.
     * @param   array   $substitute array with substitution to perform on sentence.
     * @param   array   $options array ('no_translate' => 1) set this and you string
     *                  will be returned as it is.  
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
            if (self::$hide_nt) {
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
