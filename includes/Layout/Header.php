<!doctype html>
<html lang="en" data-theme="dark" class="bg-base-300">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Kanji Database</title>

        <link
            href="https://cdn.jsdelivr.net/npm/daisyui@5"
            rel="stylesheet"
            type="text/css"
        />

        <link
            href="style/main.css"
            rel="stylesheet"
            type="text/css"
        />

        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    </head>
    <body>
        <div class="text-right fixed w-full bottom-2 right-4">
            <?php if (User::fetch() === false) { ?>
                <a class="text-primary opacity-60 hover:opacity-100" href="register.php">Register</a>
                <a class="text-secondary opacity-60 hover:opacity-100"  href="login.php">Login</a>
            <?php } else { ?>
                <a class="text-primary opacity-60 hover:opacity-100" href="logout.php">Logout</a>
            <?php } ?>

            <?php var_dump(User::fetch()) ?>
        </div>
