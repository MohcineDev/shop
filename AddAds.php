<?php
ob_start();
$title = 'Add New Item';
session_start();

include 'ini.php';
if (isset($_SESSION['normalUser'])) {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $pic         = $_FILES['item-pic'];
        $picName = $pic['name'];
        $pic['type'];
        $tempName = $pic['tmp_name'];
        $picsize = $pic['size'];

        $allowedFilesExtension = array('png', 'jpg', 'jpeg');
        $ex = explode('.', $picName);
        $picExtension = strtolower(end($ex));

        $name        = filter_var($_POST['name'], FILTER_SANITIZE_STRING); // clean the string from special chars
        $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
        $price       = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
        $country     = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
        $status      = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
        $category    = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
        $tags        = filter_var($_POST['tags'], FILTER_SANITIZE_STRING);


        $Errors = array();

        if (!in_array($picExtension, $allowedFilesExtension)) {
            $Errors[] = 'please choose a valid picture';
        }
        if ($picsize > 4194304) {
            $Errors[] = 'picture must be less than 4 MB';
        }

        if (strlen($name) < 2) {
            $Errors[] = 'enter a valid name';
        }

        if (strlen($description) < 10) {
            $Errors[] = 'enter a valid description';
        }

        if (strlen($country) < 2) {
            $Errors[] = 'enter a valid country';
        }

        if (empty($price)) {
            $Errors[] = 'enter something in price';
        }
        if (empty($status)) {
            $Errors[] = 'enter something in status';
        }
        if (empty($category)) {
            $Errors[] = 'enter something in category';
        }

        if (empty($Errors)) {

            $pictureName = rand(0, 1000) . '-' . $picName;
            move_uploaded_file($tempName, 'itemsPics\\' . $pictureName); //Moves an uploaded file to a new location

            $commande = $connection->prepare('insert into items(Name, Description, Price , Country_Made,Status, Add_Date, Cat_ID, Member_ID, tags,item_pic) values (?,?,?,?,?,now(), ?,?,?,?)');
            $commande->execute(array($name, $description, $price, $country, $status, $category, $_SESSION['userId'], $tags, $pictureName));

            if ($commande) {
                echo '<div class="alert alert-success">item Added! need to be approved</div>';
            }
        }
    }

?>
    <h1>Add New Item</h1>
    <div class="infos">
        <div class="container">
            <div class="card border-success block">
                <div class="card-header text-white bg-success">Add New Item</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST"  enctype="multipart/form-data" class="form-horizontal">

                                <div class="form-group form-group-lg">
                                    <label class="col-sm-5 control-label">Name :</label>
                                    <div class="col-sm-10 col-md-8">
                                        <input type="text" name="name" autocomplete="off" placeholder="Item name" class="form-control live-name">
                                    </div>
                                </div>
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-5 control-label">Description :</label>
                                    <div class="col-sm-10 col-md-8">
                                        <textarea name="description" maxlength="100" placeholder="Category Description max(100)" class="form-control live-desc"></textarea>

                                    </div>
                                </div>

                                <div class="form-group form-group-lg">
                                    <label class="col-sm-5 control-label">Price :</label>
                                    <div class="col-sm-10 col-md-8">
                                        <input type="text" name="price" autocomplete="off" placeholder="Item Price" class="form-control live-price">
                                    </div>
                                </div>

                                <div class="form-group form-group-lg">
                                    <label class="col-sm-5 control-label">Country :</label>
                                    <div class="col-sm-10 col-md-8">
                                        <input type="text" name="country" autocomplete="off" placeholder="Item Country" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group form-group-lg">
                                    <label class="col-sm-5 control-label">Status :</label>
                                    <div class="col-sm-10 col-md-8">
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
                                    <div class="col-sm-10 col-md-8">
                                        <select name="category" class="form-control">
                                            <option value="0">...</option>
                                            <?php
                                            $commande = $connection->prepare('select * from categories');
                                            $commande->execute();
                                            $cats = $commande->fetchAll();
                                            foreach ($cats as $cat) {
                                                echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
                                            }
                                            ?>
                                        </select>

                                    </div>
                                </div>

                                <div class="form-group form-group-lg">
                                    <label class="col-sm-5 control-label">Item Picture :</label>
                                    <div class="col-sm-10 col-md-8">
                                        <input type="file" name="item-pic" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group form-group-lg">
                                    <label class="col-sm-5 control-label">Tags :</label>
                                    <div class="col-sm-10 col-md-8">
                                        <input type="text" name="tags" autocomplete="off" placeholder="Item Tags" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10 col-md-8">
                                        <input type="submit" value=" Add Item" class="btn btn-primary btn-block">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-4">
                            <div class="img-thumbnail item-thumbnail live-preview">
                                <p class='price'></p>
                                <img src="layout\img\300.png" class="img-fluid">
                                <div class="caption">
                                    <h3></h3>
                                    <p></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                if (!empty($Errors)) {
                    foreach ($Errors as $err) {
                        echo '<div class="alert alert-danger">' . $err . '</div>';
                    }
                }

                ?>
            </div>
        </div>
    </div>

<?php
} else {
    header('Location: login.php');
    exit();
}
include "include/template/footer.php";
ob_end_flush();
