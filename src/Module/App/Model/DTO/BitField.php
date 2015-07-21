<?php

namespace ApiExport\Module\App\Model\DTO;

/**
 * Class BitField.
 */
class BitField
{
    const ENABLED = 1;  // 0001
    const VIEWED = 2;   // 0010
    const APPROVED = 4; // 0100

    /*
     * Methods
     */

    public static function getAssociativeArray()
    {
        $ref = new \ReflectionClass(get_called_class());

        return $ref->getConstants();
    }

    public static function fromString($string)
    {
        $ref = new \ReflectionClass(get_called_class());
        $result = null;
        if ($ref->hasConstant($string)) {
            $constants = self::getAssociativeArray();
            $result = $constants[$string];
        }

        return $result;
    }

    public static function toString($value)
    {
        $ref = new \ReflectionClass(get_called_class());
        $constants = $ref->getConstants();
        $constants = array_flip($constants);
        $result = null;
        if (array_key_exists($value, $constants)) {
            $result = $constants[$value];
        }

        return $result;
    }

    public static function isValid($value)
    {
        $ref = new \ReflectionClass(get_called_class());
        $constants = $ref->getConstants();
        $constants = array_flip($constants);

        return array_key_exists($value, $constants);
    }
}
