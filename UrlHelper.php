<?php

namespace mgcode\helpers;

class UrlHelper extends \yii\helpers\Url
{
    /**
     * Generate SLUG string max 120 characters long.
     * Only ASCII characters are allowed.
     * Uses old but tested library
     * @param string $string
     * @param string mixed $separator
     * @return string
     */
    public static function toSlug($string, $separator = '-')
    {
        return \mgcode\helpers\vendors\UrlTransliterate::cleanString($string, $separator);
    }

    /**
     * Appends new parameters to url.
     * Overrides existing parameters with new ones.
     * @param string $url
     * @param array $params
     * @return string
     */
    public static function addParamsToUrl($url, $params)
    {
        // Parse url
        $parseUrl = parse_url($url);

        // Parse existing parameters
        $query = [];
        if (isset($parseUrl['query'])) {
            $query = static::parseUrlQuery($parseUrl['query']);
        }

        // Append new queries and override existing ones
        $query = array_merge($query, $params);

        // Build new URL
        $cleanUrl = isset($parseUrl['scheme']) ? $parseUrl['scheme'].'://' : '';
        $cleanUrl .= isset($parseUrl['host']) ? $parseUrl['host'] : '';
        $cleanUrl .= isset($parseUrl['port']) ? ':'.$parseUrl['port'] : '';
        $cleanUrl .= isset($parseUrl['path']) ? $parseUrl['path'] : '';
        $cleanUrl .= $query ? '?'.http_build_query($query) : '';
        $cleanUrl .= isset($parseUrl['fragment']) ? '#'.$parseUrl['fragment'] : '';

        return $cleanUrl;
    }

    /**
     * Parses url query to parameters
     * @param $query
     * @return array
     */
    public static function parseUrlQuery($query)
    {
        $data = preg_replace_callback('/(?:^|(?<=&))[^=[]+/', function ($match) {
            return bin2hex(urldecode($match[0]));
        }, $query);

        parse_str($data, $values);

        return array_combine(array_map('hex2bin', array_keys($values)), $values);
    }
}