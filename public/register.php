<?php
require_once("../includes/bootstrap.php");
require_once("../includes/Layout/Header.php"); 

$account = User::fetch();

if ($account !== false) {
    header("Location: index.php");
    die();
}

if (isset($_POST["username"]) && isset($_POST["password"])) {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $user = User::register($username, $password);

    if ($user["type"] == "error") {
        header("Location: register.php?error=1");
        die();
    }

    header("Location: index.php");
    die();
}
?>

<?php if (isset($_GET["error"])) { ?>
<h2 class="text-error text-center font-bold text-2xl">Failed to register!</h2>
<?php } ?>

<form action="register.php" method="post">
    <label for="username">Username</label>
    <input type="text" name="username" required />

    <label for="password">Password</label>
    <input type="password" name="password" required />

    <input type="submit" value="Submit" />
</form>

<?php require_once("../includes/Layout/Footer.php"); ?>
