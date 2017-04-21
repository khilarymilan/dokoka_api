<?php

function random_boolean(float $probability_truth = .5)
{
    $range = (int)(PHP_INT_MAX / 2);
    return (random_int(1, $range) <= $probability_truth * $range);
}