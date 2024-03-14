<?php

if (! function_exists('std')) {
    function std($array): float|int
    {
        return sqrt(variance($array));
    }
}
if (! function_exists('mean')) {
    function mean($array): float|int
    {
        if (count($array) == 0) {
            return 0;
        }

        return array_sum($array) / count($array);
    }
}
if (! function_exists('variance')) {
    function variance($array): float|int
    {
        $N = count($array);

        return diffFromMean($array) / ($N);
    }
}
if (! function_exists('diffFromMean')) {
    function diffFromMean($array, $squared = true): float|int
    {
        $mean = mean($array);

        return array_sum(array_map(fn ($x) => pow($x - $mean, $squared ? 2 : 1), $array));
    }
}
if (! function_exists('dotProduct')) {
    function dotProduct($A, $B): float|int
    {
        return array_sum(array_map(fn ($x, $y) => $x * $y, $A, $B));
    }
}

if (! function_exists('l2Norm')) {
    function l2Norm($A): float|int
    {
        return sqrt(array_sum(array_map(fn ($x) => pow($x, 2), $A)));
    }
}
