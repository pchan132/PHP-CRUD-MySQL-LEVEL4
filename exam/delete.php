<?php
include 'db.php';

$id = $_GET['id'] ?? 0;

// ลบข้อมูลแบบ Prepared Statement (ปลอดภัย)
$stmt = $conn->prepare("DELETE FROM shippingcompany WHERE ShippingCompanyID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: list.php");
?>
