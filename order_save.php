<?php
session_start();
include 'db.php';

$id = $_POST['OrderID'] ?? '';
$custId = $_POST['CustomerID'] ?? '';
$shipId = $_POST['ShippingCompanyID'] ?? '';
$datetime = $_POST['OrderDateTime'] ?? '';

// Validate
$err = [];
if ($custId == '') $err['CustomerID'] = "Please select a customer";
if ($shipId == '') $err['ShippingCompanyID'] = "Please select a shipping company";
if ($datetime == '') $err['OrderDateTime'] = "Order DateTime is required";

// Error -> back to form
if (!empty($err)) {
    $_SESSION['err'] = $err;
    $_SESSION['old'] = $_POST;
    header("Location: order_form.php" . ($id ? "?id=$id" : ""));
    exit;
}

// Save
if ($id != '') {
    $stmt = $conn->prepare("UPDATE Orders SET CustomerID=?, ShippingCompanyID=?, OrderDateTime=? WHERE OrderID=?");
    $stmt->bind_param("iisi", $custId, $shipId, $datetime, $id);
} else {
    $stmt = $conn->prepare("INSERT INTO Orders (CustomerID, ShippingCompanyID, OrderDateTime) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $custId, $shipId, $datetime);
}
$stmt->execute();

header("Location: orders.php");
?>
