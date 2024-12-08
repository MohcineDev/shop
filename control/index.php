<?php
ob_start();
session_start();
$title = 'Login';
$navbar = 'no';
if (isset($_SESSION['username'])) {
    header('Location: dashboard.php');
}

include 'ini.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $_POST['err'] = "Hello";
    $hashPass = sha1($password);

    $commande = $connection->prepare('select id, username, password from users where username= ? and password= ? and groupId=1');
    $commande->execute(array($username, $hashPass));
    $row = $commande->fetch();
    $count = $commande->rowCount();

    if ($count > 0) {
        $_SESSION['username'] = $username;
        $_SESSION['id'] = $row['id']; //get id
        header('Location: dashboard.php');
        exit();
    }
}

ob_end_flush();
?>
<div class="container login-page">
    <h1>Admin Login</h1>



    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <input type="text" class="form-control" name="username" value="" autocomplete="off" placeholder="username">
        <input type="password" class="form-control" name="password" placeholder="password">
        <input class="btn btn-primary btn-block" type="submit" value="Login">
    </form>
</div>

<?php
include "include/template/footer.php";
?>