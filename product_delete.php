<?php
include 'db.php';
$id = $_GET['id'] ?? 0;
$stmt = $conn->prepare("DELETE FROM Products WHERE ProductID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
header("Location: products.php");
?>
