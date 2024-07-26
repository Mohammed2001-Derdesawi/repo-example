<?php

if (!function_exists('safeArray')) {
    function safeArray($array)
    {
        foreach ($array as &$value) {
            if (is_array($value)) {
                $value = safeArray($value);
            } elseif (is_null($value)) {
                $value = '';
            }
        }
        unset($value);
        return $array;
    }
}
