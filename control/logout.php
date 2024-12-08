<?php

session_start();
session_unset(); // unset the variable
session_destroy();//destroy

header('Location:index.php');

exit(); // in case there is an error (!show)