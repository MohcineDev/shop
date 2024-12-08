<?php

function lang($phrase)
{
    static $lang = array(
        'msg' => 'مرحبا !!'
    );

    return $lang[$phrase];
}
