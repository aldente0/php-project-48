<?php

namespace Differ\Formatters;

function plain($diffData, $stringFromLastLevel = '', $isFirstLevel = true): string
{
    $result = '';

    foreach ($diffData as $data) {
        if ($data['status'] === 'not changed') {
            continue;
        }

        $row1 = $stringFromLastLevel ?: 'Property ';
        $name = $data['name'];

        if (!$isFirstLevel) {
            $row1 .= ".{$name}";
        } else {
            $row1 .= "'{$name}";
        }

        if ($data['status'] === 'nested') {
            $row1 = plain($data['child'], $row1, false);
        } elseif ($data['status'] === 'changed') {
            $oldValue = toPlain($data['oldValue']);
            $newValue = toPlain($data['newValue']);

            $row1 .= "' was updated. From {$oldValue} to $newValue\n";
        } else {
            $value = toPlain($data['value']);

            if ($data['status'] === 'deleted') {
                $row1 .= "' was removed\n";
            } elseif ($data['status'] === 'added') {
                $row1 .= "' was added with value: {$value}\n";
            }
        }

        $result = implode("", [$result, $row1]);
    }

    return $result;
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
        return "'" . $data . "'";
    } elseif (is_null($data)) {
        return 'null';
    }

    return (string) $data;
}
