<?php

namespace Diversen\Translate;

use Diversen\Translate\Extractor;
use Google\Cloud\Translate\V2\TranslateClient;

class GoogleTranslate extends Extractor {
    
    /** source language. Defaults to 'en' */
    public $source = 'en';
    /** target language. Defaults to 'da' */
    public $target = 'da';
    /** google api key */
    public $key ='';
    /** echo som messages about the translation */
    public $debug = 1;
    
    /**
     * update a translation
     */
    public function updateLang () {

        foreach ($this->dirs as $dir) {
            $this->translate($dir);
        }
    }
    
    /**
     * get source file
     * @param string $dir
     * @return string $source_file
     */
    public function getSourceFile($dir) {
        return 
                $dir . '/' . 
                $this->translateDir . "/" . 
                $this->source . '/' . 
                $this->translateFile;
    }
    
    /**
     * get target file
     * @param string $dir
     * @return string $target_file 
     */
    public function getTargetFile($dir) {
        return 
                $this->getTargetDir($dir) . "/" .
                $this->translateFile;
    }
    
    /**
     * get target dir
     * @param string $dir
     * @return string $target_dir
     */
    public function getTargetDir($dir) {
        return 
                $dir . '/' . 
                $this->translateDir . "/" . 
                $this->target;
    }
    
    /**
     * translate a dir
     * @param string $dir
     * @return boolean $res
     */
    public function translate ($dir) {
        $source = $this->getSourceFile($dir);
        if (!file_exists($source)) {
            error_log("Source file $source does not exists. We skip. ");
            return false;
        }
        
        // targt file
        $target = $this->getTargetFile($dir);
        
        // compose source array
        include $source;
        $source_ary = ${$this->translateAryName};
        
        // compose target array
        if (file_exists($target)) {
            include $target;
            $target_ary = ${$this->translateAryName};            
        } else {
            $target_ary = array ();
        }
        
        // get google translate object
        $translate = new TranslateClient();

        // compose a php file
        $translation_str = "<?php\n\n";
        $translation_str.= "$" . $this->translateAryName . " = array();\n\n";
        
        foreach ($source_ary as $key => $text) {
            if (isset($target_ary[$key])) {
                $translation_str.=$this->setCorrectQuotes($key, $target_ary[$key]);
            } else {
                
                // $translated = $t->translate($val);
                $translated_ary = $translate->translate($text, [
                    'target' => $this->target,
                    'source' => $this->source,
                ]);

                $translated = $translated_ary['text'];
                
                $this->debug("Translation:\n");
                $this->debug("$text\n");
                $this->debug("$translated\n\n");
                
                $translation_str.= $this->setCorrectQuotes($key, $translated);
                usleep(1000);
            }    
        }
        
        $this->createFile($dir, $translation_str);
    }
    
    /**
     * create translation file
     * @param string $dir
     * @param string $str translation string
     */
    public function createFile ($dir, $str) {
        
        $save_dir = $this->getTargetDir($dir);
        if (!file_exists($save_dir)) {
            $res = mkdir($save_dir, 0755, true);
            if (!$res) {
                echo "Could not make dir $dir with permission 0755";
                return false;
            }
        }
        $file = $this->getTargetFile($dir);
        file_put_contents($file, $str);
    }
    
    /**
     * simple debug. Write message to screen
     * @param string $mes
     */
    public function debug ($mes) {
        echo $mes;
    }
}
