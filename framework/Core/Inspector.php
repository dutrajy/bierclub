<?php

namespace Commercial\Framework\Core;

class Inspector
{
    public static function parse($comment)
    {
        $annotationPattern =  "~@(([[:upper:]][[:alnum:]]*)(\\(\\\"(.*)\\\"\\))?)~u";
        \preg_match_all($annotationPattern, $comment, $matches, \PREG_PATTERN_ORDER);

        return [$matches[2], $matches[4]];
    }
}
