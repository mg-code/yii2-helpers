<?php
namespace mgcode\helpers;

class ConstantHelper
{

    /**
     * Checks if a value exists in constants for given class
     * @param string $constant Constant value
     * @param string|object $class The class name or instance
     * @param string $searchPattern Constants prefix. By default returns all constants.
     * @static
     * @return bool
     */
    public static function valueInConstants($constant, $class, $searchPattern = null)
    {
        $constants = self::getConstantList($class, $searchPattern);
        return (bool) array_search($constant, $constants);
    }

    /**
     * Get constants filtered by pattern in a class
     * @param string|object $class The class name or instance
     * @param string $searchPattern Constants prefix. By default returns all constants.
     * @static
     * @return array
     */
    public static function getConstantList($class, $searchPattern = null)
    {
        if (is_object($class)) {
            $class = get_class($class);
        }

        $r = new \ReflectionClass($class);
        $constants = $r->getConstants();

        if ($searchPattern !== null) {
            foreach ($constants as $key => $value) {
                if (strpos($key, $searchPattern) !== 0) {
                    unset($constants[$key]);
                }
            }
        }

        return $constants;
    }

    /**
     * Checks for a constant existence in a class
     * @param string $constant The constant key
     * @param string|object $class The class name or instance
     * @param string $searchPattern Constants prefix. By default returns all constants.
     * @static
     * @return bool
     */
    public static function keyExists($constant, $class, $searchPattern = null)
    {
        $constants = self::getConstantList($class, $searchPattern);
        return array_key_exists($constant, $constants);
    }
}
