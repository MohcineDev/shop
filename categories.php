<?php
ob_start();
session_start();

include 'ini.php'; ?>
<div class="container">
    <!-- <h1>
        <?php #echo str_replace('-', ' ', $_GET['name']) 
        ?> -->
    </h1>
    <h1>items</h1>
    <div class="row">

        <?php
        $items = getItemsBySubCat($_GET['g']);
        foreach ($items as $item) {
        ?>
            <div class="col-sm-6 col-md-3">
                <div class="img-thumbnail item-thumbnail">
                    <?php echo "<p class='price'>{$item['Price']} $</p>";
                    if (!empty($item['item_pic'])) {

                        echo '<img src="itemsPics/' . $item['item_pic'] . '" class="img-fluid">';
                    } else {
                        echo '<img src="itemsPics/default.png" class="img-fluid">';
                    }
                    ?>
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

ob_end_flush();

include "include/template/footer.php";

?>