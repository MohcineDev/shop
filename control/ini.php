<?php
//connection file
include 'include/language/en.php';
include 'connect.php';
include 'include\function\functions.php';
include "include/template/header.php";

if(!isset($navbar)){ //check each page if the var $navbar is set
    include "include/template/navbar.php";
}

