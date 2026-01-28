<?php
include 'db.php';
$orderId = $_GET['order_id'] ?? 0;
$productId = $_GET['product_id'] ?? 0;

$stmt = $conn->prepare("DELETE FROM OrderLine WHERE OrderID = ? AND ProductID = ?");
$stmt->bind_param("ii", $orderId, $productId);
$stmt->execute();

header("Location: orderlines.php?order_id=$orderId");
?>
