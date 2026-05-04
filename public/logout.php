<?php
require_once("../includes/bootstrap.php");
require_once("../includes/Layout/Header.php"); 

$account = User::fetch();

if ($account !== false) {
    User::logout();
    header("Location: index.php");
    die();
}

?>

<h2 class="font-bold text-center py-8">Logging out...</h2>

<?php require_once("../includes/Layout/Footer.php"); ?>
