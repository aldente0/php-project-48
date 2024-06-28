<?php

namespace Differ\Formatters;

function json($diffData): string
{
    return json_encode($diffData);
}
