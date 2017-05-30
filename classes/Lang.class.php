<?php
/******************************************************************************/
// Created by: shlomo hassid.
// Release Version : 1.1
// Creation Date: 12/11/2015
// Copyright 2015, shlomo hassid.
/******************************************************************************/

/*****************************      DEPENDENCE      ***************************/
//Trace.class.php
// NONE
/******************************************************************************/

/**
 * Description of Lang
 *
 * @author shlomi
 */
class Lang {
    
    private static $dic;
    private static $hooks;
    private static $lang;
    private static $code;
    private static $dir;
    
    /** Load Dictionary
     * 
     * @param Array $load - Dictionary array 
     * 
     */
    public static function load( $load )
    {   
        Trace::add_trace('construct class',__METHOD__);
        self::$dic = (isset($load['dic']))?$load['dic']:array();
        self::$hooks = (isset($load['js']))?$load['js']:array();
        self::$lang = (isset($load['lang']))?$load['lang']:"";
        self::$code = (isset($load['code']))?$load['code']:"en";
        self::$dir = (isset($load['dir']))?$load['dir']:"ltr";
    }
    
    /** Get current loaded language:
     * 
     * @return String
     * 
     */
    public static function get_lang() {
        return self::$lang;
    }
    
    /** Get current loaded language code:
     * 
     * @return String
     * 
     */
    public static function get_langCode() {
        return self::$code;
    }
    
     /** Get current loaded language code:
     * 
     * @return String
     * 
     */
    public static function get_langDir() {
        return self::$dir;
    }
    
    /** Print a stored sentence
     * 
     * @param String $key
     * @param Boolean $out
     * @param Array $fetch
     * @return String
     */
    public static function P($key, $out = true, $fetch = array()) {
        $print = (isset(self::$dic[$key]))?self::$dic[$key]:'';
        if ($print !== '') {
            self::fetch($print,$fetch);
        }
        if ($out) { echo $print; }
        return $print;
    }
    /** Fetch custom values into string template:
     * 
     * @param string $print
     * @param array $fetch
     * @return void
     */
    private static function fetch(&$print, $fetch) {
        if (!is_array($fetch) || count($fetch) < 1) { return; }
        foreach ($fetch as $key => $value) {
            $print = str_replace("{".($key+1)."}", $value, $print);
        }
    }
    /** Parse hooks for js:
     * 
     * @param string $lib
     * @return null | string
     */
    public static function lang_hook_js($lib) {
        if (isset(self::$hooks[$lib])) {
            $ret= array();
            foreach (self::$hooks[$lib] as $key => $sen) {
                $ret[] = $key.':"'.addslashes($sen).'"';
            }
            return implode(",",$ret);
        }
        return null;
    }
}