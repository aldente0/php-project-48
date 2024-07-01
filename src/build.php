<?php

namespace Differ\Builder;

use function Functional\sort;

function buildDiffData(object $data1, object $data2): array
{
    $sortedKeys = sort(
        array_unique(array_merge(array_keys(get_object_vars($data1)), array_keys(get_object_vars($data2)))),
        fn ($left, $right) => $left <=> $right
    );

    return array_reduce($sortedKeys, function ($acc, $key) use ($data1, $data2) {
        if (! property_exists($data1, $key) && property_exists($data2, $key)) {
            $newAcc = [...$acc, [
                'name' => $key,
                'value' => $data2->$key,
                'status' => 'added'
            ]];
            /*array_push($acc, [
                'name' => $key,
                'value' => $data2->$key,
                'status' => 'added'
            ]);*/
        } elseif (property_exists($data1, $key) && ! property_exists($data2, $key)) {
            $newAcc = [...$acc, [
                'name' => $key,
                'value' => $data1->$key,
                'status' => 'deleted'
            ]];
            /*array_push($acc, [
                'name' => $key,
                'value' => $data1->$key,
                'status' => 'deleted'
            ]);*/
        } else /*if (property_exists($data1, $key) && property_exists($data2, $key))*/ {
            if (is_object($data1->$key) && is_object($data2->$key)) {
                $newAcc = [...$acc, [
                    'name' => $key,
                    'child' => buildDiffData($data1->$key, $data2->$key),
                    'status' => 'nested'
                ]];
                /*array_push($acc, [
                    'name' => $key,
                    'child' => buildDiffData($data1->$key, $data2->$key),
                    'status' => 'nested'
                ]);*/
            } elseif ($data1->$key === $data2->$key) {
                $newAcc = [...$acc, [
                    'name' => $key,
                    'value' => $data1->$key,
                    'status' => 'not changed'
                ]];
                /*array_push($acc, [
                    'name' => $key,
                    'value' => $data1->$key,
                    'status' => 'not changed'
                ]);*/
            } else {
                $newAcc = [...$acc, [
                    'name' => $key,
                    'oldValue' => $data1->$key,
                    'newValue' => $data2->$key,
                    'status' => 'changed'
                ]];
                /*array_push($acc, [
                    'name' => $key,
                    'oldValue' => $data1->$key,
                    'newValue' => $data2->$key,
                    'status' => 'changed'
                ]);*/
            }
        }

        return $newAcc;
    }, []);
}
