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

    /**
     * Returns distance between two points. By default this function returns result in meters.
     * Function taken from: http://stackoverflow.com/questions/10053358/measuring-the-distance-between-two-coordinates-in-php
     * @param float $fromLat
     * @param float $fromLng
     * @param float $toLat
     * @param float $toLng
     * @param bool $returnMiles
     * @return float
     */
    public static function getDistance($fromLat, $fromLng, $toLat, $toLng, $returnMiles = false)
    {
        $earthRadius = $returnMiles ? 3959 : 6371000;

        // convert from degrees to radians
        $latFrom = deg2rad($fromLat);
        $lonFrom = deg2rad($fromLng);
        $latTo = deg2rad($toLat);
        $lonTo = deg2rad($toLng);

        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) + pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);
        $result = $angle * $earthRadius;
        if (!$returnMiles) {
            $result = round($result);
        }
        return (float) $result;
    }

    /**
     * Makes bounding box around location.
     * Idea taken from: http://stackoverflow.com/questions/10053358/measuring-the-distance-between-two-coordinates-in-php
     * @param float $lat
     * @param float $lng
     * @param integer $radius Radius in meters. In miles if $useMiles parameter set to true.
     * @param bool $useMiles
     * @return array
     */
    public static function getBoundingBox($lat, $lng, $radius, $useMiles = false)
    {
        $earthRadius = $useMiles ? 3959 : 6371000;

        $maxLat = $lat + rad2deg($radius / $earthRadius);
        $minLat = $lat - rad2deg($radius / $earthRadius);
        $maxLon = $lng + rad2deg(asin($radius / $earthRadius) / cos(deg2rad($lat)));
        $minLon = $lng - rad2deg(asin($radius / $earthRadius) / cos(deg2rad($lat)));

        return [
            'minLat' => $minLat,
            'minLng' => $minLon,
            'maxLat' => $maxLat,
            'maxLng' => $maxLon,
        ];
    }
}