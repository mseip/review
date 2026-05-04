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

    $user = User::login($username, $password);

    if ($user["type"] == "error") {
        header("Location: login.php?error=1");
        die();
    }

    header("Location: index.php");
    die();
}
?>

<div class="text-center">
    <h1 class="sm:text-5xl font-bold text-center max-sm:text-4xl mt-8">
        <span>Kanji</span><span class="text-primary">Database</span>
    </h1>

    <h2 class="text-xl max-sm:text-sm mb-8 mt-4 opacity-80 text-secondary font-bold">Login to an existing account</h2>

    <?php if (isset($_GET["error"])) { ?>
        <h2 class="text-error text-center font-bold mb-4 text-2xl">Failed to login!</h2>
    <?php } ?>

    <form class="flex justify-center" action="login.php" method="post">
        <fieldset class="fieldset bg-base-200 border-base-300 rounded-box w-xs border p-4">
            <legend class="fieldset-legend">Login</legend>

            <label class="label" for="username">Username</label>
            <input type="text" class="input" placeholder="Username" name="username" required />

            <label class="label" for="password">Password</label>
            <input type="password" class="input" placeholder="Password" name="password" required />

            <input type="submit" value="Login" class="btn btn-neutral mt-4" />
        </fieldset>
    </form>
</div>

<?php require_once("../includes/Layout/Footer.php"); ?>