<?php

$g = '';
if (isset($_GET['g'])) {
    $g = $_GET['g'];
} else {
    $g = 'Manage';
}

if ($g == 'Manage') {
    echo "Welcome To - {$g} page ";
    echo '<a href="?g=Add">ADD</a>';
} elseif ($g == 'Add') {
    echo "Welcome To - {$g} page";
} elseif ($g == 'Insert') {
    echo "Welcome To - {$g} page";
} else {
    echo 'Sorry! Error';
}
