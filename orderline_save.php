<?php
session_start();
include 'db.php';

$orderId = $_POST['OrderID'] ?? 0;
$productId = $_POST['ProductID'] ?? '';
$qty = $_POST['Quantity'] ?? '';

// Validate
$err = [];
if ($productId == '') $err['ProductID'] = "Please select a product";
if ($qty === '' || !is_numeric($qty) || $qty < 1 || $qty > 999) $err['Quantity'] = "Quantity must be 1-999";

// Error -> back to form
if (!empty($err)) {
    $_SESSION['err'] = $err;
    $_SESSION['old'] = $_POST;
    header("Location: orderline_form.php?order_id=$orderId");
    exit;
}

// Check if product already exists in order
$stmt = $conn->prepare("SELECT * FROM OrderLine WHERE OrderID = ? AND ProductID = ?");
$stmt->bind_param("ii", $orderId, $productId);
$stmt->execute();
$existing = $stmt->get_result()->fetch_assoc();

if ($existing) {
    // Update quantity
    $newQty = $existing['Quantity'] + $qty;
    $stmt = $conn->prepare("UPDATE OrderLine SET Quantity = ? WHERE OrderID = ? AND ProductID = ?");
    $stmt->bind_param("iii", $newQty, $orderId, $productId);
} else {
    // Insert new
    $stmt = $conn->prepare("INSERT INTO OrderLine (OrderID, ProductID, Quantity) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $orderId, $productId, $qty);
}
$stmt->execute();

header("Location: orderlines.php?order_id=$orderId");
?>
