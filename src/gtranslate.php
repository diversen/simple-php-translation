<?php

namespace diversen;
/**
 * File contains a simple class for doing translations with google 
 * translate API
 * @package gtranslate
 */

/**
 * class for doing translation with the goolge API
 * @package gtranslate
 */
class gtranslate {
    
    /**
     * var holding options
     * @var array $options
     */
    public $options;

    /**
     * Gtranslate URL
     * @var string $Gtranslate_URL
     */
    public $apiEndpoint = 'https://www.googleapis.com/language/translate/v2';

    /**
     * set options for translation 
     * @param array $options e.g:  
     *              array('key' => 'your api key', 
     *                    'target' => 'da',
     *                    'source' => 'en')
     */
    public function setOptions ($options) {
        $this->options = $options;
    }

    /**
     * method for getting all languages supported by the API
     * @return array $lang array of languages
     */
    public function getSupportLangs (){
        $url = $this->apiEndpoint . "/languages?";
        $url.= "key=" . $this->options['key'] . "&";
        $url.= "target=" . $this->options['target'];
        $str = file_get_contents($url);
        $ary = json_decode($str, true);
        return $ary;
    }

    /**
     * merthod for translating a string
     * @param string $str string to be translated
     * @return string $str the translated string
     */
    public function translate ($str) {
        $url = $this->apiEndpoint . "?";
        $url.= "key=" . $this->options['key'] . "&";
        $url.= "target=" . $this->options['target'] . "&";
        $url.= "source=" . $this->options['source'] . "&";
        $url.= "q=" . urlencode($str);
        $str = file_get_contents($url);
        $ary = json_decode($str, true);
        if (isset($ary['data']['translations'][0]['translatedText'])){
            $text = $ary['data']['translations'][0]['translatedText'];
            return urldecode($text);
        }
        return null;
    }
}
