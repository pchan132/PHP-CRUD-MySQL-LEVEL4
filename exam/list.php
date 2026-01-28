<?php
include 'db.php';

// รับค่าค้นหา
$kw = $_GET['kw'] ?? '';

// Query: ถ้ามีคำค้นหาก็ใช้ LIKE, ถ้าไม่มีก็ดึงทั้งหมด
if ($kw != '') {
    $stmt = $conn->prepare("SELECT * FROM shippingcompany WHERE CompanyName LIKE ?");
    $search = "%$kw%";
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM shippingcompany");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>รายการบริษัท</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #4CAF50; color: white; }
        .btn { padding: 5px 10px; text-decoration: none; margin: 2px; display: inline-block; }
        .add { background: #4CAF50; color: white; }
        .edit { background: #2196F3; color: white; }
        .del { background: #f44336; color: white; }
        input[type="text"] { padding: 8px; width: 200px; }
        button { padding: 8px 15px; }
    </style>
</head>
<body>
    <h1>บริษัทขนส่ง</h1>
    
    <!-- ฟอร์มค้นหา -->
    <form method="GET">
        <input type="text" name="kw" value="<?= htmlspecialchars($kw) ?>" placeholder="ค้นหาชื่อบริษัท...">
        <button type="submit">ค้นหา</button>
        <a href="list.php" class="btn">Reset</a>
        <a href="form.php" class="btn add">+ เพิ่มใหม่</a>
    </form>
    <br>
    
    <!-- ตารางแสดงข้อมูล -->
    <table>
        <tr>
            <th>#</th>
            <th>ชื่อบริษัท</th>
            <th>ที่อยู่</th>
            <th>เมือง</th>
            <th>ประเทศ</th>
            <th>จัดการ</th>
        </tr>
        <?php $i = 1; while ($r = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $i++ ?></td>
            <td><?= htmlspecialchars($r['CompanyName']) ?></td>
            <td><?= htmlspecialchars($r['Address']) ?></td>
            <td><?= htmlspecialchars($r['City']) ?></td>
            <td><?= htmlspecialchars($r['Country']) ?></td>
            <td>
                <a href="form.php?id=<?= $r['ShippingCompanyID'] ?>" class="btn edit">แก้ไข</a>
                <a href="delete.php?id=<?= $r['ShippingCompanyID'] ?>" class="btn del" onclick="return confirm('ลบข้อมูลนี้?')">ลบ</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
