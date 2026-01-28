<?php
session_start();
include 'db.php';

$id = $_POST['CustomerID'] ?? '';
$name = trim($_POST['CustomerName'] ?? '');
$addr = trim($_POST['AddressLine1'] ?? '');
$city = trim($_POST['City'] ?? '');
$country = trim($_POST['Country'] ?? '');
$postal = trim($_POST['PostalCode'] ?? '');
$phone = trim($_POST['MobilePhone'] ?? '');

// Validate
$err = [];
if ($name == '') $err['CustomerName'] = "Customer Name is required";
elseif (mb_strlen($name) > 50) $err['CustomerName'] = "ต้องไม่เกิน 50 ตัวอักษร (ขณะนี้ " . mb_strlen($name) . " ตัว)";

if ($addr == '') $err['AddressLine1'] = "Address is required";
elseif (mb_strlen($addr) > 50) $err['AddressLine1'] = "ต้องไม่เกิน 50 ตัวอักษร (ขณะนี้ " . mb_strlen($addr) . " ตัว)";

if (mb_strlen($city) > 50) $err['City'] = "ต้องไม่เกิน 50 ตัวอักษร (ขณะนี้ " . mb_strlen($city) . " ตัว)";

if ($country == '') $err['Country'] = "Country is required";
elseif (mb_strlen($country) > 50) $err['Country'] = "ต้องไม่เกิน 50 ตัวอักษร (ขณะนี้ " . mb_strlen($country) . " ตัว)";

if ($postal == '') $err['PostalCode'] = "Postal Code is required";
elseif (mb_strlen($postal) != 5) $err['PostalCode'] = "ต้องเป็น 5 ตัวอักษรเท่านั้น (ขณะนี้ " . mb_strlen($postal) . " ตัว)";

if ($phone == '') $err['MobilePhone'] = "Mobile Phone is required";
elseif (mb_strlen($phone) > 12) $err['MobilePhone'] = "ต้องไม่เกิน 12 ตัวอักษร (ขณะนี้ " . mb_strlen($phone) . " ตัว)";
elseif (!preg_match('/^0[0-9]{2}-[0-9]{3}-[0-9]{4}$/', $phone)) $err['MobilePhone'] = "Format: 0XX-XXX-XXXX (e.g., 081-234-5678)";

// Error -> back to form
if (!empty($err)) {
    $_SESSION['err'] = $err;
    $_SESSION['old'] = $_POST;
    header("Location: customer_form.php" . ($id ? "?id=$id" : ""));
    exit;
}

// Save
if ($id != '') {
    $stmt = $conn->prepare("UPDATE Customers SET CustomerName=?, AddressLine1=?, City=?, Country=?, PostalCode=?, MobilePhone=? WHERE CustomerID=?");
    $stmt->bind_param("ssssssi", $name, $addr, $city, $country, $postal, $phone, $id);
} else {
    $stmt = $conn->prepare("INSERT INTO Customers (CustomerName, AddressLine1, City, Country, PostalCode, MobilePhone) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $addr, $city, $country, $postal, $phone);
}
$stmt->execute();

header("Location: customers.php");
?>
