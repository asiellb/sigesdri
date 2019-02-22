<?php
/**
 * Created by PhpStorm.
 * User: Jr.AvaLug
 * Date: 12/11/2016
 * Time: 4:49
 */

namespace DRI\UsefulBundle\Useful;


class Useful
{
    static function getSlug($preSlug, $separator = '-'){
        // Código copiado de http://cubiq.org/the-perfect-php-clean-url-generator
        $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $preSlug);
        $slug = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $slug);
        $slug = strtolower(trim($slug, $separator));
        $slug = preg_replace("/[\/_|+ -]+/", $separator, $slug);
        return $slug;
    }
}