<?php

function lang($phrase)
{
    static $lang = array(
        'msg' => 'Hello'
    );

    return $lang[$phrase];
}
