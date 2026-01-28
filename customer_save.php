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
elseif (strlen($name) > 50) $err['CustomerName'] = "Max 50 characters";

if ($addr == '') $err['AddressLine1'] = "Address is required";
elseif (strlen($addr) > 50) $err['AddressLine1'] = "Max 50 characters";

if ($country == '') $err['Country'] = "Country is required";
elseif (strlen($country) > 50) $err['Country'] = "Max 50 characters";

if ($postal == '') $err['PostalCode'] = "Postal Code is required";
elseif (strlen($postal) != 5) $err['PostalCode'] = "Must be 5 digits";

if ($phone == '') $err['MobilePhone'] = "Mobile Phone is required";
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
