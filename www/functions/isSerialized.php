<?php

function isSerialized($string)
{
    return (@unserialize($string) !== false || $string == 'b:0;');
}
