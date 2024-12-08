<?php

// get latest items, users, comments
function getLatestx()
{
    global $connection;

    $Commande = $connection->prepare("select * from categories where subCategory = 0");
    $Commande->execute();
    $values = $Commande->fetchAll();
    return $values;
}

function getItems($where = '', $value = '', $approve = '')
{
    global $connection;
    $query = '';

    if ($approve == 1) {
        $query = 'and Approved = 1';
    }

    $Commande = $connection->prepare("SELECT * from items where $where = ? $query");
    $Commande->execute(array($value));
    $values = $Commande->fetchAll();
    return $values;
}

function getItemsBySubCat($where = '')
{
    global $connection;

    $Commande = $connection->prepare("SELECT * from items WHERE Cat_id IN (SELECT id from categories WHERE subCategory = ?) and Approved = 1");
    $Commande->execute(array($where));
    $values = $Commande->fetchAll();
    return $values;
}

function getItemsbyTag($tag)
{
    global $connection;

    $Commande = $connection->prepare("SELECT * from items where tags like '%{$tag}%'");
    $Commande->execute();
    $values = $Commande->fetchAll();
    return $values;
}

function getIAlltems($approve = '')
{
    global $connection;
    $query = '';

    if ($approve == 1) {
        $query = 'where Approved = 1';
    }

    $Commande = $connection->prepare("select * from items $query");
    $Commande->execute();
    $values = $Commande->fetchAll();
    return $values;
}

#check the user registered statu
function checkRegStatu($name)
{
    global $connection;

    $Commande = $connection->prepare('select Username, registeredStatus from users where Username = ? and registeredStatus = 0');
    $Commande->execute(array($name));
    $statu = $Commande->rowCount();

    return $statu;
}

//check if username exist in db
function checkusername($name)
{
    //global : to access the variable $connection declared in connect.php file
    global $connection;

    $Commande = $connection->prepare('select username from users where username = ?');
    $Commande->execute(array($name));
    $count = $Commande->rowCount();

    return $count;
}



function getTitle()
{
    global $title;

    if (isset($title)) {

        echo $title;
    } else {
        echo 'Shop';
    }
}
function getUserPic($id)
{
    global $connection;

    $Commande = $connection->prepare('select profile_pic from users where ID = ?');
    $Commande->execute(array($id));
    $pic = $Commande->fetchAll();

    if (!empty($pic))
        return $pic[0][0];
    else
        return '0.png';
}

################################################"

//redirect after error

function redirectHome($msg, $url = null, $sec = 3)
{
    $page = '';
    echo $msg;
    if ($url == 'members.php?g=Add') {
        $page = 'Manage Members';
    } elseif ($url == 'items.php?g=Insert') {

        $page = 'Manage Members';
    } elseif ($url == 'categories.php') {

        $page = 'Manage Categories';
    } elseif ($url == 'items.php') {
        $page = 'Manage items';
    } elseif ($url == 'comments.php') {

        $page = 'Manage comments';
    } else {
        $url = 'index.php';
        $page = 'Dashboard';
    }

    echo "<div class='alert alert-info'>Redirect to the $page Page after $sec seconds!!</div>";

    header("refresh:$sec; url=$url");
}


//check if item exist in db & insert function
function checkInsert($myCommande, $value)
{
    //global : to access the variable $connection declared in connect.php file
    global $connection;

    $Commande = $connection->prepare($myCommande . "?");
    $Commande->execute(array($value));
    $count = $Commande->rowCount();

    return $count;
}

//get items count

function getCountItems($item, $table)
{
    global $connection;
    $Commande = $connection->prepare("select COUNT($item) from {$table}");
    $Commande->execute();
    return $Commande->fetchColumn();
}
