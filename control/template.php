<?php
ob_start(); //output buffering start store everything to memory except the header //must put after before session start

session_start();
$title = 'Members';
if (isset($_SESSION['username'])) {
    include 'ini.php';

    $g = '';
    if (isset($_GET['g'])) {
        $g = $_GET['g'];
    } else {
        $g = 'Manage';
    }


    if ($g == 'Manage') {
        echo $g;
    } elseif ($g == 'Add') {
        echo $g;
    } elseif ($g == 'Insert') {
        echo $g;
    } elseif ($g == 'Edit') {
        echo $g;
    } elseif ($g == 'Update') {
        echo $g;
    } elseif ($g == 'Delete') {
        echo $g;
    } elseif ($g == 'Active') {
        echo $g;
    }
    include "include/template/footer.php";
} else {
    header('Location:index.php');
    exit();
}


ob_end_flush();
?>