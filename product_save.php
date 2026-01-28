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
elseif (strlen($name) > 50) $err['ProductName'] = "Max 50 characters";

if ($pic == '') $err['Picture'] = "Picture URL is required";
elseif (strlen($pic) > 100) $err['Picture'] = "Max 100 characters";

if ($cat == '') $err['Category'] = "Category is required";
elseif (strlen($cat) > 50) $err['Category'] = "Max 50 characters";

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
