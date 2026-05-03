<?php
require_once("../includes/bootstrap.php");

$file = "assets/kanjidic2.xml";
$xml = simplexml_load_file($file) or die("No KDB!");

$misc = ["grade", "jlpt", "freq"];

foreach ($xml->character as $item) {
    $kanji = [
        "literal" => (string) $item->literal,
        "meaning" => "",
        "misc" => [],
        "meaning" => [],
        "reading" => [
            "onyomi" => [],
            "kunyomi" => [],
            "nanori" => []
        ]
    ];

    // Misc
    foreach ($misc as $key) {
        $kanji["misc"]["$key"] = (int) $item->misc?->{$key};
    }

    // Meaning
    foreach ($item->reading_meaning->rmgroup->meaning as $meaning) {
        if (!isset($meaning["m_lang"])) {
            $kanji["meaning"][] = (string) $meaning;
        }
    }

    // Reading 音読み・訓読み
    foreach ($item->reading_meaning->rmgroup->reading as $reading) {
        if ($reading["r_type"] == "ja_on") $kanji["reading"]["onyomi"][] = (string) $reading;
        if ($reading["r_type"] == "ja_kun") $kanji["reading"]["kunyomi"][] = (string) $reading;
    }

    // Reading 名乗り
    foreach ($item->reading_meaning->nanori as $nanori) {
        $kanji["reading"]["nanori"][] = (string) $nanori;
    }

    $dbc->sqlQuery("
        INSERT INTO kanji (
            literal, grade, jlpt, freq, meaning, onyomi,
            kunyomi, nanori
        ) VALUES (
            :literal, :grade, :jlpt, :freq, :meaning, :onyomi,
            :kunyomi, :nanori
        )
    ", [
        "literal" => $kanji["literal"] ?? "",
        "grade" => $kanji["misc"]["grade"] ?? 0,
        "jlpt" => $kanji["misc"]["jlpt"] ?? 0,
        "freq" => $kanji["misc"]["freq"] ?? 0,
        "meaning" => join(", ", $kanji["meaning"] ?? []),
        "onyomi" => join(", ", $kanji["reading"]["onyomi"] ?? []),
        "kunyomi" => join(", ", $kanji["reading"]["kunyomi"] ?? []),
        "nanori" => join(", ", $kanji["reading"]["nanori"] ?? [])
    ]);

    echo "Processed " . $kanji["literal"] . "<br />";
}
?>
