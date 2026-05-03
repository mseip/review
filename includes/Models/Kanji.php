<?php
class Kanji {
    public function __construct(
        public int $id,
        public string $literal,
        public int $grade,
        public int $jlpt,
        public int $freq,
        public string $meaning,
        public string $onyomi,
        public string $kunyomi,
        public string $nanori
    ) {}

    public static function find($query = "", $arguments = "") {
        global $dbc;

        $kanjis = $dbc->fetchArray("SELECT * FROM kanji $query", $arguments);
        $out = [];

        if (!$kanjis) return [];

        foreach ($kanjis as $kanji) {
            $out[] = new self(
                $kanji["id"],
                $kanji["literal"],
                $kanji["grade"],
                $kanji["jlpt"],
                $kanji["freq"],
                $kanji["meaning"],
                $kanji["onyomi"],
                $kanji["kunyomi"],
                $kanji["nanori"]
            );
        }

        return $out;
    }

    public function details() {
        return [
            "id" => self->id,
            "literal" => self->literal,
            "grade" => self->grade,
            "jlpt" => self->jlpt,
            "freq" => self->freq,
            "meaning" => self->meaning,
            "onyomi" => self->onyomi,
            "kunyomi" => self->kunyomi,
            "nanori" => self->nanori,
        ];
    }
}
