<?php

namespace Diversen\Translate;

class Extractor
{

    var $dirs = [];
    
    /** 
     * File where translation strings will be placed 
     */
    var $translateFile = 'language.php';

    /** 
     * translation dir where translation files will be placed 
     * */
    var $translateDir = 'lang';

    /** 
     * name of var inside translateFile LANG 
     */
    var $translateAryName = 'LANG';

    /** 
     * Method names to search for 
     */
    var $extractMethodName = ['Lang::translate', 'Lang.translate', 'translate'];

    /** 
     * default language. This is default language we extract from 
     */
    var $defaultLanguage = 'en';

    /**
     * attach all dirs inside a dir to $this->dirs
     * e.g. modules/* will set the following dirs
     * /modules/account
     * /modules/blog
     * @param string $path modules/*
     */
    public function setDirsInsideDir($path)
    {
        $dirs = self::getDirsGlob($path);
        $this->addDirs($dirs);
    }

    /**
     * add some dirs
     * @param array $dirs
     */
    private function addDirs($dirs)
    {
        foreach ($dirs as $dir) {
            $this->dirs[] = $dir;
        }
    }

    /**
     * set a single path for e.g. a vendor lib
     * @param string $path
     */
    public function setSingleDir($path)
    {
        $this->dirs[] = $path;
    }

    /**
     * Search for a single translation method name
     */
    public function searchSingleExtractMethod ($str, $method_name) {

        // search for strings inside ''
        $search = "/$method_name\('([^']+)'/s";
        
        preg_match_all($search, $str, $out);
        $strings = $out[1];
        $strings = array_unique($strings);

        // search for strings inside ""
        $search = '/' . $method_name . '\("([^"]+)"/s';

        preg_match_all($search, $str, $out2);
        $strings2 = $out2[1];
        $strings = array_merge($strings, $strings2);
        return $strings;
    }

    /**
     * Search for translation method names
     */
    public function search($str = '')
    {
        $strings = [];

        foreach($this->extractMethodName as $method_name) {
            $strings_extracted = $this->searchSingleExtractMethod($str, $method_name);
            $strings = array_merge($strings, $strings_extracted);
        }
        
        return $strings;
        
    }

    /**
     * get a line of the translation file with correct quotes
     * e.g $LANG["This is many single quots'"] = "This '"
     * @param string $key
     * @param string $value
     * @return string $str
     */
    public function setCorrectQuotes($key, $value = '')
    {

        $str = '';
        if (empty($value)) {
            $value = $key;
        }

        // search for apostrophe (') somewhere 
        // in order to know how to write out the array
        $apo_key = strpos($key, "'");
        if ($apo_key) {
            $str .= "\$" . $this->translateAryName . '["' . $key . '"] = ';
        } else {
            $str .= "\$" . $this->translateAryName . "['$key'] = ";
        }

        // and also for the value
        $apo_val = strpos($value, "'");
        if ($apo_val) {
            $str .= '"' . $value . '";' . "\n";
        } else {
            $str .= "'$value';" . "\n";
        }
        return $str;
    }

    /**
     * check if prim mime type is text
     * @param string $file
     * @return boolean $res true if text else false
     */
    private function isText($file)
    {
        $prim_mime = self::getPrimMime($file);
        if ($prim_mime == 'text') {
            return true;
        }
        return false;
    }

    /**
     * get language dir name to be used in source dir
     * @param string $dir
     * @return string $dir
     */
    public function getLanguageDirFromDir($dir)
    {
        return
            $dir . '/' .
            $this->translateDir . '/' .
            $this->defaultLanguage;
    }

    /**
     * get language file name from dir
     * @param string $dir
     * @return string $file
     */
    public function getLanguageFileFromDir($dir)
    {
        return
            $this->getLanguageDirFromDir($dir) . '/' .
            $this->translateFile;
    }

    /**
     * create translation file
     * @param string $dir
     * @param string $str translation string
     */
    public function createFile($dir, $str)
    {

        $save_dir = $this->getLanguageDirFromDir($dir);
        if (!file_exists($save_dir)) {
            $res = mkdir($save_dir, 0755, true);
            if (!$res) {
                echo "Could not make dir $dir with permission 0755";
                return false;
            }
        }
        $file = $this->getLanguageFileFromDir($dir);
        file_put_contents($file, $str);
    }

    /**
     * Method for getting a file list recursive
     * @param string $start_dir the directory where we start
     * @param string $pattern a given fnmatch() pattern
     * return array $ary an array with the files found. 
     */
    public static function getFileListRecursive($start_dir, $pattern = null)
    {
        $files = array();
        if (is_dir($start_dir)) {
            $fh = opendir($start_dir);
            while (($file = readdir($fh)) !== false) {
                // skip hidden files and dirs and recursing if necessary
                if (strpos($file, '.') === 0) {
                    continue;
                }
                $filepath = $start_dir . '/' . $file;
                if (is_dir($filepath)) {
                    $files = array_merge($files, self::getFileListRecursive($filepath, $pattern));
                } else {
                    if (isset($pattern)) {
                        if (fnmatch($pattern, $filepath)) {
                            array_push($files, $filepath);
                        }
                    } else {
                        array_push($files, $filepath);
                    }
                }
            }
            closedir($fh);
        } else {
            // false if the function was called with an invalid non-directory argument
            $files = false;
        }
        return $files;
    }

    /**
     * get dirs in path using DirectoryIterator method
     * @param string $path
     * @param array $options you can set a basename which has to be correct
     *              'basename' => '/path/to/exists'
     * @return array $dirs 
     */
    public static function getDirsGlob($path, $options = array())
    {
        if (file_exists($path)) {
            $it = new \DirectoryIterator($path);
        } else {
            error_log("$path does not exists in " . __FILE__ . ": " . __LINE__);
            return array();
        }

        $dirs = array();
        foreach ($it as $file) {
            if ($file->isDir() && !$file->isDot()) {
                $dirs[] = $it->getPathname();
            }
        }
        if (isset($options['basename'])) {
            foreach ($dirs as $key => $dir) {
                $dirs[$key] = basename($dir);
            }
        }
        return $dirs;
    }

    /**
     * Method for getting mime type of a file
     * @param string $path
     * @return string $mime_type 
     */
    public static function getMime($path)
    {
        if (!function_exists('mime_content_type')) {
            throw new \Exception('function mime_content_type. Please intall the extension');
        }
        $result = false;
        if (is_file($path) === true) {
            $result = preg_replace('~^(.+);.*$~', '$1', mime_content_type($path));
        }

        return $result;
    }
    /**
     * Return the prim mime type of a file, e.g. 'image'
     * @param string $file
     * @return string $mime
     */
    public static function getPrimMime($file)
    {
        $str = self::getMime($file);
        $ary = explode('/', $str);
        return $ary[0];
    }

    /**
     * generates a PHP string with extracted translation
     * @param string $dir
     * @return string $translation_str
     */
    public function createLangStrForPath($dir)
    {
        $file_list = self::getFileListRecursive($dir);
        asort($file_list);

        // compose a php file
        $translation_str = "<?php\n\n";
        $translation_str .= "$" . $this->translateAryName . " = array();\n\n";

        foreach ($file_list as $file) {
            if (!$this->isText($file)) {
                continue;
            }

            $file_str = file_get_contents($file);
            $strings = $this->search($file_str);

            // no strings we continue
            if (empty($strings)) {
                continue;
            }

            $translation_str .= "\n// Translation of file $file\n\n";

            // and we add all strings in that file
            foreach ($strings as $trans) {
                $translation_str .= $this->setCorrectQuotes($trans);
            }
        }
        return $translation_str;
    }

    /**
     * Updates a PHP string with extracted translation
     * Updating means that any changes to the values of 
     * e.g. $LANG['My sentence'] will not be changed
     * @param string $dir
     * @return string $translation_str
     */
    public function updateLangStrForPath($dir)
    {
        $file_list = self::getFileListRecursive($dir);

        asort($file_list);

        // compose a php file
        $translation_str = "<?php\n\n";
        $translation_str .= "$" . $this->translateAryName . " = array();\n";

        // if lang file does not exists
        $lang_file = $this->getLanguageFileFromDir($dir);
        if (!file_exists($lang_file)) {
            return $this->createLangStrForPath($dir);
        }

        include $lang_file;

        $original_ary = ${$this->translateAryName};
        if (empty($original_ary)) {
            return $this->createLangStrForPath($dir);
        }

        foreach ($file_list as $file) {
            if (!$this->isText($file)) {
                continue;
            }

            $file_str = file_get_contents($file);
            $strings = $this->search($file_str);

            // no strings we continue
            if (empty($strings)) {
                continue;
            }

            $translation_str .= "\n// Translation of file $file\n\n";

            // and we add all strings
            foreach ($strings as $trans) {
                if (!isset($original_ary[$trans])) {
                    // new value
                    $translation_str .= $this->setCorrectQuotes($trans);
                } else {
                    // keep old value
                    $old_value = $original_ary[$trans];
                    $translation_str .= $this->setCorrectQuotes($trans, $old_value);
                }
            }
        }
        return $translation_str;
    }


    /**
     * generate a language file in a language dir
     * will overwrite previous translations
     * e.g.:
     * modules/account/lang/en/language.php
     * 
     * @param type $lang
     */
    public function generateLang()
    {
        foreach ($this->dirs as $dir) {
            $str = $this->createLangStrForPath($dir);
            $this->createFile($dir, $str);
        }
    }

    /**
     * generate a language file in a language dir
     * will overwrite previous translations
     * e.g.:
     * modules/account/lang/en/language.php
     * 
     * @param type $lang
     */
    public function updateLang()
    {
        foreach ($this->dirs as $dir) {
            $str = $this->updateLangStrForPath($dir);
            $this->createFile($dir, $str);
        }
    }
}
