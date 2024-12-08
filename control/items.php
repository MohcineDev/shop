<?php
ob_start(); //output buffering start store everything to memory except the header //must put after before session start

session_start();
$title = 'Items';
if (isset($_SESSION['username'])) {
    include 'ini.php';

    $g = '';
    if (isset($_GET['g'])) {
        $g = $_GET['g'];
    } else {
        $g = 'Manage';
    }


    if ($g == 'Manage') {

        $commande = $connection->prepare("SELECT items.*, categories.Name as category_name , users.Username FROM items 
                                         inner JOIN categories ON items.Cat_ID = categories.ID
                                         INNER JOIN users on items.Member_ID = users.ID");
        $commande->execute();
        $values = $commande->fetchAll();
?>
        <h1>Manage Items</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="text-center manageTable table table-bordered ">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Add Date</th>
                        <th>Category</th>
                        <th>Member</th>
                        <th>Control</th>
                    </tr>
                    <?php
                    foreach ($values as $value) {
                        echo '<tr>';
                        echo '<td>' . $value['item_ID'] . '</td>';
                        echo '<td>' . $value['Name'] . '</td>';
                        echo '<td>' . $value['Description'] . '</td>';
                        echo '<td>' . $value['Price'] . ' $</td>';
                        echo '<td>' . $value['Add_Date'] . '</td>';
                        echo '<td>' . $value['category_name'] . '</td>';
                        echo '<td>' . ucfirst($value['Username']) . '</td>';
                        echo '<td>
                        <a class="btn btn-sm btn-success" href="items.php?g=Edit&id=' . $value['item_ID'] . '"  ><i class="far fa-edit"></i> Edit</a>
                        <a class="btn btn-sm btn-danger" href="items.php?g=Delete&id=' . $value['item_ID'] . '"  ><i class="far fa-trash-alt"></i> Delete</a>';
                        if ($value['Approved'] == 0) {
                            echo ' <a class="btn btn-sm btn-primary" href="items.php?g=Approve&id=' . $value['item_ID'] . '"  ><i class="fa fa-check"> </i> Approve</a>';
                        }
                        echo '</td>';
                        echo '</tr>';
                    }

                    ?>

                </table>
            </div>
            <a class="btn btn-primary" href="items.php?g=Add">+ Add New Item</a>
        </div>

    <?php
    } elseif ($g == 'Add') {
    ?>
        <h1 class="" style="text-align:center">Add Item</h1>
        <div class="container">
            <form action="?g=Insert" method="POST" enctype="multipart/form-data" class="form-horizontal">

                <div class="form-group form-group-lg">
                    <label class="col-sm-5 control-label">Name :</label>
                    <div class="col-sm-10 col-md-12">
                        <input type="text" name="name" autocomplete="off" placeholder="Item name" class="form-control">
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <label class="col-sm-5 control-label">Description :</label>
                    <div class="col-sm-10 col-md-12">
                        <textarea name="description" maxlength="100" placeholder="Item Description max(100)" class="form-control"></textarea>
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-5 control-label">Price :</label>
                    <div class="col-sm-10 col-md-12">
                        <input type="text" name="price" autocomplete="off" placeholder="Item Price" class="form-control">
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-5 control-label">Country :</label>
                    <div class="col-sm-10 col-md-12">
                        <input type="text" name="country" autocomplete="off" placeholder="Item Country" class="form-control">
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-5 control-label">Status :</label>
                    <div class="col-sm-10 col-md-12">
                        <select name="status" id="" class="form-control">
                            <option value="0">...</option>
                            <option value="1">New</option>
                            <option value="2">Like New</option>
                            <option value="3">Used</option>
                            <option value="4">Old</option>
                        </select>

                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-5 control-label">Category :</label>
                    <div class="col-sm-10 col-md-12">
                        <select name="category" id="" class="form-control">
                            <option value="0">...</option>
                            <?php
                            $commande = $connection->prepare('select * from categories where subCategory = 0 ');
                            $commande->execute();
                            $cats = $commande->fetchAll();

                            foreach ($cats as $cat) {
                                echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";

                                $commande = $connection->prepare("select * from categories where subCategory = {$cat['ID']}");
                                $commande->execute();
                                $Categories = $commande->fetchAll();

                                if (!empty($Categories)) {

                                    foreach ($Categories as $subcat) {

                                        echo "<option value='{$subcat['ID']}'> ----> {$subcat['Name']}</option>";
                                    }
                                }
                            }
                            ?>
                        </select>

                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <label class="col-sm-5 control-label">Members :</label>
                    <div class="col-sm-10 col-md-12">
                        <select name="members" id="" class="form-control">
                            <option value="0">...</option>
                            <?php
                            $commande = $connection->prepare('select * from users');
                            $commande->execute();
                            $users = $commande->fetchAll();
                            foreach ($users as $user) {
                                echo "<option value='" . $user['ID'] . "'>" . $user['Username'] . "</option>";
                            }
                            ?>
                        </select>

                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <label class="col-sm-5 control-label">Item Picture :</label>
                    <div class="col-sm-10 col-md-12">
                        <input type="file" name="item-pic" class="form-control">
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-5 control-label">Tags :</label>
                    <div class="col-sm-10  col-md-12">
                        <input type="text" name="tags" autocomplete="off" placeholder="Item Tags" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value=" Add Item" class="btn btn-primary">
                    </div>
                </div>
            </form>
        </div>
        <?php

        //Insert

    } elseif ($g == 'Insert') {
        echo '<h1>Insert Item</h1>';
        echo '<div class="container">';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $pic         = $_FILES['item-pic'];
            $picName     = $pic['name'];
            $tempName    = $pic['tmp_name'];
            $picsize     = $pic['size'];

            $allowedFilesExtension = array('png', 'jpg', 'jpeg');
            $ex = explode('.', $picName);
            $picExtension = strtolower(end($ex));

            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $country = $_POST['country'];
            $status = $_POST['status'];
            $category = $_POST['category'];
            $members = $_POST['members'];
            $tags = $_POST['tags'];

            $Errors = array();
            if (empty($name)) {
                $Errors[] = 'enter something in name!!';
            }
            if (empty($description)) {
                $Errors[] = 'enter something in description!!';
            }
            if (empty($price)) {
                $Errors[] = 'enter something in price!!';
            }
            if (empty($country)) {
                $Errors[] = 'enter something in country!!';
            }
            if (empty($status)) {
                $Errors[] = 'enter something in status!!';
            }
            if (empty($category)) {
                $Errors[] = 'enter something in category!!';
            }
            if (empty($members)) {
                $Errors[] = 'enter something in members!!';
            }
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
                move_uploaded_file($tempName, '..\itemsPics\\' . $pictureName); //Moves an uploaded file to a new location

                $commande = $connection->prepare('insert into items(Name, Description, Price , Country_Made,Status, Add_Date, Cat_ID, Member_ID, tags,item_pic) values (?,?,?,?,?,now(), ?,?,?,?)');
                $commande->execute(array($name, $description, $price, $country, $status, $category, $members, $tags, $pictureName));

                redirectHome('<div class="alert alert-success">Itam Added </div>', 'items.php');
            }
        } else {
            redirectHome("<div class='alert alert-danger'>Something Wrong!!</div>", $sec = 15);
        }
        echo '</div>';

        //Edit

    } elseif ($g == 'Edit') {

        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id = intval($_GET['id']);
        } else   $id = 0;

        $commande = $connection->prepare('select * from items where item_id=?');
        $commande->execute(array($id));
        $row = $commande->fetch();
        $count = $commande->rowCount();

        if ($count == 1) {
        ?>
            <!-- work in edit page-->
            <h1>Edit Item</h1>

            <div class="container">
                <form action="?g=Update" method="POST" enctype="multipart/form-data" class="form-horizontal">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <div class="form-group form-group-lg">
                        <label class="col-sm-5 control-label">Name :</label>
                        <div class="col-sm-12">
                            <input type="text" name="name" value="<?php echo $row['Name'] ?>" placeholder="Item name" class="form-control">
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label class="col-sm-5 control-label">Description :</label>
                        <div class="col-sm-12">

                            <textarea name="description" placeholder="Item Description max(100)" maxlength="100" class="form-control"><?php echo $row['Description']; ?></textarea>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-5 control-label">Price :</label>
                        <div class="col-sm-12">
                            <input type="text" name="price" value="<?php echo $row['Price'] ?>" placeholder="Item Price" class="form-control">
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-5 control-label">Country :</label>
                        <div class="col-sm-12">
                            <input type="text" name="country" value="<?php echo $row['Country_Made'] ?>" placeholder="Item Country" class="form-control">
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-5 control-label">Status :</label>
                        <div class="col-sm-12">
                            <select name="status" class="form-control">
                                <option value="1" <?php if ($row['Status'] == 1) echo 'Selected'; ?>>New</option>
                                <option value="2" <?php if ($row['Status'] == 2) echo 'Selected'; ?>>Like New</option>
                                <option value="3" <?php if ($row['Status'] == 3) echo 'Selected'; ?>>Used</option>
                                <option value="4" <?php if ($row['Status'] == 4) echo 'Selected'; ?>>Old</option>
                            </select>

                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-5 control-label">Category :</label>
                        <div class="col-sm-12">
                            <select name="category" class="form-control">
                                <?php
                                $commande = $connection->prepare('select * from categories where subCategory = 0 ');
                                $commande->execute();
                                $cats = $commande->fetchAll();

                                foreach ($cats as $cat) {

                                    echo "<option  value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";

                                    $commande = $connection->prepare("select * from categories where subCategory = {$cat['ID']}");
                                    $commande->execute();
                                    $Categories = $commande->fetchAll();

                                    if (!empty($Categories)) {
                                        foreach ($Categories as $subcat) {
                                            if ($row['Cat_ID'] == $subcat['ID'])
                                                echo "<option value='{$subcat['ID']}' Selected> ----> {$subcat['Name']}</option>";
                                            else
                                                echo "<option value='{$subcat['ID']}'> ----> {$subcat['Name']}</option>";
                                        }
                                    }
                                }
                                ?>
                            </select>

                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label class="col-sm-5 control-label">Members :</label>
                        <div class="col-sm-12">
                            <select name="members" class="form-control">
                                <?php
                                $commande = $connection->prepare('select * from users');
                                $commande->execute();
                                $users = $commande->fetchAll();
                                foreach ($users as $user) {
                                    echo "<option value='" . $user['ID'] . "'";
                                    if ($row['Member_ID'] == $user['ID']) {
                                        echo 'Selected';
                                    }
                                    echo " >" . $user['Username'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-5 control-label">Item Picture :</label>
                        <div class="col-sm-10 col-md-12">
                            <input type="hidden" name="oldimg" value="<?php echo $row['item_pic'] ?>">
                            <input type="file" name="item-pic" class="form-control">
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-5 control-label">Tags :</label>
                        <div class="col-sm-12">
                            <input type="text" name="tags" autocomplete="off" placeholder="Item Tags" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Update Item" class="btn btn-primary">
                        </div>
                    </div>
                </form>

                <?php

                $commande = $connection->prepare("SELECT comments.*, users.Username as User FROM comments 
                             INNER JOIN users on users.ID = comments.User_id where comments.Item_id ={$id}");
                $commande->execute();
                $values = $commande->fetchAll();

                if ($commande->rowCount() >= 1) {

                    echo "<h1 class='text-center'>Manage { " . $row['Name'] . "  } Comments</h1>";

                ?>
                    <div class="container">
                        <div class="table-responsive">
                            <table class="text-center manageTable table table-bordered ">
                                <tr>
                                    <th>Comment</th>
                                    <th>User Name</th>
                                    <th>Added Date</th>
                                    <th>Control</th>
                                </tr>
                                <?php
                                foreach ($values as $value) {
                                    echo '<tr>';
                                    echo '<td>' . $value['Comment'] . '</td>';
                                    echo '<td>' . $value['User'] . '</td>';
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
                <?php } ?>

            </div>
<?php
        } else {
            echo '<div class="container">';
            redirectHome("<div class='alert alert-danger'>Something Wrong!!</div>");
            echo '</div>';
        }

        //Update

    } elseif ($g == 'Update') {
        echo '<h1>Update Item</h1>';
        echo '<div class="container">';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $Errors = array();

            $id          = $_POST['id'];
            $name        = $_POST['name'];
            $description = $_POST['description'];
            $price       = $_POST['price'];
            $country     = $_POST['country'];
            $status      = $_POST['status'];
            $category    = $_POST['category'];
            $members     = $_POST['members'];
            $tags        = $_POST['tags'];

            $pic         = $_FILES['item-pic'];
            $picName     = $pic['name'];

            if (!empty($picName)) {

                $tempName    = $pic['tmp_name'];
                $picsize     = $pic['size'];

                $allowedFilesExtension = array('png', 'jpg', 'jpeg');
                $ex = explode('.', $picName);
                $picExtension = strtolower(end($ex));

                if (!in_array($picExtension, $allowedFilesExtension)) {
                    $Errors[] = 'please choose a valid picture';
                }
                if ($picsize > 4194304) {
                    $Errors[] = 'picture must be less than 4 MB';
                }
            }

            if (empty($name)) {
                $Errors[] = 'enter something in name!!';
            }
            if (empty($description)) {
                $Errors[] = 'enter something in description!!';
            }
            if (empty($price)) {
                $Errors[] = 'enter something in price!!';
            }
            if (empty($country)) {
                $Errors[] = 'enter something in country!!';
            }
            if (empty($status)) {
                $Errors[] = 'enter something in status!!';
            }
            if (empty($category)) {
                $Errors[] = 'enter something in category!!';
            }
            if (empty($members)) {
                $Errors[] = 'enter something in members!!';
            }

            foreach ($Errors as $err) {
                echo '<div class="alert alert-danger">' . $err . '</div>';
            }
            $pictureName = '';
            if (empty($Errors)) {
                //update db  
                if (!empty($picName)) {
                    $pictureName = rand(0, 1000) . '-' . $picName;
                    move_uploaded_file($tempName, '..\itemsPics\\' . $pictureName); //Moves an uploaded file to a new location
                } else {
                    $pictureName = $_POST['oldimg'];
                }

                $commande = $connection->prepare('update items set Name=?,Description=?, Price = ?, Country_Made=?, Status=?, Cat_ID=?, Member_ID=?, tags=?, item_pic=? where item_ID=?');
                $commande->execute(array($name, $description, $price, $country, $status, $category, $members, $tags, $pictureName, $id));

                redirectHome("<div class='alert alert-success'> item updated </div>");
            }
        } else {
            redirectHome("<div class='alert alert-danger'>Error!! </div>", "index.php");
        }
        echo '</div>';

        //Delete

    } elseif ($g == 'Delete') {

        echo '<h1>Delete Item</h1>';

        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id = intval($_GET['id']);
        } else   $id = 0;

        $commande = $connection->prepare('select * from items where item_id=?');
        $commande->execute(array($id));
        echo '<div class="container">';

        if ($commande->rowCount() == 1) {
            $commande = $connection->prepare("delete from items where item_id = {$id}");
            $commande->execute();

            redirectHome("<div class='alert alert-success'> Item  Deleted</div>", "items.php");
        } else {
            redirectHome("<div class='alert alert-danger'>Something Wrong!!</div>");
        }
        echo '</div>';

        //Approve

    } elseif ($g == 'Approve') {
        echo '<h1>Item Approved</h1>';

        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id = intval($_GET['id']);
        } else   $id = 0;

        $commande = $connection->prepare('select * from items where item_id=?');
        $commande->execute(array($id));
        echo '<div class="container">';

        if ($commande->rowCount() == 1) {
            $commande = $connection->prepare("update items set Approved = 1 where item_id = ?");
            $commande->execute(array($id));

            redirectHome("<div class='alert alert-success'> Item Approved </div>");
        } else {
            redirectHome("<div class='alert alert-danger'>Something Wrong!!</div>");
        }
        echo '</div>';
    }
    include "include/template/footer.php";
} else {
    header('Location:index.php');
    echo '13';
    exit();
}


ob_end_flush();
?>