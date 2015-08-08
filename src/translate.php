<?php

namespace diversen;
use diversen\translate\extractor;

class translate {

    private $dict = array();

    /**
     * loads a translation file into dict
     * @param string $file
     */
    public function load($file) {
        if (file_exists($file)) {
            include $file;
            $this->dict+= $LANG;
        }
    }

    /**
     * translate a sentence according to loaded dict
     * @param string $sentence
     * @param array $substitute
     * @return string
     */
    public function translate($sentence, $substitute = array()) {
        if (isset($this->dict[$sentence])) {
            
            $translated = $this->dict[$sentence];
            $translated = $this->substitute($translated, $substitute);
            return $translated;
        }
        return $sentence;
    }
    
    /**
     * substitues a string accoring to substitute
     * @param string $sentence
     * @param array $substitute
     * @return string $sentence
     */
    private function substitute($sentence, $substitute) {
        if (!empty($substitute)) {
            foreach ($substitute as $key => $val) {
                $sentence = str_replace('{' . $key . '}', $val, $sentence);
            }
        }
        return $sentence;
    }
}
