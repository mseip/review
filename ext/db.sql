create table kanji (
    id INT AUTO_INCREMENT PRIMARY KEY,
    literal VARCHAR(2),
    grade INT DEFAULT -1,
    jlpt INT DEFAULT -1,
    freq INT DEFAULT -1,
    meaning VARCHAR(350) DEFAULT "",
    onyomi VARCHAR(350) DEFAULT "",
    kunyomi VARCHAR(350) DEFAULT "",
    nanori VARCHAR(350) DEFAULT ""
);

