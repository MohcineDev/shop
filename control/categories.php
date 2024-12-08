<?php
ob_start();
session_start();
$title = 'Categories';
if (isset($_SESSION['username'])) {
    include 'ini.php';

    $g = '';
    if (isset($_GET['g'])) {
        $g = $_GET['g'];
    } else {
        $g = 'Manage';
    }

    if ($g == 'Manage') {

        $sort = 'DESC';
        $sorting = array('DESC', 'ASC');

        if (isset($_GET['sort']) && in_array($_GET['sort'], $sorting)) {
            $sort = $_GET['sort'];
        }

        $commande = $connection->prepare("select * from categories where subCategory = 0 order by Ordering $sort");
        $commande->execute();
        $Categories = $commande->fetchAll();
?>
        <h1>Manage Categories</h1>

        <div class="container">
            <div class="card ">
                <div class="card-header">Manage Categories
                    <div class="ordering float-right"> Order By :
                        <a class="<?php echo $sort == 'ASC' ? 'active' : '' ?>" href="?sort=ASC">ASC </a>
                        <a class="<?php echo $sort == 'DESC' ? 'active' : '' ?>" href="?sort=DESC"> DESC</a>
                    </div>
                </div>
                <div class="card-body">
                    <?php

                    foreach ($Categories as $Category) {
                        echo ' <div class="category">';
                    ?>
                        <div class="hidden-btns">
                            <a href="?g=Edit&id=<?php echo $Category["ID"]; ?>" class="btn btn-primary"><i class="fa fa-edit"></i> Edit</a>
                            <a href="?g=Delete&id=<?php echo $Category["ID"]; ?>" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</a>
                        </div>
                        <?php
                        echo '<h4>' . $Category['Name'] . '</h4>';
                        if ($Category['Description'] == '') {

                            echo '<p> No Description for this Category</p>';
                        } else
                            echo '<p>' . $Category['Description'] . '</p>';
                        echo '</div>';

                        $cmd = $connection->prepare("select * from categories where subCategory = {$Category['ID']}");
                        $cmd->execute();
                        $subCat = $cmd->fetchAll();
                        $count = $cmd->rowCount();

                        if ($count > 0) {
                            echo '<div class="sub-cat">';
                            echo '<p>Sub Categories</p>';
                            echo '<ul class="list-unstyled">';
                            foreach ($subCat as $sub) {
                        ?>
                                <li><a href="?g=Edit&id=<?php echo $sub['ID'] ?>">- <?php echo $sub['Name'] ?> </a>
                                    <a href="?g=Delete&id=<?php echo $sub["ID"]; ?>" class="sub-delete"><i class="fa fa-trash"></i></a>
                                </li>
                    <?php }
                            echo '</ul>';
                            echo '</div>';
                        }
                        echo '<hr>';
                    }
                    ?>
                </div>
            </div>
            <a class="add-cat btn btn-success" href="?g=Add"><i class="fa fa-plus"></i> Add Category</a>
        </div>
    <?php

        //Add

    } elseif ($g == 'Add') {
    ?>

        <h1>Add Category</h1>
        <div class="container add-category-form">
            <form action="?g=Insert" method="POST" class="form-horizontal">
                <div class="form-group form-group-lg">
                    <label class="col-sm-6 control-label">Name</label>
                    <div class="col-sm-6 col-md-12">
                        <input type="text" name="name" autocomplete="off" placeholder="Category name" required class="form-control">
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <label class="col-sm-6 control-label">Description</label>
                    <div class="col-sm-6 col-md-12">
                        <textarea name="description" maxlength="100" placeholder="Category Description max(100)" class="form-control"></textarea>
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <label class="col-sm-6 control-label">Ordering</label>
                    <div class="col-sm-6 col-md-12">
                        <input type="text" name="ordering" class="form-control">
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-6 control-label">Sub Category of :</label>
                    <div class="col-sm-6 col-md-12">
                        <select name="parent" class="form-control">
                            <option value="0">None</option>
                            <?php
                            $commande = $connection->prepare("select * from categories where subCategory = 0");
                            $commande->execute();
                            $Categories = $commande->fetchAll();

                            foreach ($Categories as $cat) {
                                echo "<option value='{$cat['ID']}'>{$cat['Name']}</option>";
                            }
                            ?>
                        </select>

                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-4 col-form-label">Visible :</label>
                    <div class="col-sm-6 col-md-6">
                        <div class="form-check-inline">
                            <label for="vis-yes">
                                <input type="radio" name="visible" value="0" checked id="vis-yes">
                                Yes
                            </label>
                        </div>
                        <div class="form-check-inline">
                            <label for="vis-no">
                                <input type="radio" name="visible" value="1" id="vis-no">
                                No
                            </label>
                        </div>
                    </div>
                </div>


                <div class="form-group form-group-lg">
                    <label class="col-sm-4 col-form-label">Comment :</label>
                    <div class="col-sm-6 col-md-6">
                        <div class="form-check-inline">
                            <label for="com-yes">
                                <input type="radio" name="comment" value="0" checked id="com-yes">
                                Yes</label>
                        </div>
                        <div class="form-check-inline">
                            <label for="com-no">
                                <input type="radio" name="comment" value="1" id="com-no">
                                No
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <label class="col-sm-4 col-form-label">Allow Ads :</label>
                    <div class="col-sm-6 col-md-6">
                        <div class="form-check-inline"><label for="ads-yes">
                                <input type="radio" name="ads" checked value="0" id="ads-yes">
                                Yes
                            </label>
                        </div>
                        <div class="form-check-inline">
                            <label for="ads-no">
                                <input type="radio" name="ads" value="1" id="ads-no">
                                No
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value=" Add " class="btn btn-primary">
                    </div>
                </div>
            </form>
        </div>
        <?php

//Insert

    } elseif ($g == 'Insert') {
        echo '<h1>Insert Category</h1>';
        echo '<div class="container">';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $Name = $_POST['name'];
            $parent = $_POST['parent'];
            $Desc = $_POST['description'];
            $Order = $_POST['ordering'];
            $Visible = $_POST['visible'];
            $Comment = $_POST['comment'];
            $Ads = $_POST['ads'];

            $count = checkInsert("select Name from categories where Name = ", $Name);
            if ($count == 1) {
                redirectHome("<div class='alert alert-danger'>Sorry ! this category ' $Name ' already exist please change the category</div>", "categories.php");
            } else {
                //insert to db

                $commande = $connection->prepare('insert into categories(Name, subCategory, Description, Ordering, Visibility,AllowComment,AllowAds) values ( ? ,? ,?, ?, ?, ?,? )');
                $commande->execute(array($Name, $parent, $Desc, $Order, $Visible, $Comment, $Ads));

                redirectHome('<div class="alert alert-success">row changed or updated : ' . $commande->rowCount() . '</div>', 'categories.php');
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

        $commande = $connection->prepare('select * from categories where id=?');
        $commande->execute(array($id));
        $row = $commande->fetch();
        $count = $commande->rowCount();

        if ($count == 1) {
        ?>
            <!-- work in edit page-->
            <h1>Edit Category</h1>
            <div class="container">
                <form action="?g=Update" method="POST" class="form-horizontal">
                    <div class="form-group form-group-lg">
                        <input type="hidden" name="id" value='<?php echo $id ?>'>
                        <label class="col-sm-4 control-label">Name</label>
                        <div class="col-sm-6 col-md-12">
                            <input type="text" name="name" autocomplete="off" placeholder="Category name" required class="form-control" value="<?php echo $row['Name']; ?>">
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label class="col-sm-4 control-label">Description</label>
                        <div class="col-sm-6 col-md-12">
                            <textarea name="description" maxlength="100" placeholder="Category Description max(100)" class="form-control"><?php echo $row['Description']; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label class="col-sm-4 control-label">Ordering</label>
                        <div class="col-sm-6 col-md-12">
                            <input type="text" name="ordering" class="form-control" value="<?php echo $row['Ordering']; ?>">
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-4 control-label">Sub Category of :</label>
                        <div class="col-sm-6 col-md-12">
                            <select name="parent" class="form-control">
                                <option value="0">None</option>
                                <?php

                                $commande = $connection->prepare("select * from categories where subCategory = 0");
                                $commande->execute();
                                $Categories = $commande->fetchAll();

                                foreach ($Categories as $cat) {
                                    if ($cat['ID'] == $row['subCategory']) {
                                        echo "<option value='{$cat['ID']}' selected>{$cat['Name']}</option>";
                                    } else {
                                        echo "<option value='{$cat['ID']}'>{$cat['Name']}</option>";
                                    }
                                }
                                ?>
                            </select>

                        </div>
                    </div>

                    <div class="form-group form-group-lg ">
                        <label class="col-sm-4 col-form-label">Visible :</label>
                        <div class="col-sm-8">
                            <div class="form-check-inline">
                                <label for="vis-yes">
                                    <input type="radio" name="visible" value="0" id="vis-yes" <?php echo ($row['Visibility'] == 0) ? 'checked' : ''; ?>>
                                    Yes</label>
                            </div>
                            <div class="form-check-inline">
                                <label for="vis-no">
                                    <input type="radio" name="visible" value="1" id="vis-no" <?php echo $row['Visibility'] == 1 ? 'checked' : ''; ?>>
                                    No</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-lg ">
                        <label class="col-sm-4 col-form-label">Comment :</label>
                        <div class="col-sm-8">
                            <div class="form-check-inline"> 
                                <label for="com-yes">
                                    <input type="radio" name="comment" value="0" id="com-yes" <?php echo ($row['AllowComment'] == 0) ? 'checked' : ''; ?>>
                                    Yes</label>
                            </div>
                            <div class="form-check-inline">
                                <label for="com-no">
                                    <input type="radio" name="comment" value="1" id="com-no" <?php echo $row['AllowComment'] == 1 ? 'checked' : ''; ?>>
                                    No</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-lg ">
                        <label class="col-sm-4 col-form-label">Allow Ads :</label>
                        <div class="col-sm-8">
                            <div class="form-check-inline">
                                <label for="ads-yes">
                                    <input type="radio" name="ads" value="0" id="ads-yes" <?php echo ($row['AllowAds'] == 0) ? 'checked' : ''; ?>>
                                    Yes</label>
                            </div>
                            <div class="form-check-inline">
                                <label for="ads-no">
                                    <input type="radio" name="ads" value="1" id="ads-no" <?php echo $row['AllowAds'] == 1 ? 'checked' : ''; ?>>
                                    No</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value=" Update " class="btn btn-primary">
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

        //Update

    } elseif ($g == 'Update') {
        echo '<h1>Update Category</h1>';
        echo '<div class="container">';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $id          = $_POST['id'];
            $name        = $_POST['name'];
            $parent      = $_POST['parent'];
            $description = $_POST['description'];
            $ordering    = $_POST['ordering'];
            $visible     = $_POST['visible'];
            $comment     = $_POST['comment'];
            $ads         = $_POST['ads'];

            $Errors = array();
            if (empty($name)) {
                $Errors[] = 'enter something in name!!<br>';
            }

            foreach ($Errors as $err) {
                echo '<div class="alert alert-danger">' . $err . '</div>';
            }

            if (empty($Errors)) {
                //update db
                $commande = $connection->prepare('update categories set Name=?, subCategory=?, Description=?, Ordering = ?, Visibility=? , AllowComment=?, AllowAds=? where id=?');
                $commande->execute(array($name, $parent, $description, $ordering, $visible, $comment, $ads, $id));

                redirectHome("<div class='alert alert-success'>row changed or updated {$commande->rowCount()} </div>", "categories.php");
            }
        } else {
            redirectHome("<div class='alert alert-danger'>Error!! </div>", "index.php");
        }
        echo '</div>';
    } elseif ($g == 'Delete') {
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id = intval($_GET['id']);
        } else   $id = 0;

        $commande = $connection->prepare('select * from categories where id=?');
        $commande->execute(array($id));
        echo '<div class="container">';
        if ($commande->rowCount() == 1) {
            $commande = $connection->prepare("delete from categories where id = {$id}");
            $commande->execute();

            redirectHome("<div class='alert alert-success'> row Deleted {$commande->rowCount()} </div>", "categories.php");
        } else {
            redirectHome("<div class='alert alert-danger'>Delete Something Wrong!!</div>", "categories.php");
        }
        echo '</div>';
    }
    include "include/template/footer.php";
} else {
    header('Location:index.php');
    exit();
}
