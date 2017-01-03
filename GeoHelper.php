<?php

namespace mgcode\helpers;

class GeoHelper
{
    /**
     * Returns pretty distance from meters
     * @param int $meters
     * @param int $kmPrecision
     * @return string
     */
    public static function getPrettyDistance($meters, $kmPrecision = 1)
    {
        $meters = (int) $meters;
        if ($meters < 1000) {
            return $meters.'m';
        }
        $km = round($meters / 1000, $kmPrecision);
        return $km.'km';
    }
}