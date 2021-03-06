<?php

// is there any PROBLEM with this?
// are there any limitations to this approach?
// we are currently jumping backwards, can we try
// jumping forwards?

// PROBLEM

// well, the label is not defined yet SOOOOO

// how do we solve this?
// pre-process the $ops array
// (php does this too, you can declare a function after invokation)

$code = 'jump:x 1 label:x';
$ops = explode(' ', $code);

$labels = [];

foreach ($ops as $ip => $op) {
    // look, it's copy-pasted from the other place
    if (strpos($op, ':') !== false) {
        list($command, $label) = explode(':', $op);
        switch ($command) {
            case 'label':
                $labels[$label] = $ip;
                break;
        }
        continue;
    }
}

$stack = [];
$ip = 0;

while ($ip < count($ops)) {
    $op = $ops[$ip];
    $ip++;

    echo "$ip:\t$op\t".json_encode($stack)."\n";

    if (is_numeric($op)) {
        array_push($stack, (int) $op);
        continue;
    }

    if (strpos($op, ':') !== false) {
        list($command, $label) = explode(':', $op);
        switch ($command) {
            case 'jump':
                $ip = $labels[$label];
                break;
        }
        continue;
    }

    switch ($op) {
        case '+':
            $b = array_pop($stack);
            $a = array_pop($stack);
            array_push($stack, $a + $b);
            break;
        case '-':
            $b = array_pop($stack);
            $a = array_pop($stack);
            array_push($stack, $a - $b);
            break;
        case '.':
            echo chr(array_pop($stack));
            break;
        case 'dup':
            $top = array_pop($stack);
            array_push($stack, $top);
            array_push($stack, $top);
            break;
        default:
            throw new \RuntimeException("Invalid operation $op at $ip");
            break;
    }
}

// var_dump($stack);
var_dump(array_pop($stack));
