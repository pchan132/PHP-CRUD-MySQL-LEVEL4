<?php
session_start();
include 'db.php';

$id = $_POST['ProductID'] ?? '';
$name = trim($_POST['ProductName'] ?? '');
$pic = trim($_POST['Picture'] ?? '');
$cat = trim($_POST['Category'] ?? '');
$desc = trim($_POST['ProductDescription'] ?? '');
$price = $_POST['Price'] ?? '';
$stock = $_POST['QuantityStock'] ?? '';

// Validate
$err = [];
if ($name == '') $err['ProductName'] = "Product Name is required";
elseif (mb_strlen($name) > 50) $err['ProductName'] = "ต้องไม่เกิน 50 ตัวอักษร (ขณะนี้ " . mb_strlen($name) . " ตัว)";

if ($pic == '') $err['Picture'] = "Picture URL is required";
elseif (mb_strlen($pic) > 100) $err['Picture'] = "ต้องไม่เกิน 100 ตัวอักษร (ขณะนี้ " . mb_strlen($pic) . " ตัว)";

if ($cat == '') $err['Category'] = "Category is required";
elseif (mb_strlen($cat) > 50) $err['Category'] = "ต้องไม่เกิน 50 ตัวอักษร (ขณะนี้ " . mb_strlen($cat) . " ตัว)";

if (mb_strlen($desc) > 250) $err['ProductDescription'] = "ต้องไม่เกิน 250 ตัวอักษร (ขณะนี้ " . mb_strlen($desc) . " ตัว)";

if ($price === '' || !is_numeric($price)) $err['Price'] = "Price is required (number)";
elseif ($price < 0 || $price > 9999) $err['Price'] = "Price must be 0-9999";

if ($stock === '' || !is_numeric($stock)) $err['QuantityStock'] = "Stock is required (number)";
elseif ($stock < 0 || $stock > 999) $err['QuantityStock'] = "Stock must be 0-999";

// Error -> back to form
if (!empty($err)) {
    $_SESSION['err'] = $err;
    $_SESSION['old'] = $_POST;
    header("Location: product_form.php" . ($id ? "?id=$id" : ""));
    exit;
}

// Save
if ($id != '') {
    $stmt = $conn->prepare("UPDATE Products SET ProductName=?, Picture=?, Category=?, ProductDescription=?, Price=?, QuantityStock=? WHERE ProductID=?");
    $stmt->bind_param("ssssiis", $name, $pic, $cat, $desc, $price, $stock, $id);
} else {
    $stmt = $conn->prepare("INSERT INTO Products (ProductName, Picture, Category, ProductDescription, Price, QuantityStock) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssii", $name, $pic, $cat, $desc, $price, $stock);
}
$stmt->execute();

header("Location: products.php");
?>
