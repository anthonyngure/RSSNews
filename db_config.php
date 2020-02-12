<?php

require_once 'errors.php';
try {
    $host = "localhost";
    $user = "toshngure";
    $password = "";
    $database = "dambaal_news";
    $link = mysqli_connect($host, $user, $password, $database);
} catch (Exception $ex) {
    badRequest($ex->getMessage());
}
