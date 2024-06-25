<?php

namespace Differ\Builder;

use function Functional\sort;

function buildDiffData(object $data1, object $data2): array
{
    $diffData = [];
    $sortedKeys = sort(
        array_unique(array_merge(array_keys(get_object_vars($data1)), array_keys(get_object_vars($data2)))),
        fn ($left, $right) => $left <=> $right
    );

    foreach ($sortedKeys as $key) {
        if (! property_exists($data1, $key) && property_exists($data2, $key)) {
            $diffData[] = [
                'name' => $key,
                'value' => $data2->$key,
                'status' => 'added'
            ];
        } elseif (property_exists($data1, $key) && ! property_exists($data2, $key)) {
            $diffData[] = [
                'name' => $key,
                'value' => $data1->$key,
                'status' => 'deleted'
            ];
        } elseif (property_exists($data1, $key) && property_exists($data2, $key)) {
            if (is_object($data1->$key) && is_object($data2->$key)) {
                $diffData[] = [
                    'name' => $key,
                    'child' => buildDiffData($data1->$key, $data2->$key),
                    'status' => 'nested'
                ];
            } elseif ($data1->$key === $data2->$key) {
                $diffData[] = [
                    'name' => $key,
                    'value' => $data1->$key,
                    'status' => 'not changed'
                ];
            } else {
                $diffData[] = [
                    'name' => $key,
                    'oldValue' => $data1->$key,
                    'newValue' => $data2->$key,
                    'status' => 'changed'
                ];
            }
        }
    }

    return $diffData;
}
