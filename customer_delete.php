<?php
include 'db.php';
$id = $_GET['id'];
$conn->query("DELETE FROM customers WHERE CustomerID=$id");
header("location:customer.php");
exit();