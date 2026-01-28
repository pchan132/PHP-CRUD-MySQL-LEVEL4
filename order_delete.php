<?php
include 'db.php';
$id = $_GET['id'] ?? 0;

// Check if order has order lines
$stmt = $conn->prepare("SELECT COUNT(*) as cnt FROM OrderLine WHERE OrderID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if ($result['cnt'] > 0) {
    echo "<script>alert('ไม่สามารถลบข้อมูล Order ได้ เนื่องจากมี Order Lines อยู่'); window.location.href='orders.php';</script>";
    exit;
}

$stmt = $conn->prepare("DELETE FROM Orders WHERE OrderID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
header("Location: orders.php");
?>
