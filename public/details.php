<?php
require_once("../includes/bootstrap.php");
require_once("../includes/Layout/Header.php");

$user = User::fetch();
$prev = "";

if (!isset($_GET["kanji"])) {
    header("Location: search.php");
    die();
}

if (isset($_GET["prev"])) {
    $prev = urlencode($_GET["prev"]);
}

// Get kanji
$kanjis = Kanji::find("WHERE literal = :kanji LIMIT 1", [
    "kanji" => trim($_GET["kanji"])
]);

if (empty($kanjis)) {
    echo "<h2 class='text-center font-bold text-4xl opacity-60 my-8'>No results found!</h2>";

    require_once("../includes/Layout/Footer.php");
    die();
}

$kanji = $kanjis[0];

// Post comment
if (isset($_POST["comment"]) && $user !== false) {
    Comment::create(
        $kanji->id, $user["id"], trim($_POST["comment"])
    );
}

// Get comments
$comments = Comment::find($kanji->id);
?>

<div class="p-4">
    <button class="btn btn-error mt-4" type="button" onclick="location.href = 'search.php?search=<?php echo $prev ?>'">X</button>

    <div class="py-4 gap-4">
        <?php
            echo "<div class='sm:flex sm:w-[70%] py-8 m-auto gap-4 max-sm:text-center'>";
            {
                echo "<h2 class='font-bold text-8xl'>$kanji->literal</h2>";
                echo "<div>";
                {
                    echo "<h2 class='font-bold capitalize text-4xl'>$kanji->meaning</h2>";

                    // Tags
                    echo "<ul class='flex gap-2 py-2 max-sm:justify-center'>";
                    {
                        if (!empty($kanji->grade)) echo "<li class='btn btn-xs'><span class='font-bold'>Grade: </span>$kanji->grade</li>";
                        if (!empty($kanji->jlpt)) echo "<li class='btn btn-xs'><span class='font-bold'>JLPT: </span>$kanji->jlpt</li>";
                        if (!empty($kanji->freq)) echo "<li class='btn btn-xs'><span class='font-bold'>Frequency: </span>$kanji->freq</li>";
                    }
                    echo "</ul>";

                    // Meanings
                    echo "<div>";
                    {
                        if (!empty($kanji->kunyomi)) echo "<div><span class='font-bold text-primary'>訓読み: </span> $kanji->kunyomi</div>";
                        if (!empty($kanji->onyomi)) echo "<div><span class='font-bold text-secondary'>音読み: </span> $kanji->onyomi</div>";
                        if (!empty($kanji->nanori)) echo "<div><span class='font-bold'>名乗り: </span> $kanji->nanori</div>";
                    }
                    echo "</div>";
                }
                echo "</div>";
            }
            echo "</div>";
        ?>
    </div>

    <div class="sm:w-[70%] m-auto mt-8">
        <h2 class="font-bold text-4xl">Comments</h2>
        <div class="divider"></div>

        <?php
            foreach ($comments as $comment) {
                $safe_username = htmlspecialchars($comment->username);
                $safe_description = htmlspecialchars($comment->description);

                echo "<div class='mb-8'>";
                {
                    echo "<h2 class='text-accent text-sm font-bold'>$safe_username said:</h2>";
                    echo "<textarea class='textarea resize-none w-full my-2' readonly>$safe_description</textarea>";
                    echo "<p class='text-xs text-right opacity-60'>$comment->createdAt</p>";
                }
                echo "</div>";
            }

            if (empty($comments)) {
                echo "<h2 class='text-2xl opacity-60 font-bold text-center py-8'>No comments yet!</h2>";
            }
        ?>
        
        <div class="divider"></div>

        <?php if ($user !== false) { ?>
            <form class="p-1" method="post" >
                <p class="my-2 opacity-60 text-sm">Leave a comment!</p>

                <textarea
                    class="block resize-y w-full mt-4 textarea"
                    name="comment"
                    placeholder="Comment"></textarea>

                <div class="text-right">
                    <input class="btn btn-primary mt-4 max-sm:w-full" type="submit" value="Submit" />
                </div>
            </form>
        <?php } ?>
    </div>
</div>

<?php require_once("../includes/Layout/Footer.php"); ?>