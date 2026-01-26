<?php
include 'db.php';
$id = $_GET['id'];
$conn->query("DELETE FROM products WHERE ProductID=$id");
header("location:index.php");
exit();
