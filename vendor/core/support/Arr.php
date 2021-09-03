<?php

namespace Tahir\Support;

use ArrayAccess;

class Arr
{
    public function has($array)
    {
        if( is_array($array) && count($array) > 0 )
        {
            return true;
        }

        return false;
    }

    public function get($array)
    {
        if( $this->has() )
        {
            return $array;
        }

        return [];
    }

    public function only($array, $keys)
    {
        return array_intersect_key($array, array_flip((array) $keys));
    }

    public static function accessible($value)
    {
        return is_array($value) || $value instanceof ArrayAccess;
    }

    public function exists($array, $key)
    {
        if ($array instanceof ArrayAccess)
        {
            return $array->offsetExists($key);
        }

        return array_key_exists($key, $array);
    }

    public function except($array, $keys)
    {
        static::forget($array, $keys);

        return $array;
    }

    public function forget(&$array, $keys)
    {
        $original = &$array;

        $keys = (array) $keys;

        if (count($keys) === 0)
        {
            return;
        }

        foreach ($keys as $key)
        {
            // if the exact key exists in the top-level, remove it
            if ($this->exists($array, $key))
            {
                unset($array[$key]);

                continue;
            }

            $parts = explode('.', $key);

            // clean up before each pass
            $array = &$original;

            while (count($parts) > 1) {
                $part = array_shift($parts);

                if (isset($array[$part]) && is_array($array[$part])) {
                    $array = &$array[$part];
                } else {
                    continue 2;
                }
            }

            unset($array[array_shift($parts)]);
        }
    }

}