<?php
ob_start();
$title = 'Item Details';

session_start();
include 'ini.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
} else   $id = 0;

$commande = $connection->prepare('select I.*, C.Name as cat_name from items as I inner join categories as C on I.Cat_ID = C.ID where item_ID=?');
$commande->execute(array($id));
$row = $commande->fetch();
if ($commande->rowCount() > 0) {
?>
    <h1 class=""><?php echo $row['Name']; ?></h1>

    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <?php
                if (!empty($row['item_pic'])) {

                    echo '<img src="itemsPics/' . $row['item_pic'] . '" class="img-fluid">';
                } else {
                    echo '<img src="itemsPics/default.png" class="img-fluid">';
                }
                ?>


            </div>
            <div class="col-md-9 infos">

                <h2><?php echo  $row['Name']; ?></h2>
                <ul class="list-unstyled">
                    <li><i class="fa fa-calendar-alt"></i> <span>Description </span>: <?php echo $row['Description']; ?></li>
                    <li><i class="fa fa-calendar-alt"></i> <span>Added Date </span>: <?php echo $row['Add_Date']; ?></li>
                    <li><i class="fa fa-calendar-alt"></i> <span>Price </span>: <?php echo $row['Price']; ?></li>
                    <li><i class="fa fa-calendar-alt"></i> <span>Country Made </span>: <?php echo $row['Country_Made']; ?></li>
                    <li><i class="fa fa-tags"></i> <span>Category </span>: <a href='categories.php?g=<?php echo $row['Cat_ID']; ?>'><?php echo $row['cat_name']; ?></a></li>
                    <li><i class="fa fa-tags"></i> <span>Tags </span>:
                        <?php
                        $Tags = explode(',', $row['tags']); //Split a string by string
                        foreach ($Tags as $Tag) {
                            if (!empty($Tag)) {

                                $tag = str_replace(' ', '', $Tag); //Replace all occurrences of the search string with the replacement string
                                $lowerTag = strtolower($tag);

                                echo "<a class='tag' href='tags.php?name={$lowerTag}'>" . $tag . "</a>";
                            }
                        }

                        ?>
                    </li>
                </ul>
            </div>
        </div>
        <hr class="item_hr">

        <!-- comment-->
        <?php
        if (isset($_SESSION['normalUser'])) {
        ?>
            <div class="row">
                <div class="offset-md-3 your-comnt">
                    <h3>Add Your Comment</h3>
                    <form action="<?php echo $_SERVER['PHP_SELF'] . '?id=' . $row['item_ID']; ?>" method="POST">
                        <textarea name="comment"  maxlength="60" placeholder="Category Description max(60)" class="form-control" required ></textarea>
                         

                        <input type="submit" class="btn btn-success" value="Add Comment">
                    </form>

                    <?php

                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                        $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
                        $itemId = $row['item_ID'];
                        $userId = $_SESSION['userId']; //the member who is connected

                        if (!empty($comment)) {
                            $commande = $connection->prepare('insert into comments (Comment, Status, Com_date, Item_id, User_id) values(?,?,?,?,?)');
                            $commande->execute(array($comment, 0, 'NOW()', $itemId, $userId));
                        }
                        if ($commande) { //check if the commande pass correctly
                            echo '<div class="alert alert-success">Comment Added! need to be approved</div>';
                        }
                    }

                    ?>
                </div>
            </div>
        <?php
        } else {
            echo 'you must be <a href="login.php">logged</a> in to add comment';
        }
        ?>
        <hr class="item_hr">
        <?php
        $commande = $connection->prepare("SELECT comments.*, users.Username as User , users.profile_pic as pic FROM comments INNER JOIN users on users.ID = comments.User_id where item_Id = ? and Status = 1");
        $commande->execute(array($row['item_ID']));
        $values = $commande->fetchAll();
        ?>
        <?php
        foreach ($values as $value) { ?>
            <div class="comment-box">
                <div class="row">
                    <div class='col-sm-3 text-center'>

                        <?php
                        if (!empty($value['pic']))
                              echo '<img src="control/profilePics/' . $value['pic'] . '" class="img-fluid rounded-circle">'; 
                        else
                            echo '<img src="control/profilePics/0.png" class="img-fluid rounded-circle">';

                        echo $value['User']; ?>
                    </div>
                    <div class='col-sm-9'>
                        <p class="lead"><?php echo $value['Comment'] ?></p>
                    </div>

                </div>
            </div>
        <?php }
        ?>
    </div>

<?php
} else {
    echo 'something wrong No id!!!';
}

include "include/template/footer.php";
ob_end_flush();
