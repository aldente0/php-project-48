<?php

namespace Differ\Formatters;

function plain(array $diffData, string $stringFromLastLevel = '', bool $isFirstLevel = true): string
{
    return array_reduce($diffData, function ($res, $data) use ($stringFromLastLevel, $isFirstLevel) {
        if ($data['status'] === 'not changed') {
            return $res;
        }

        $row = strlen($stringFromLastLevel) > 0 ? $stringFromLastLevel : 'Property ';
        $name = $data['name'];

        if (!$isFirstLevel) {
            $namedRow = implode('.', [$row, $name]);
        } else {
            $namedRow = implode("'", [$row, "{$name}"]);
        }

        if ($data['status'] === 'nested') {
            $rowWithValue = plain($data['child'], $namedRow, false);
        } elseif ($data['status'] === 'changed') {
            $oldValue = toPlain($data['oldValue']);
            $newValue = toPlain($data['newValue']);

            $rowWithValue = implode("'", [$namedRow, " was updated. From {$oldValue} to $newValue\n"]);
        } else {
            $value = toPlain($data['value']);

            if ($data['status'] === 'deleted') {
                $rowWithValue = implode("'", [$namedRow, " was removed\n"]);
            } else {
                $rowWithValue = implode("'", [$namedRow, " was added with value: {$value}\n"]);
            }
        }

        return implode("", [$res, $rowWithValue]);
    }, '');
}

function toPlain(mixed $data): string
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
