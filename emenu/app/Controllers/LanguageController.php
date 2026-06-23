<?php
class LanguageController extends Controller {
    
    // Switch the active language
    public function switch($lang = 'id') {
        // Validate against allowed languages
        $allowed_langs = ['id', 'en', 'vi'];
        
        if (in_array($lang, $allowed_langs)) {
            $_SESSION['lang'] = $lang;
        }

        // Redirect user back to the previous page
        $previous_url = $_SERVER['HTTP_REFERER'] ?? BASEURL;
        header('Location: ' . $previous_url);
        exit;
    }
}