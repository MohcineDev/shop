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
        $query = '';
        $h1 = 'Manage Members';
        if (isset($_GET['side']) && $_GET['side'] == 'pending') {
            $query = 'and registeredStatus = 0';
            $h1 = 'Pending Members';
        }
        $commande = $connection->prepare("select * from users where groupid != 1 $query");
        $commande->execute();
        $values = $commande->fetchAll();
        echo "<h1>{$h1}</h1>";
?>
        <div class="container">
         
            <div class="table-responsive">
                <table class="text-center manageTable table table-bordered ">
                    <tr>
                        <th>ID</th>
                        <th>Picture</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Full Name</th>
                        <th>Register Date</th>
                        <th>Control</th>
                    </tr>
                    <?php
                    foreach ($values as $value) {
                        echo '<tr>';
                        echo '<td>' . $value['ID'] . '</td>';
                        if (empty($value['profile_pic'])) {
                            echo '<td><img src="profilePics/' . rand(0, 5) . '.png" alt="profile picture"></td>';
                        } else {

                            echo '<td><img src="profilePics/' . $value['profile_pic'] . '" alt="profile picture"></td>';
                        }
                        echo '<td>' . ucfirst($value['Username']) . '</td>';
                        echo '<td>' . $value['Email'] . '</td>';
                        echo '<td>' . $value['Fullname'] . '</td>';
                        echo '<td>' . $value['registeredDate'] . '</td>';
                        echo '<td>
                        <a class="btn btn-success btn-sm" href="members.php?g=Edit&id=' . $value['ID'] . '"  > Edit</a>
                        <a class="btn btn-danger btn-sm" href="members.php?g=Delete&id=' . $value['ID'] . '"  >Delete</a>';

                        if ($value['registeredStatus'] == 0) {
                            echo ' <a class="btn btn-primary btn-sm" href="members.php?g=Active&id=' . $value['ID'] . '"  >Active</a>';
                        }
                        echo '</td>';
                        echo '</tr>';
                    }

                    ?>

                </table>
            </div>
            <a class="btn btn-primary" href="members.php?g=Add"><i class="fa fa-plus"></i>  Add New Member</a>
        </div>

    <?php
    } elseif ($g == 'Add') {
    ?>
        <!-- work in Add member page-->
        <h1>Add New Member</h1>
        <div class="container">
            <form action="?g=Insert" method="POST" enctype="multipart/form-data" class="form-horizontal">
                <div class="form-group form-group-lg">
                    <label class="col-sm-10 control-label">Username :</label>
                    <div class="col-sm-10">
                        <input type="text" name="username" placeholder="username" autocomplete="off" required class="form-control">
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <label class="col-sm-10 control-label">Password :</label>
                    <div class="col-sm-10">
                        <input type="password" name="password" placeholder="password" required class="form-control">
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <label class="col-sm-10 control-label">Email :</label>
                    <div class="col-sm-10">
                        <input type="email" name="email" required placeholder="Your Email Address" class="form-control">
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <label class="col-sm-10 control-label">Full name :</label>
                    <div class="col-sm-10">
                        <input type="text" name="fullName" placeholder="Your Full name" required class="form-control">
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <label class="col-sm-10 control-label">Profile Picture :</label>
                    <div class="col-sm-10">
                        <input type="file" name="profile-pic" required class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-md-10">
                        <input type="submit" value=" Add Member " class="btn form-control btn-primary">
                    </div>
                </div>
            </form>
        </div>
        <?php

        //Insert

    } elseif ($g == 'Insert') {
        echo '<h1>Insert page</h1>';
        echo '<div class="container">';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $pic = $_FILES['profile-pic'];

            ///print_r($pic);

            $picName = $pic['name'];
            $pic['type'];
            $tempName = $pic['tmp_name'];
            $picsize = $pic['size'];

            $allowedFilesExtension = array('png', 'jpg', 'jpeg');

            $username = $_POST['username'];
            $password = $_POST['password'];
            $email    = $_POST['email'];
            $fullname = $_POST['fullName'];

            $hashedPassword = sha1($password);
            
            $ex = explode('.', $picName);

            $picExtension = strtolower(end($ex));

            $Errors = array();
            if (empty($username)) {
                $Errors[] = 'enter something in username!!';
            }

            if (strlen($username) < 4) {
                $Errors[] = 'username must be more than 4!!';
            }
            if (empty($password)) {
                $Errors[] = 'enter something in password!!';
            }
            if (empty($email)) {
                $Errors[] = 'enter something in email!!';
            }
            if (empty($fullname)) {
                $Errors[] = 'enter something in fullname!!';
            }

            //Checks if a value exists in an array
            if (!in_array($picExtension, $allowedFilesExtension)) {
                $Errors[] = 'please choose a valid picture';
            }
            if ($picsize > 4194304) {
                $Errors[] = 'picture must be less than 4 MB';
            }

            foreach ($Errors as $err) {
                echo '<div class="alert alert-danger">' . $err . '</div>';
            }

            if (empty($Errors)) {

                $pictureName = rand(0, 1000) . '-' . $picName;
                move_uploaded_file($tempName, 'profilePics\\' . $pictureName); //Moves an uploaded file to a new location

                $count = checkInsert("select username from users where username = ", $username);
                if ($count == 1) {
                    redirectHome("<div class='alert alert-danger'>Sorry ! this username ' $username ' already exist please change the username</div>", "members.php?g=Add");
                } else {
                    //insert to db
                    $commande = $connection->prepare('insert into users(Username, Password, Email, Fullname,registeredStatus,registeredDate, profile_pic) values ( ? ,?, ?, ?, 1,now(),? )');
                    $commande->execute(array($username, $hashedPassword, $email, $fullname, $pictureName));

                    redirectHome('<div class="alert alert-success">row changed or updated : ' . $commande->rowCount() . '</div>', 'members.php');
                }
            }
        } else {
            redirectHome("<div class='alert alert-danger'>Something Wrong!!</div>");
        }
        echo '</div>';

//Edit

    } elseif ($g == 'Edit') {

        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id = intval($_GET['id']);
        } else   $id = 0;

        $commande = $connection->prepare('select * from users where id=?');
        $commande->execute(array($id));
        $row = $commande->fetch();
        $count = $commande->rowCount();

        if ($count == 1) {
        ?>
        
            <h1>Edit Profile</h1>

            <div class="container">
                <form action="?g=Update" method="POST" class="form-horizontal">

                    <input type="hidden" name="id" value='<?php echo $id ?>'>
                    <div class="form-group form-group-lg">
                        <label class="col-sm-10 control-label">Username :</label>
                        <div class="col-sm-12">
                            <input type="text" name="username" value="<?php echo $row['Username'] ?>" autocomplete="off" required class="form-control">
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label class="col-sm-10 control-label">Password :</label>
                        <div class="col-sm-12">
                            <input type="hidden" name="oldpass" value="<?php echo $row['Password'] ?>">
                            <input type="password" name="newpass" class="form-control">
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label class="col-sm-10 control-label">Email :</label>
                        <div class="col-sm-12">
                            <input type="email" name="email" value="<?php echo $row['Email'] ?>" required class="form-control">
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label class="col-sm-10 control-label">Full name :</label>
                        <div class="col-sm-12">
                            <input type="text" name="fullName" value="<?php echo $row['Fullname'] ?>" required class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-4">
                            <input type="submit" value="Save" class="btn form-control btn-primary">
                        </div>
                    </div>
                </form>
            </div>
<?php
        } else {
            echo '<div class="container">';
            redirectHome("<div class='alert alert-danger'>Something Wrong!!</div>");
            echo '</div>';
        }
    } elseif ($g == 'Update') {

        echo '<h1>Update Page</h1>';
        echo '<div class="container">';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $id = $_POST['id'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $fullname = $_POST['fullName'];

            //Determine whether $_POST['Password'] is empty
            $password = '';
            if (empty($_POST['newpass'])) {
                $password = $_POST['oldpass'];
            } else {
                $password = sha1($_POST['newpass']);
            }

            $Errors = array();
            if (empty($username)) {
                $Errors[] = 'enter something in username!!<br>';
            }

            if (strlen($username) < 4) {
                $Errors[] = 'username must be more than 4!!<br>';
            }
            if (empty($email)) {
                $Errors[] = 'enter something in email!!<br>';
            }
            if (empty($fullname)) {
                $Errors[] = 'enter something in fullname!!<br>';
            }

            foreach ($Errors as $err) {
                echo '<div class="alert alert-danger">' . $err . '</div>';
            }

            if (empty($Errors)) {
                //update db
                $commande = $connection->prepare('update users set Username=?,password=?, email = ?, Fullname=? where id=?');
                $commande->execute(array($username, $password, $email, $fullname, $id));

                redirectHome("<div class='alert alert-success'> Member Updated </div>");
            }
        } else {
            redirectHome("<div class='alert alert-danger'>Error!! </div>", "index.php");
        }
        echo '</div>';
        //
    } elseif ($g == 'Delete') {

        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id = intval($_GET['id']);
        } else   $id = 0;

        $commande = $connection->prepare('select * from users where id=?');
        $commande->execute(array($id));

        if ($commande->rowCount() == 1) {
            $commande = $connection->prepare("delete from users where id = {$id}");
            $commande->execute();

            echo '<div class="container">';
            redirectHome("<div class='alert alert-success'> Member Deleted</div>");
            echo '</div>';
        } else {
            echo '<div class="container">';
            redirectHome("<div class='alert alert-danger'>Something Wrong!!</div>");
            echo '</div>';
        }
    } elseif ($g == 'Active') {

        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id = intval($_GET['id']);
        } else   $id = 0;

        $commande = $connection->prepare('select * from users where id=?');
        $commande->execute(array($id));

        echo '<div class="container">';

        if ($commande->rowCount() == 1) {
            $commande = $connection->prepare("update users set registeredStatus = 1 where id = ?");
            $commande->execute(array($id));

            redirectHome("<div class='alert alert-success'> Member Activated</div>");
        } else {
            redirectHome("<div class='alert alert-danger'>Something Wrong!!</div>");
        }
        echo '</div>';
    }
    include "include/template/footer.php";
} else {
    header('Location:index.php');
    exit();
}
