<?php

//connection file
include 'control\connect.php';

$sessionUser = '';
if (isset($_SESSION['normalUser'])) {
    $sessionUser = $_SESSION['normalUser'];
}

include 'include/language/en.php';
include 'include\function\functions.php';
include "include/template/header.php";
