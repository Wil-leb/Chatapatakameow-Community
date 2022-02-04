<?php

function guidv4($input = null) {
    $input = $input ?? random_bytes(16);
    assert(strlen($input) == 16);

    $input[6] = chr(ord($input[6]) & 0x0f | 0x40);
    $input[8] = chr(ord($input[8]) & 0x3f | 0x80);
    
    return vsprintf("%s%s-%s-%s-%s-%s%s%s", str_split(bin2hex($input), 4));
}