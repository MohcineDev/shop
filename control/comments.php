<?php
ob_start(); //output buffering start store everything to memory except the header //must put after before session start

session_start();
$title = 'Comments';
if (isset($_SESSION['username'])) {
    include 'ini.php';

    $g = '';
    if (isset($_GET['g'])) {
        $g = $_GET['g'];
    } else {
        $g = 'Manage';
    }

    if ($g == 'Manage') {

        $commande = $connection->prepare("SELECT comments.*, items.Name, users.Username as User FROM comments 
        INNER JOIN items on comments.Item_id = items.item_ID
        INNER JOIN users on users.ID = comments.User_id");
        $commande->execute();
        $values = $commande->fetchAll();
        echo "<h1>Manage Comments</h1>";
?>
        <div class="container">
            <div class="table-responsive">
                <table class="text-center manageTable table table-bordered ">
                    <tr>
                        <th>ID</th>
                        <th>Comment</th>
                        <th>Item Name</th>
                        <th>User Name</th>
                        <th>Added Date</th>
                        <th>Control</th>
                    </tr>
                    <?php
                    foreach ($values as $value) {
                        echo '<tr>';
                        echo '<td>' . $value['Id'] . '</td>';
                        echo '<td>' . $value['Comment'] . '</td>';
                        echo '<td>' . $value['Name'] . '</td>';
                        echo '<td>' . ucfirst($value['User']) . '</td>';
                        echo '<td>' . $value['Com_date'] . '</td>';
                        echo '<td>
                        <a class="btn btn-success btn-sm" href="Comments.php?g=Edit&id=' . $value['Id'] . '"  > Edit</a>
                        <a class="btn btn-danger btn-sm" href="Comments.php?g=Delete&id=' . $value['Id'] . '"  >Delete</a>';

                        if ($value['Status'] == 0) {
                            echo ' <a class="btn btn-primary btn-sm" href="comments.php?g=Approve&id=' . $value['Id'] . '"  ><i class="fa fa-check"></i>Approve</a>';
                        }
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </table>
            </div>
        </div>
        <?php
    } elseif ($g == 'Edit') {

        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id = intval($_GET['id']);
        } else   $id = 0;

        $commande = $connection->prepare('select * from comments where id=?');
        $commande->execute(array($id));
        $row = $commande->fetch();
        $count = $commande->rowCount();

        if ($count == 1) {
        ?>
            <!-- work in edit page-->
            <h1>Edit Comment</h1>

            <div class="container">
                <form action="?g=Update" method="POST" class="form-horizontal">
                    <input type="hidden" name="id" value='<?php echo $id ?>'>

                    <div class="form-group form-group-lg">

                        <div class="col-sm-12">
                            <textarea name="comment" autocomplete="off" required class="form-control"><?php echo $row['Comment'] ?></textarea>
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
            redirectHome("<div class='alert alert-danger'>Something Wrong!!</div>", "comments.php");
            echo '</div>';
        }
    } elseif ($g == 'Update') {

        echo '<h1>Update Comment</h1>';
        echo '<div class="container">';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $comment = $_POST['comment'];

            $Errors = array();

            if (empty($comment)) {
                $Errors[] = 'enter something in comment!!<br>';
            }

            foreach ($Errors as $err) {
                echo '<div class="alert alert-danger">' . $err . '</div>';
            }

            if (empty($Errors)) {
                //update db
                $commande = $connection->prepare('update comments set comment=? where Id=?');
                $commande->execute(array($comment, $id));

                redirectHome("<div class='alert alert-success'> Comment updated {$commande->rowCount()} </div>", "comments.php");
            }
        } else {
            redirectHome("<div class='alert alert-danger'>Error!! </div>", "comments.php");
        }
        echo '</div>';

        //Delete

    } elseif ($g == 'Delete') {
        echo '<h1>Delete Comment</h1>';

        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id = intval($_GET['id']);
        } else   $id = 0;

        $commande = $connection->prepare('select * from comments where id=?');
        $commande->execute(array($id));

        echo '<div class="container">';

        if ($commande->rowCount() == 1) {
            $commande = $connection->prepare("delete from comments where id = {$id}");
            $commande->execute();

            redirectHome("<div class='alert alert-success'> Comment Deleted </div>", "comments.php");
        } else {
            redirectHome("<div class='alert alert-danger'>Something Wrong!!</div>", "comments.php");
        }
        echo '</div>';
    } elseif ($g == 'Approve') {
        echo '<h1>Approve Comment</h1>';

        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id = intval($_GET['id']);
        } else   $id = 0;

        $commande = $connection->prepare('select * from comments where id=?');
        $commande->execute(array($id));
        echo '<div class="container">';

        if ($commande->rowCount() == 1) {
            $commande = $connection->prepare("update comments set Status = 1 where id = ?");
            $commande->execute(array($id));

            redirectHome("<div class='alert alert-success'> Comment Approved! </div>", "comments.php");
        } else {
            redirectHome("<div class='alert alert-danger'>Something Wrong!!</div>",  "comments.php");
        }
        echo '</div>';
    }
    include "include/template/footer.php";
} else {
    header('Location:index.php');
    exit();
}
