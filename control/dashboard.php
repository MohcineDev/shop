<?php
ob_start();
session_start();

if (isset($_SESSION['username'])) {
    $title = 'Dashboard';

    include 'ini.php';
?>
    <div class="container first text-center">
        <h1>Welcome to the Dashboard</h1>

        <div class="row">
            <div class="col-md-3">
                <div class="stat">Total Members<span><a href="members.php"><?php echo getCountItems('id', 'users'); ?></a></span></div>
            </div>
            <div class="col-md-3">
                <div class="stat">Pending Members<span><a href="members.php?g=Manage&side=pending"><?php echo checkInsert('select * from users where registeredStatus=', 0) ?></a></span></div>
            </div>
            <div class="col-md-3">
                <div class="stat">Total Items<span><a href="items.php"><?php echo getCountItems('item_id', 'items'); ?></a></span></div>
            </div>
            <div class="col-md-3">
                <div class="stat">Total Comments<span><a href="comments.php"><?php echo getCountItems('id', 'comments'); ?></a></span></div>
            </div>
        </div>
    </div>

    <div class="container latest">
        <div class="row">
            <div class="col-sm-6">
                <div class="card card-default">
                    <div class="card-header"> Latest 10 Registered Users </div>
                    <div class="card-body">
                        <ul class="list-unstyled users-list">
                            <?php
                            $lastUsers = getLatest('id, Username, registeredStatus', 'users', 'id');
                            foreach ($lastUsers as $username) {
                                echo "<li>" . $username['Username'] . " <a href='members.php?g=Edit&id="
                                    . $username['id'] . "'> <span class='btn btn-primary float-right'><i class='far fa-edit'></i> Edit User</span> </a> ";
                                //add active btn if registeredStatus = 0
                                if ($username['registeredStatus'] == 0) {
                                    echo "<a class='btn btn-success float-right' href='members.php?g=Active&id=" . $username["id"] . "'  ><i class='fa fa-check'></i> Active</a>";
                                }
                                echo "</li>";
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card card-default">
                    <div class="card-header"> Latest 10 Added Items </div>
                    <div class="card-body">
                        <ul class="list-unstyled users-list">
                            <?php
                            $latestItems = getLatest('item_id, Name, Approved', 'items', 'item_id');
                            foreach ($latestItems as $item) {
                                echo "<li>{$item['Name']} <a href='items.php?g=Edit&id={$item['item_id']}'><span class='btn btn-primary float-right'><i class='far fa-edit'></i> Edit Item</span></a>";
                                if ($item['Approved'] == 0) {
                                    echo "<a class='btn btn-success float-right' href='items.php?g=Approve&id={$item['item_id']}'><i class='fa fa-check'></i> Approve</a>";
                                }
                                echo '</li>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
    include "include/template/footer.php";
} else {
    header('Location:index.php');
    exit();
}
