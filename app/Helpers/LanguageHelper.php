<?php
class LanguageHelper {
    private static $translations = [];
    private static $currentLang = 'vi';
    
    public static function init() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Get language from session or default to Vietnamese
        self::$currentLang = $_SESSION['language'] ?? 'vi';
        
        // Load translations
        self::loadTranslations(self::$currentLang);
    }
    
    public static function setLanguage($lang) {
        if (in_array($lang, ['en', 'vi'])) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['language'] = $lang;
            self::$currentLang = $lang;
            self::loadTranslations($lang);
        }
    }
    
    public static function getCurrentLanguage() {
        return self::$currentLang;
    }
    
    private static function loadTranslations($lang) {
        $filePath = __DIR__ . '/../Languages/' . $lang . '.php';
        if (file_exists($filePath)) {
            self::$translations = require $filePath;
        }
    }
    
    public static function translate($key, $params = []) {
        $keys = explode('.', $key);
        $value = self::$translations;
        
        foreach ($keys as $k) {
            if (isset($value[$k])) {
                $value = $value[$k];
            } else {
                return $key; // Return key if translation not found
            }
        }
        
        // Replace parameters
        foreach ($params as $param => $val) {
            $value = str_replace(':' . $param, $val, $value);
        }
        
        return $value;
    }
    
    // Shorthand function
    public static function t($key, $params = []) {
        return self::translate($key, $params);
    }
}

// Initialize language on load
LanguageHelper::init();
