<?php
require_once("config.php");

require_once("Utility/Database.php");

require_once("Models/Kanji.php");
require_once("Models/User.php");

session_start();
$dbc = new Database();
