<?php

namespace Differ\Formatters;

function stylish(array $diffData, int $level = 1): string
{
    $result = '{';
    $spaces = str_repeat('    ', $level - 1);

    return implode("\n", [array_reduce($diffData, function ($res, $data) use ($spaces, $level) {
        $name = $data['name'];
        $resWithSpaces = implode("\n", [$res, $spaces]);
        if ($data['status'] === 'nested') {
            $nested = stylish($data['child'], $level  + 1);
            $resWithValue = implode("", [$resWithSpaces, "    {$name}: {$nested}"]);
        } elseif ($data['status'] === 'changed') {
            $newValue = toStylish($data['newValue'], $level + 1);
            $oldValue = toStylish($data['oldValue'], $level + 1);
            $resWithValue = implode("\n",
                ["$resWithSpaces  - {$name}: {$oldValue}", "{$spaces}  + {$name}: {$newValue}"]
            );
        } else {
            $value = toStylish($data['value'], $level + 1);

            if ($data['status'] === 'deleted') {
                $resWithValue = implode("", [$resWithSpaces, "  - {$name}: {$value}"]);
            } elseif ($data['status'] === 'added') {
                $resWithValue = implode("", [$resWithSpaces, "  + {$name}: {$value}"]);
            } else {
                $resWithValue = implode("", [$resWithSpaces, "    {$name}: {$value}"]);
            }
        }

        return $resWithValue;
    }, $result), "{$spaces}}"]);
}

function toStylish(mixed $data, int $level = 1): string
{
    if (is_object($data)) {
        $stylishVal = toStringObject($data, $level);
    } elseif (is_array($data)) {
        $stylishVal = toStringArray($data, $level);
    } elseif ($data === true) {
        $stylishVal = "true";
    } elseif ($data === false) {
        $stylishVal = "false";
    } elseif (is_null($data)) {
        $stylishVal = "null";
    } else {
        $stylishVal = (string) $data;
    }

    return $stylishVal;
}

function toStringObject(object $data, int $level = 1): string
{
    $keys = array_keys(get_object_vars($data));
    $spaces = str_repeat('    ', $level - 1);

    return implode("\n", [array_reduce($keys, function ($acc, $key) use ($data, $spaces, $level) {
        $value = $data->$key;
        $accUpdated = implode("\n", [ $acc, "{$spaces}    {$key}: "]);
        if (is_object($value) || is_array($value)) {
            return implode("", [$accUpdated, toStylish($value, $level + 1)]);
        } else {
            return rtrim(implode("", [$accUpdated, "{$value}"]));
        }
    }, '{'), "{$spaces}}"]);
}

function toStringArray(array $data, int $level = 1): string
{
    $spaces = str_repeat('    ', $level - 1);

    return implode("\n", [array_reduce($data, function ($acc, $val) use ($level, $spaces) {
        if (is_object($val) || is_array($val)) {
            return toStylish($val, $level + 1);
        } else {
            return rtrim(implode("\n", [ $acc, "{$spaces}    {$val},"]));
        }
    }, '['), "{$spaces}]"]);
}
