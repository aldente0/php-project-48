<?php

namespace Differ\Formatters;

function json(array $diffData): string
{
    return json_encode($diffData);
}
