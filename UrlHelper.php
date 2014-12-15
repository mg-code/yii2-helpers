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
        $parsedUrl = parse_url($url);

        // Parse existing parameters
        $query = [];
        if (isset($parsedUrl['query'])) {
            $query = static::parseUrlQuery($parsedUrl['query']);
        }

        // Append new queries and override existing ones
        $query = array_merge($query, $params);

        return static::_buildUrl($parsedUrl, $query);
    }

    /**
     * Removes parameter from url query
     * @param string $url
     * @param string|array $key Can be an array of keys
     * @return string
     */
    public static function removeParamFromUrl($url, $key)
    {
        if (!$url) {
            return $url;
        }

        // Parse url and query
        $parsedUrl = parse_url($url);
        $query = isset($parsedUrl['query']) ? static::parseUrlQuery($parsedUrl['query']) : [];

        // Remove keys
        $key = is_array($key) ? $key : [$key];
        foreach ($key as $val) {
            unset($query[$val]);
        }

        return static::_buildUrl($parsedUrl, $query);
    }

    /**
     * Parses url query to parameters
     * This function keeps dots. By default parse_str converts dots to underscores.
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

    /**
     * Builds url from parameters
     * @param mixed $parsedUrl
     * @param array $query
     * @static
     * @return string
     */
    protected static function _buildUrl($parsedUrl, array $query)
    {
        $cleanUrl = isset($parsedUrl['scheme']) ? $parsedUrl['scheme'].'://' : '';
        $cleanUrl .= isset($parsedUrl['host']) ? $parsedUrl['host'] : '';
        $cleanUrl .= isset($parsedUrl['port']) ? ':'.$parsedUrl['port'] : '';
        $cleanUrl .= isset($parsedUrl['path']) ? $parsedUrl['path'] : '';
        $cleanUrl .= $query ? '?'.http_build_query($query) : '';
        $cleanUrl .= isset($parsedUrl['fragment']) ? '#'.$parsedUrl['fragment'] : '';

        return $cleanUrl;
    }
}