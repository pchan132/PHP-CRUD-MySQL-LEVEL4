<?php
include 'db.php';
$id = $_GET['id'] ?? 0;
$stmt = $conn->prepare("DELETE FROM ShippingCompany WHERE ShippingCompanyID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
header("Location: shipping.php");
?>
