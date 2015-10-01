<?php
namespace mgcode\helpers;

class RequestHelper extends \yii\base\Component
{
    /**
     * Returns unsigned IP address from signed IP address
     * @param $signed
     * @return string
     */
    public static function signedToUnsigned($signed)
    {
        return sprintf("%u", $signed);
    }

    /**
     * Converts a string containing an (IPv4) Internet Protocol dotted address into unsigned integer
     * @param string $ip A standard format address.
     * @return string
     */
    public static function ipToUnsignedInt($ip)
    {
        return static::signedToUnsigned(ip2long($ip));
    }

    /**
     * Converts an (IPv4) Internet network address into a string in Internet standard dotted format
     * Signed/Unsigned integers can be used
     * @param string $ip A proper address representation.
     * @return string
     */
    public static function intToIp($ip)
    {
        return long2ip($ip);
    }

    /**
     * Checks if IP address is in CIDR IP range
     * @param string $ip Unsigned integer IP address
     * @param $cidr
     * @return bool
     */
    public static function isIpInCidr($unsignedIp, $cidr)
    {
        $range = static::getIpRangeFromCidr($cidr);
        return $unsignedIp >= $range[0] && $unsignedIp <= $range[1];
    }

    /**
     * Returns IP range from CIDR
     * @param string $cidr
     * @return array
     */
    public static function getIpRangeFromCidr($cidr)
    {
        list($ip, $mask) = explode('/', $cidr);

        $maskBinStr = str_repeat("1", $mask).str_repeat("0", 32 - $mask); //net mask binary string
        $inverseMaskBinStr = str_repeat("0", $mask).str_repeat("1", 32 - $mask); //inverse mask

        $ipLong = ip2long($ip);
        $ipMaskLong = bindec($maskBinStr);
        $inverseIpMaskLong = bindec($inverseMaskBinStr);
        $netWork = $ipLong & $ipMaskLong;

        $start = $netWork + 1; //ignore network ID(eg: 192.168.1.0)

        $end = ($netWork | $inverseIpMaskLong) - 1; //ignore broadcast IP(eg: 192.168.1.255)
        return [
            static::signedToUnsigned($start),
            static::signedToUnsigned($end),
        ];
    }

    /**
     * Checks is users ip public.
     * @param string $ip A standard (dotted) format address
     * @param bool $matchIpv6 Whether to match ipv6 addresses or not
     * @return bool
     */
    public static function isPublicIp($ip, $matchIpv6 = true)
    {
        $ip = trim($ip);
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
            return true;
        } else if ($matchIpv6 && filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
            return true;
        }

        return false;
    }

    /**
     * Returns forwarded user IP address.
     * @param bool $returnDefault Whether to return default address if not found
     * @return string
     */
    public static function getUserIpForwarded($returnDefault = false)
    {
        $varsToCheck = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED');
        foreach ($varsToCheck as $key) {
            if (!array_key_exists($key, $_SERVER)) {
                continue;
            }
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                $ip = trim($ip); // just to be safe
                if (self::isPublicIp($ip)) {
                    return $ip;
                }
            }
        }
        return $returnDefault ? $_SERVER["REMOTE_ADDR"] : null;
    }

    /**
     * Converts ip address to binary IP
     * @param string $ip A standard format address
     * @return string
     */
    public static function ipToBin($ip)
    {
        if (filter_var($ip, FILTER_VALIDATE_IP) === false) {
            return false;
        }

        return inet_pton($ip);
    }

    /**
     * Converts binary address to standard format address
     * @param $bin
     * @return string A standard format address
     */
    public static function binToIp($bin)
    {
        return inet_ntop($bin);
    }

    /**
     * Checks is code in white list.
     * White list codes: 1xx, 2xx, 3xx
     * @param integer $code
     * @return bool
     */
    public static function isCodeInWhiteList($code)
    {
        $regex = '/^(1|2|3)\d\d$/';
        return preg_match($regex, $code) ? true : false;
    }
}