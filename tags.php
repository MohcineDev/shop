<?php
ob_start();
session_start();
$title = 'Tags';
include 'ini.php';

if (isset($_GET['name'])) {

    echo "<h1>{$_GET['name']}</h1>";
?>
    <div class="container">
        <div class="row">
            <?php
            $items = getItemsbyTag($_GET['name']);
             
            foreach ($items as $item) {
            ?>
                <div class="col-sm-6 col-md-3">
                    <div class="img-thumbnail item-thumbnail">
                        <?php echo "<p class='price'>{$item['Price']}</p>"; ?>
                        <img src="layout\img\300.png" class="img-fluid">
                        <div class="caption">
                            <?php
                            echo "<h3><a href='itemDetails.php?id={$item['item_ID']}'>{$item['Name']}</a></h3>";
                            echo "<p>{$item['Description']}</p>";
                            echo "<p class='item-date'>{$item['Add_Date']}</p>";

                            ?>
                        </div>
                    </div>
                </div>

            <?php
            }
            ?>
        </div>
    </div>


<?php
} else {
    echo 'Something Wrong!!';
}
ob_end_flush();

include "include/template/footer.php";

?>