<?php

namespace EcommerceTest\Objects;

/**
 * Class that contains general method used in most part of the app
 */
class General{

    /**
     * custom HTML back link
     */
    public static function backLink(string $link, string $image, string $message): array{
        return <<<HTML
<div id="indietro">
    <a href="{$link}"><img src="{$image}" alt="indietro" title="indietro"></a>
    <a href="{}$link">{$message}</a>
</div>
HTML;
    }

        /**
     * Create an HTML page
     * @param string $title page title
     * @param string $body the content inside the body tag
     * @param string $styleTag (optional) <style> tag content
     * @param array $styles (optional) an array that contains css files
     * @param array $sripts (optional) an array that contains js files
     * @return string the HTML page content
     */
    public static function genericHtml(string $title, string $body, string $styleTag = "",array $styles = [], array $scripts = []):string{
        $stylesS = "";
        $scriptsS = "";
        $styles_map = array_map(function($style){
            return '<link rel="stylesheet" href="'.$style['href'].'">';
        },$styles);
        $scripts_map = array_map(function($script){
            if(isset($script['type'])) $type = 'type="'.$script['type'].'"';
            else $type = "";
            return '<script '.$type.' src="'.$script['src'].'"></script>';
        },$scripts);
        if(!empty($styles_map)){
            foreach($styles_map as $style) $stylesS .= $style;
        }
        if(!empty($scripts_map)){
            foreach($scripts_map as $script) $scriptsS .= $script;
        }
        return <<<HTML
<!DOCTYPE html>
<html>
    <head>
        <title>{$title}</title>
        <meta charset="utf-8">
        {$stylesS}
        {$scriptsS}
        <style>
            {$styleTag}
        </style>
    </head>
    <body>
        {$body}
    </body>
</html>
HTML;
    }

    /**
     * Print a simple message in a page
     * @param string $message the mesage to print
     * @return string the HTML content
     */
    public static function simpleMessage(string $message): string{
        return <<<HTML
<div class="text-center fs-3 fw-bold lh-lg h3">{$message}</div>
HTML;
    }
}

?>