<?php
class Comment {
    public function __construct(
        public string $username,
        public string $description,
        public string $createdAt
    ) {}

    public static function find(int $id) {
        global $dbc;

        $comments = $dbc->fetchArray("
            SELECT 
                u.username, c.description, c.createdAt
            FROM comments c
            JOIN users u ON c.userID = u.id
            WHERE c.kanjiID = :id
            ORDER BY c.createdAt DESC
        ", [
            "id" => $id,
        ]);

        $out = [];

        if (!$comments) return [];

        foreach ($comments as $comment) {
            $out[] = new self(
                $comment["username"],
                $comment["description"],
                $comment["createdAt"]
            );
        }

        return $out;
    }
    
    public static function create(
        int $kanjiID,
        int $userID,
        string $comment
    ) {
        global $dbc;
        
        $comment = trim($comment);
        
        if (strlen($comment) >= 499 || strlen($comment) == 0) {
            return [
                "type" => "error",
                "message" => "Comment too long/short!"
            ];
        }
        
        // Does kanji exist?
        $existsKanji = $dbc->fetchArray("SELECT 1 FROM kanji WHERE id = :id LIMIT 1", ["id" => $kanjiID]);

        if (empty($existsKanji)) {
            return [
                "type" => "error",
                "message" => "Kanji does not exist."
            ];
        }

        // Does user exist?
        $existsUser = $dbc->fetchArray("SELECT 1 FROM users WHERE id = :id LIMIT 1", ["id" => $userID]);

        if (empty($existsUser)) {
            return [
                "type" => "error",
                "message" => "User does not exist."
            ];
        }

        // Insert comment
        try {
            $dbc->sqlQuery("INSERT INTO comments (userID, kanjiID, description) VALUES (:userID, :kanjiID, :description)", [
                "userID" => $userID,
                "kanjiID" => $kanjiID,
                "description" => $comment
            ]);

            return [
                "type" => "success",
                "message" => $dbc->connection->lastInsertId()
            ];
        } catch (Exception $e) {
            return [
                "type" => "error",
                "message" => "Internal error!"
            ];
        }
    }
}