<?php

namespace Differ\Formatters;

function stylish(array $diffData, int $level = 1): string
{
    $result = '{';
    $spaces = str_repeat('    ', $level - 1);
    $result = array_reduce($diffData, function ($res, $data) use ($spaces, $level) {
        $name = $data['name'];
        $res = implode("\n", [$res, $spaces]);
        if ($data['status'] === 'nested') {
            $nested = stylish($data['child'], $level  + 1);
            return implode("", [$res, "    {$name}: {$nested}"]);
        }

        if ($data['status'] === 'changed') {
            $newValue = toStylish($data['newValue'], $level + 1);
            $oldValue = toStylish($data['oldValue'], $level + 1);

            $res = implode("", [$res, "  - {$name}: {$oldValue}"]);
            return implode("\n", [$res, "{$spaces}  + {$name}: {$newValue}"]);
        }

        $value = toStylish($data['value'], $level + 1);

        if ($data['status'] === 'deleted') {
            return implode("", [$res, "  - {$name}: {$value}"]);
        } elseif ($data['status'] === 'added') {
            return implode("", [$res, "  + {$name}: {$value}"]);
        } else {
            return implode("", [$res, "    {$name}: {$value}"]);
        }
    }, $result);

    return implode("\n", [$result, "{$spaces}}"]);
}

function toStylish($data, int $level = 1): string
{
    if (is_object($data)) {
        return toStringObject($data, $level);
    } elseif (is_array($data)) {
        return toStringArray($data, $level);
    } elseif ($data === true) {
        return "true";
    } elseif ($data === false) {
        return "false";
    } elseif (is_null($data)) {
        return "null";
    }

    return (string) $data;
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
            $string = implode("", [$string, toStylish($value, $level + 1)]);
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
            $string = toStylish($value, $level + 1);
        } else {
            $string = rtrim(implode("\n", [ $string, "{$spaces}    {$value},"]));
        }
    }

    return implode("\n", [$string, "{$spaces}]"]);
}
