<?php
/**
 * Created by PhpStorm.
 * User: ervin
 * Date: 11.03.2017
 * Time: 11:35
 */

namespace Libs\Admin;


class Helper {

    public static function transformFiled($field, array $languages) {
        $transformField = [];
        if (strpos($field, '*.') !== false) {
            $tmpField = substr($field, 2);
            foreach (array_keys($languages) as $lng) {
                $transformField[$lng] = "{$lng}_{$tmpField}";
            }
        } else {
            $transformField[] = "t_{$field}";
        }

        return $transformField;
    }

    public static function inverseTransformField($field) {
        $tmpPos = strrpos($field, '_');
        $table = substr($field, 0, $tmpPos);
        $column = substr($field, $tmpPos + 1);

        return [
            'table' => $table,
            'column' => $column,
        ];
    }

}