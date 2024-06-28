<?php

namespace Differ\Formatters;

function plain($diffData, $stringFromLastLevel = '', $isFirstLevel = true): string
{
    return array_reduce($diffData, function ($res, $data) use ($stringFromLastLevel, $isFirstLevel) {
        if ($data['status'] === 'not changed') {
            return $res;
        }

        $row = $stringFromLastLevel ?: 'Property ';
        $name = $data['name'];

        if (!$isFirstLevel) {
            $row = implode('.', [$row, $name]);
        } else {
            $row = implode("'", [$row, "{$name}"]);
        }

        if ($data['status'] === 'nested') {
            $row = plain($data['child'], $row, false);
        } elseif ($data['status'] === 'changed') {
            $oldValue = toPlain($data['oldValue']);
            $newValue = toPlain($data['newValue']);

            $row = implode("'", [$row, " was updated. From {$oldValue} to $newValue\n"]);
        } else {
            $value = toPlain($data['value']);

            if ($data['status'] === 'deleted') {
                $row = implode("'", [$row, " was removed\n"]);
            } elseif ($data['status'] === 'added') {
                $row = implode("'", [$row, " was added with value: {$value}\n"]);
            }
        }

        return implode("", [$res, $row]);
    }, '');
}

function toPlain($data): string
{
    if (is_object($data) || is_array($data)) {
        return '[complex value]';
    } elseif ($data === true) {
        return "true";
    } elseif ($data === false) {
        return "false";
    } elseif (is_string($data)) {
        return implode("'", ['', $data, '']);
    } elseif (is_null($data)) {
        return 'null';
    }

    return (string) $data;
}
