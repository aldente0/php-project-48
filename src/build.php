<?php

namespace Differ\Builder;

function buildDiffData(array $data1, array $data2): array
{
    $diffData = [];

    foreach ($data1 as $key => $val) {
        if (! isset($data2[$key])) {
            $diffData[] = [
                'name' => $key,
                'value' => $val,
                'status' => 'deleted'
            ];
        } elseif ($data2[$key] === $val) {
            $diffData[] = [
                'name' => $key,
                'value' => $val,
                'status' => 'not changed'
            ];
        } else {
            $diffData[] = [
                'name' => $key,
                'oldValue' => $val,
                'newValue' => $data2[$key],
                'status' => 'changed'
            ];
        }
    }

    foreach ($data2 as $key => $val) {
        if (! isset($data1[$key])) {
            $diffData[] = [
                'name' => $key,
                'status' => 'added',
                'value' => $val
            ];
        }
    }
    return \Functional\sort($diffData, fn ($left, $right) => $left['name'] <=> $right['name']);
}
