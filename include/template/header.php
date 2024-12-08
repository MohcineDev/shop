<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="layout\css\bootstrap.css">
    <link rel="stylesheet" href="layout\css\bootstrap.min.css">
    <link rel="stylesheet" href="layout\css\mystyle.css">
    <link rel="stylesheet" href="layout\css\all.css">

    <title><?php getTitle(); ?></title>
</head>

<body>

    <nav class="navbar navbar-expand-lg justify-content-between navbar-dark bg-dark">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="index.php">Home</a>

        <div class="collapse navbar-collapse  " id="navbarSupportedContent">
            <ul class="navbar-nav nav">
                <?php
                $categories = getLatestx();

                foreach ($categories as $category) {
                    echo "<li><a href='categories.php?g={$category['ID']}' class='nav-link'>
                    {$category['Name']}</a></li>";
                }
                ?>
            </ul>
        </div>
        <div>
            <?php
            if (isset($_SESSION['normalUser'])) {
                $a = intval($_SESSION['userId']);

                echo ' <img src="control/profilePics/' . getUserPic($a) . '" class="profile-img img-fluid rounded-circle">';
            ?>
                <div class="btn-group myinfo dropdown">
                    <span class="btn btn-default dropdown-toggle">
                        <?php echo 'Welcome ' . $_SESSION['normalUser']; ?>
                    </span>
                    <ul class="dropdown-menu">
                        <li><a href="profile.php">Profile</a></li>
                        <li><a href="AddAds.php">Add New Item</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                </div>

            <?php
                $regStatu = checkRegStatu($_SESSION['normalUser']);

                if ($regStatu == 1) {
                }
            } else {
                echo '<a href="login.php">
                         <span class="pull-right">Login / Register</span>
                         </a>';
            }
            ?>
        </div>
    </nav>