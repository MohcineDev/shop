<?php
ob_start();

session_start();
if (isset($_SESSION['normalUser'])) {
    header('Location: index.php');
}

include 'ini.php';
//make sure to change the session name user / admin

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['Login'])) {

        $user = $_POST['username'];
        $pass = $_POST['password'];

        $hashedPass = sha1($pass);

        $commande = $connection->prepare('select ID, username, password from users where username= ? and password= ?');
        $commande->execute(array($user, $hashedPass));

        $id = $commande->fetch();

        $count = $commande->rowCount();

        if ($count > 0) {
            $_SESSION['normalUser'] = $user;
            $_SESSION['userId'] = $id['ID'];

            header('Location: index.php');
            exit();
        }
    } else {
        //register form
        $errs = array();
        if (isset($_POST['username'])) {

            $filtered_username = filter_var($_POST['username'], FILTER_SANITIZE_STRING); //Filters a variable with a specified filter

            if (strlen($filtered_username) < 4) {
                $errs[] = 'username must be at least 4 chars !!';
            }
        }
        //email check
        if (isset($_POST['email'])) {

            $filtered_email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); //Filters a variable with a specified filter

            if (filter_var($filtered_email, FILTER_SANITIZE_EMAIL) != true) {
                $errs[] = 'email must be valid email!!';
            }
        }

        if (isset($_POST['password']) && isset($_POST['confirm_password'])) {

            //check for empty password before the sha1 function
            //because sha1 for empty give a value

            if (empty($_POST['password'])) {
                $errs[] = 'enter something in password !!';
            }

            $passOne = sha1($_POST['password']);
            $passTwo =  sha1($_POST['confirm_password']);

            if ($passOne !== $passTwo) { //not identical
                $errs[] = 'passwords not matched !!';
            }
        }
        //insert user to db

        if (empty($errs)) {

            $count = checkusername($_POST['username']);
            if ($count == 1) {
                $errs[] = "Sorry ! this username {$_POST['username']} already exist please change the username";
            } else {
                //insert to db
                $commande = $connection->prepare('insert into users(Username, Password, Email,registeredStatus,registeredDate) values ( ? ,?, ?, 0,now() )');
                $commande->execute(array($_POST['username'], $passOne, $_POST['email']));
                $successRegister = 'try to login';
            }
        }
    }
}

?>

<div class="container login-page">
    <h1 class="text-center">
        <span class="selected" data-class="login">Login</span> |
        <span data-class="register">Register</span>
    </h1>
    <!-- login form-->
    <!-- after done the server validation then add required-->
    <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">

        <input type="text" name="username" pattern=".{4,10}" title="username must be between 4 and 10" required placeholder="Username" class="form-control" autocomplete="off">

        <input type="password" name="password" placeholder="Password" class="form-control" autocomplete="new-password">
        <input type="submit" name="Login" value="Login" class="btn btn-primary btn-block">
    </form>
    <!-- register form-->
    <form class="register" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">

        <input type="text" name="username" placeholder="Username" pattern=".{4,10}" title="username must be between 4 and 10" required class="form-control" autocomplete="off">

        <input type="email" name="email" required placeholder="your mail" class="form-control" autocomplete="off">
        <input type="password" minlength="6" required name="password" placeholder="Password" class="form-control" autocomplete="new-password">
        <input type="password" minlength="6" required name="confirm_password" placeholder="Confirm Password" class="form-control" autocomplete="new-password">
        <input type="submit" name="Register" value="Register" class="btn btn-info btn-block">
    </form>
    <!-- errors -->
    <div class="text-center errs">
        <?php
        if (!empty($errs)) {
            foreach ($errs as $er) {
                echo '<p class="errors">' . $er . '</p>';
            }
        }
        if (isset($successRegister)) {
            echo '<p class="successRegister">' . $successRegister . '</p>';
        }
        ?>
    </div>

</div>


<?php

ob_end_flush();
include "include/template/footer.php";
?>