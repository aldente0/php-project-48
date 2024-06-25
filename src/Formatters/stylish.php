<?php

namespace Differ\Formatters;

function stylish(array $diffData, int $level = 1): string
{
    $result = '{';
    $spaces = str_repeat('    ', $level - 1);

    foreach ($diffData as $data) {
        $name = $data['name'];
        $result = implode("\n", [$result, $spaces]);
        if ($data['status'] === 'nested') {
            $nested = stylish($data['child'], $level  + 1);
            $result = implode("", [$result, "    {$name}: {$nested}"]);
            continue;
        }

        if ($data['status'] === 'changed') {
            $oldValue = $data['oldValue'];
            $newValue = $data['newValue'];

            if (is_bool($oldValue) || is_null($oldValue) || is_object($oldValue) || is_array($oldValue)) {
                $oldValue = toString($oldValue, $level + 1);
            }

            if (is_bool($newValue) || is_null($newValue) || is_object($newValue) || is_array($newValue)) {
                $newValue = toString($newValue, $level + 1);
            }

            $result = implode("", [$result, "  - {$name}: {$oldValue}"]);
            $result = implode("\n", [$result, "{$spaces}  + {$name}: {$newValue}"]);
            continue;
        }

        $value = $data['value'];
        if (is_bool($value) || is_null($value) || is_object($value) || is_array($value)) {
            $value = toString($value, $level + 1);
        }

        if ($data['status'] === 'deleted') {
            $result = implode("", [$result, "  - {$name}: {$value}"]);
        } elseif ($data['status'] === 'added') {
            $result = implode("", [$result, "  + {$name}: {$value}"]);
        } elseif ($data['status'] === 'not changed') {
            $result = implode("", [$result, "    {$name}: {$value}"]);
        }
    }

    return implode("\n", [$result, "{$spaces}}"]);
}

function toString(array|object|bool|null $data, int $level = 1): string
{
    if (is_object($data)) {
        return toStringObject($data, $level);
    } elseif (is_array($data)) {
        return toStringArray($data, $level);
    } elseif ($data === true) {
        return "true";
    } elseif ($data === false) {
        return "false";
    }

    return "null";
}

function toStringObject(object $data, $level = 1): string
{
    $string = '{';
    $keys = array_keys(get_object_vars($data));
    $spaces = str_repeat('    ', $level - 1);

    foreach ($keys as $key) {
        $value = $data->$key;
        $string = implode("\n", [ $string, "{$spaces}    {$key}: "]);
        if (is_object($value) || is_array($value)) {
            $string = implode("", [$string, toString($value, $level + 1)]);
        } else {
            $string = rtrim(implode("", [$string, "{$value}"]));
        }
    }

    return implode("\n", [$string, "{$spaces}}"]);
}

function toStringArray(array $data, int $level = 1): string
{
    $string = '[';
    $spaces = str_repeat('    ', $level - 1);

    foreach ($data as $value) {
        if (is_object($value) || is_array($value)) {
            $string = toString($value, $level + 1);
        } else {
            $string = rtrim(implode("\n", [ $string, "{$spaces}    {$value},"]));
        }
    }

    return implode("\n", [$string, "{$spaces}]"]);
}
