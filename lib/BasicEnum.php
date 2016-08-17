<?php
/**
 * Base abstract class to provide OO style access to pseudo-enumerated types
 */
abstract class BasicEnum {
    private static $constCacheArray = NULL;

    /*
     * Get the list of constants within this class
     */
    private static function getConstants() {
        if (self::$constCacheArray == NULL) {
            self::$constCacheArray = [];
        }
        $calledClass = get_called_class();
        if (!array_key_exists($calledClass, self::$constCacheArray)) {
            $reflect = new ReflectionClass($calledClass);
            self::$constCacheArray[$calledClass] = $reflect->getConstants();
        }
        return self::$constCacheArray[$calledClass];
    }
    
    /**
     * Checks for a valid enumerated variable name. Will accept lower case unless
     * strict is declared true
     * @param type $name the name to verify
     * @param type $strict Match case
     * @return type boolean
     */
    public static function isValidName($name, $strict = false) {
        $constants = self::getConstants();

        if ($strict) {
            return array_key_exists($name, $constants);
        }

        $keys = array_map('strtolower', array_keys($constants));
        return in_array(strtolower($name), $keys);
    }

    /**
     * Checks if the value provided matches one of the enumerated construct variables' 
     * values
     * @param type $value The value to verify
     * @param type $strict Match case
     * @return boolean
     */
    public static function isValidValue($value, $strict = true) {
        $values = array_values(self::getConstants());
        return in_array($value, $values, $strict);
    }
    
   
}
?>