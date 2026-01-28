<?php
include 'db.php';
$id = $_GET['id'] ?? 0;
$stmt = $conn->prepare("DELETE FROM Customers WHERE CustomerID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
header("Location: customers.php");
?>
