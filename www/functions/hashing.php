<?php

function hash_pw($pw)
{

    global $websitesalt;
    global $hashing_options;

    $hash = password_hash($websitesalt . $pw, PASSWORD_BCRYPT, $hashing_options);
    return $hash;
}

function pw_check($pw, $hash)
{

    global $websitesalt;
    if (password_verify($websitesalt . $pw, $hash)) {
        return 1;
    } else {
        return 0;
    }
}
