<?php
header("Content-Type: text/html; charset=UTF-8");

require_once "hapi.php";

$book = new Book();

echo $book->query();
?>