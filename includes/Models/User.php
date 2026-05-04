<?php
class User {
    private static function setSession(
        string $id,
        string $username
    ) {
        if (session_status() === PHP_SESSION_NONE) {
            throw new \Exception("No session!");
        }

        session_regenerate_id(true);

        $_SESSION["user_id"] = $id;
        $_SESSION["user_name"] = $username;
    }

    private static function removeSession() {
        if (session_status() === PHP_SESSION_NONE) {
            throw new \Exception("No session!");
        }

        session_regenerate_id(true);

        // Clear
        $_SESSION["user_id"] = null;
        $_SESSION["user_name"] = null;
    }

    public static function register(
        string $username,
        string $password
    ) {
        global $dbc;

        $username = trim($username);
        $password = trim($password);
        
        $hash = password_hash($password, PASSWORD_DEFAULT);

        if (!$hash) {
            return [
                "type" => "error",
                "message" => "invalid hash!"
            ];
        }

        $exists = $dbc->fetchArray("SELECT 1 FROM users WHERE username = :username", [
            "username" => $username
        ]);

        // Could in theory make `username` unique, but I don't care
        if (!empty($exists)) {
            return [
                "type" => "error",
                "message" => "duplicate username!"
            ];
        }

        try {
            $dbc->sqlQuery("INSERT INTO users (username, password) VALUES (:username, :password)", [
                "username" => $username,
                "password" => $hash
            ]);

            $id = $dbc->connection->lastInsertId();
            User::setSession($id, $username);

            return [
                "type" => "success",
                "message" => [
                    "id" => $id,
                    "username" => $username
                ]
            ];
        } catch (Exception $e) {
            return false;
        }
    }

    public static function login(
        string $username,
        string $password
    ) {
        global $dbc;

        $username = trim($username);
        $password = trim($password);
        
        $hash = password_hash($password, PASSWORD_DEFAULT);

        if (!$hash) {
            return [
                "type" => "error",
                "message" => "invalid hash!"
            ];
        }

        try {
            $search = $dbc->fetchArray("SELECT id, username, password FROM users WHERE username = :username LIMIT 1", [
                "username" => $username
            ]);

            if (empty($search)) {
                return [
                    "type" => "error",
                    "message" => "User does not exist!"
                ];
            }

            $search = $search[0];

            if (!password_verify($password, $search["password"])) {
                return [
                    "type" => "error",
                    "message" => "Invalid password!"
                ];
            }

            User::setSession($search["id"], $search["username"]);

            return [
                "type" => "success",
                "message" => [
                    "id" => $search["id"],
                    "username" => $search["username"]
                ]
            ];
        } catch (Exception $e) {
            return false;
        }
    }

    public static function fetch()
    {
        if (
            !isset($_SESSION["user_id"]) ||
            !isset($_SESSION["user_name"])
        ) return false;

        return [
            "id" => $_SESSION["user_id"],
            "username" => $_SESSION["user_name"]
        ];
    }

    public static function logout()
    {
        User::removeSession();
    }
}