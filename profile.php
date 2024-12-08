<?php
ob_start();
$title = 'Profile';

session_start();
include 'ini.php';
if (isset($_SESSION['normalUser'])) {

    $getUser = $connection->prepare('select * from users where username = ?');
    $getUser->execute(array($sessionUser));

    $infos = $getUser->fetch();

?>

    <h1 class="">My Profile</h1>
    <div class="infos">
        <div class="container">
            <div class="card border-secondary block">
                <div class="card-header text-white bg-secondary ">
                    My Information
                </div>
                <div class="card-body infos">
                    <ul class="list-unstyled">
                        <li><i class="fa fa-fw fa-unlock-alt"></i> <span>Username </span>: <?php echo $infos['Username'] . '<br>'; ?></li>
                        <li><i class="fa fa-fw fa-envelope"> </i> <span>Email </span>: <?php echo $infos['Email']; ?></li>
                        <li><i class="fa fa-fw fa-user"> </i> <span>Fullname </span>: <?php echo $infos['Fullname']; ?></li>
                        <li><i class="fa fa-fw fa-calendar-alt"> </i> <span>Registered Date </span>: <?php echo $infos['registeredDate']; ?></li>
                    </ul>
                    <br>
                    <button class="btn btn-outline-success">Edit Profile</button>
                </div>
            </div>
        </div>
    </div>
    <div class="ads">
        <div class="container">
            <div class="card border-dark block">
                <div class="card-header text-white bg-dark ">
                    My Items
                </div>
                <div class="card-body">
                    <?php

                    $items = getItems('Member_ID', $infos['ID']);
                    if (empty($items)) {
                        echo '<p>No Ads ! - <a href="AddAds.php">Add Ads</a></p>';
                    } else {
                        echo '<div class="row">';

                        foreach ($items as $item) {
                    ?>
                            <div class="col-sm-6 col-md-3">
                                <div class="img-thumbnail item-thumbnail">
                                    <?php echo "<p class='price'>{$item['Price']} $</p>";
                                    if ($item['Approved'] == 0) {

                                        echo '<i class="fa fa-cloud-upload-alt" title="still not approved by the admin"></i>';
                                    }
                                    if( !empty($item['item_pic'])){

                                        echo '<img src="itemsPics/' . $item['item_pic'] . '" class="img-fluid">';
                                    }
                                    else{
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
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="comms">
        <div class="container">
            <div class="card border-primary block">
                <div class="card-header text-white bg-primary">
                    My Comments
                </div>
                <div class="card-body">
                    <?php
                    $commande = $connection->prepare('select Comment from comments where User_id = ?');
                    $commande->execute(array($infos['ID']));
                    $cmnt = $commande->fetchAll();
                    if (empty($cmnt)) {

                        echo '<p>No Comments !</p>';
                    } else {
                        foreach ($cmnt as $cm) {
                            echo '<p>' . $cm['Comment'] . '</p>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>


<?php
} else {
    header('Location: login.php');
    exit();
}
ob_end_flush();
include "include/template/footer.php";
