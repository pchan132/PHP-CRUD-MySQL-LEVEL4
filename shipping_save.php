<?php
session_start();
include 'db.php';

$id = $_POST['ShippingCompanyID'] ?? '';
$name = trim($_POST['CompanyName'] ?? '');
$addr = trim($_POST['Address'] ?? '');
$city = trim($_POST['City'] ?? '');
$country = trim($_POST['Country'] ?? '');

// Validate
$err = [];
if ($name == '') $err['CompanyName'] = "Company Name is required";
elseif (mb_strlen($name) > 50) $err['CompanyName'] = "ต้องไม่เกิน 50 ตัวอักษร (ขณะนี้ " . mb_strlen($name) . " ตัว)";

if ($addr == '') $err['Address'] = "Address is required";
elseif (mb_strlen($addr) > 50) $err['Address'] = "ต้องไม่เกิน 50 ตัวอักษร (ขณะนี้ " . mb_strlen($addr) . " ตัว)";

if (mb_strlen($city) > 50) $err['City'] = "ต้องไม่เกิน 50 ตัวอักษร (ขณะนี้ " . mb_strlen($city) . " ตัว)";

if ($country == '') $err['Country'] = "Country is required";
elseif (mb_strlen($country) > 50) $err['Country'] = "ต้องไม่เกิน 50 ตัวอักษร (ขณะนี้ " . mb_strlen($country) . " ตัว)";

// Error -> back to form
if (!empty($err)) {
    $_SESSION['err'] = $err;
    $_SESSION['old'] = $_POST;
    header("Location: shipping_form.php" . ($id ? "?id=$id" : ""));
    exit;
}

// Save
if ($id != '') {
    $stmt = $conn->prepare("UPDATE ShippingCompany SET CompanyName=?, Address=?, City=?, Country=? WHERE ShippingCompanyID=?");
    $stmt->bind_param("ssssi", $name, $addr, $city, $country, $id);
} else {
    $stmt = $conn->prepare("INSERT INTO ShippingCompany (CompanyName, Address, City, Country) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $addr, $city, $country);
}
$stmt->execute();

header("Location: shipping.php");
?>
