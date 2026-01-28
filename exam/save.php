<?php
session_start();
include 'db.php';

// รับค่าจากฟอร์ม
$id = $_POST['id'] ?? '';
$name = $_POST['CompanyName'] ?? '';
$addr = $_POST['Address'] ?? '';
$city = $_POST['City'] ?? '';
$country = $_POST['Country'] ?? '';

// Validate
$err = [];
if (trim($name) == '') $err['CompanyName'] = "กรุณากรอกชื่อบริษัท";
if (trim($addr) == '') $err['Address'] = "กรุณากรอกที่อยู่";
if (trim($country) == '') $err['Country'] = "กรุณากรอกประเทศ";

// ถ้ามี error -> กลับฟอร์ม
if (!empty($err)) {
    $_SESSION['err'] = $err;
    $_SESSION['old'] = $_POST;
    header("Location: form.php" . ($id ? "?id=$id" : ""));
    exit;
}

// บันทึก: Update หรือ Insert
if ($id != '') {
    // UPDATE
    $stmt = $conn->prepare("UPDATE shippingcompany SET CompanyName=?, Address=?, City=?, Country=? WHERE ShippingCompanyID=?");
    $stmt->bind_param("ssssi", $name, $addr, $city, $country, $id);
} else {
    // INSERT
    $stmt = $conn->prepare("INSERT INTO shippingcompany (CompanyName, Address, City, Country) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $addr, $city, $country);
}
$stmt->execute();

header("Location: list.php");
?>
