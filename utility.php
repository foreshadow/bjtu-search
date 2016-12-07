<?php

class Utility {
    
    static function highlight($subject, $keywords, $ellipsis = false) {
        $min = false;
        foreach ($keywords as $word) {
            if ($pos = mb_stripos($subject, $word)) {
                if ($min === false) {
                    $min = $pos;
                } else {
                    $min = min($min, $pos);
                }
            }
            $subject = preg_replace('/' . $word . '/i', '<span class="highlight">$0</span>', $subject);
        }
        $limit = 16;
        if (isset($ellipsis) && $ellipsis && $min !== false && $min > $limit) {
            $subject = '…' . mb_substr($subject, $min - $limit);
        }
        return $subject;
    }
    
    static function millis() {
        list($usec, $sec) = explode(' ', microtime());
        return round(((float) $sec + (float) $usec) * 1000);
    }
}
