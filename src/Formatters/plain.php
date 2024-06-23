<?php

namespace Differ\Formatters;

function plain($diffData, $row = '', $isFirstLevel = true): string
{
    $result = '';

    foreach ($diffData as $data) {
        if ($data['status'] === 'not changed') {
            continue;
        }

        $row1 = $row ?: 'Property ';
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

            $row1 .= "' was updated. From {$oldValue} to $newValue\r\n";
        } else {
            $value = toPlain($data['value']);

            if ($data['status'] === 'deleted') {
                $row1 .= "' was removed\r\n";
            } elseif ($data['status'] === 'added') {
                $row1 .= "' was added with value: {$value}\r\n";
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
    }

    return 'null';
}
