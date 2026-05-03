<?php
require_once("../includes/bootstrap.php");
require_once("../includes/Layout/Header.php"); 

if (!isset($_GET["search"])) {
    header("Location: index.php");
    die();
}

$chars = mb_str_split($_GET["search"]);
$placeholders = implode(",", array_fill(0, count($chars), "?"));

if (count($chars) > 20 || count($chars) == 0) {
    header("Location: index.php");
    die();
}

$kanjis = Kanji::find("WHERE literal IN ($placeholders)", $chars);
?>

<div class="p-4">
    <form class="text-center flex gap-2" method="get" action="search.php">
        <label class="input w-full">
            <svg
                class="h-[1em] opacity-50"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
            >
                <g
                    stroke-linejoin="round"
                    stroke-linecap="round"
                    stroke-width="2.5"
                    fill="none"
                    stroke="currentColor"
                >
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.3-4.3"></path>
                </g>
            </svg>

            <input
                type="text"
                name="search"
                placeholder="Search"
                value="<?php echo htmlspecialchars($_GET["search"]); ?>"
                required
            />
        </label>

        <input type="submit" class="btn btn-secondary" value="Search" />
    </form>

    <button class="btn btn-error mt-4" type="button" onclick="location.href = 'index.php'">X</button>

    <div class="py-4 gap-4">
        <?php
            if (empty($kanjis)) {
                echo "<h2 class='text-center font-bold text-4xl opacity-60'>No results found!</h2>";
            }

            foreach ($kanjis as $kanji) {
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
            }
        ?>
    </div>
</div>

<?php require_once("../includes/Layout/Footer.php"); ?>
