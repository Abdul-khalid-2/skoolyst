<?php
// app/Helpers/ContentHelper.php

namespace App\Helpers;

class ContentHelper
{
    // public static function cleanContent($content)
    // {
    //     if (empty($content)) {
    //         return $content;
    //     }

    //     // Remove contenteditable and other editing attributes
    //     $content = preg_replace('/\s+contenteditable\s*=\s*["\'][^"\']*["\']/i', '', $content);
    //     $content = preg_replace('/\s+data-placeholder\s*=\s*["\'][^"\']*["\']/i', '', $content);
    //     $content = preg_replace('/\s+spellcheck\s*=\s*["\'][^"\']*["\']/i', '', $content);
    //     $content = preg_replace('/\s+aria-label\s*=\s*["\'][^"\']*["\']/i', '', $content);

    //     // Remove Grammarly and other editor-specific attributes
    //     $content = preg_replace('/\s+data-gramm\s*=\s*["\'][^"\']*["\']/i', '', $content);
    //     $content = preg_replace('/\s+data-new-gr-c-s-check-loaded\s*=\s*["\'][^"\']*["\']/i', '', $content);
    //     $content = preg_replace('/\s+gramm\s*=\s*["\'][^"\']*["\']/i', '', $content);

    //     return trim($content);
    // }
}
