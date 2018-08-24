<?php
session_start();

require("scripts.txt");

$dbh = new PDO('mysql:host=localhost;dbname=workflow', 'wfuser', 'wfpass');

$stmt = $dbh->prepare("select * from users where username = :user and password = :pword");

$stmt->bindParam("user", $name);
$stmt->bindParam("pword", $pword);

$name = $_POST["username"];
$pword = $_POST["password"];

$stmt->execute();

$loginOK = false;

if ($row = $stmt->fetch()) {
    $loginOK = true;
    $_SESSION["username"] = $row["username"];
    $_SESSION["email"] = $row["email"];
}

$dbh = null;

include("top.txt");

if ($loginOK) {
    echo "You are logged in.  Thank you!";
} else {
    echo "There is no user account with that username and password.";
}

include("bottom.txt");
?>