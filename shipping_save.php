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
elseif (strlen($name) > 50) $err['CompanyName'] = "Max 50 characters";

if ($addr == '') $err['Address'] = "Address is required";
elseif (strlen($addr) > 50) $err['Address'] = "Max 50 characters";

if ($country == '') $err['Country'] = "Country is required";
elseif (strlen($country) > 50) $err['Country'] = "Max 50 characters";

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
