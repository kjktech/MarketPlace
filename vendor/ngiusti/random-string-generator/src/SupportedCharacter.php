<?php

namespace RandomStringGenerator;

class SupportedCharacter
{
    const LOWER_ALPHA_CHARS = [
        'a', 'b', 'c', 'd', 'e', 'f', 'g',
        'h', 'i', 'j', 'k', 'l', 'm', 'n',
        'o', 'p', 'q', 'r', 's', 't', 'u',
        'v', 'w', 'x', 'y', 'z'
    ];

    /** Reduces the number of computations by removing strtoupper execution */
    const UPPER_ALPHA_CHARS = [
        'A', 'B', 'C', 'D', 'E', 'F', 'G',
        'H', 'I', 'J', 'K', 'L', 'M', 'N',
        'O', 'P', 'Q', 'R', 'S', 'T', 'U',
        'V', 'W', 'X', 'Y', 'Z'
    ];

    const DIGITS = [
        0, 1, 2, 3, 4, 5, 6, 7, 8, 9
    ];

    const PUNCTUATION = [
        "!", "\"", "#", "$", "%", "&", "'", "(",
        ")", "*", "+", ",", "-", ".", "/", ":",
        ";", "<", "=", ">", "?", "@", "[", "\\",
        "]", "^", "_", "`", "{", "|", "}", "~"
    ];
}