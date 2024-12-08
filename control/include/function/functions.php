<?php


// get latest items, users, comments
function getLatest($columns, $table, $orderBy, $limit = 10)
{
    global $connection;

    $Commande = $connection->prepare("select {$columns} from {$table} order by $orderBy desc limit {$limit} ");
    $Commande->execute();
    $values = $Commande->fetchAll();
    return $values;
}

//redirect

function redirectHome($msg, $url = 'dashboard.php', $sec = 5)
{
    echo $msg;
    $page = str_replace('.php', '', $url);
    echo "<div class='alert alert-info'>you will Redirected to the $page Page after $sec seconds!!</div>";

    header("refresh:$sec; url=$url");
}

//--------------

function getTitle()
{
    global $title;

    if (isset($title)) {

        echo $title;
    } else {
        echo 'Hi';
    }
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


function getCats()
{
    global $connection;

    $Commande = $connection->prepare("select * from categories ");
    $Commande->execute();
    $values = $Commande->fetchAll();
    return $values;
}
