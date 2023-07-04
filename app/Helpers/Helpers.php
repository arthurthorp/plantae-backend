<?php
namespace App\Helpers;

use Illuminate\Support\Str;

class Helpers {
    static function convertToCamelCase($data) {
        if (is_object($data)) {
            $convertedData = new \stdClass();
            foreach ($data as $key => $value) {
                $convertedKey = Str::camel($key);
                $convertedData->$convertedKey = is_array($value) ? self::convertToCamelCase($value) : (is_object($value) ? self::convertToCamelCase($value) : $value);
            }
            return $convertedData;
        } elseif (is_array($data)) {
            $convertedData = [];
            foreach ($data as $key => $value) {
                $convertedKey = Str::camel($key);
                $convertedData[$convertedKey] = is_array($value) ? self::convertToCamelCase($value) : (is_object($value) ? self::convertToCamelCase($value) : $value);
            }
            return $convertedData;
        }
        return $data;
    }
}


?>
