<?php
session_start();
include 'db.php';

// ดึง errors และ old data จาก session
$err = $_SESSION['err'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['err'], $_SESSION['old']);

// ถ้ามี id = โหมดแก้ไข, ดึงข้อมูลเดิม
$id = $_GET['id'] ?? '';
$data = null;

if ($id != '') {
    $stmt = $conn->prepare("SELECT * FROM shippingcompany WHERE ShippingCompanyID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();
}

// ฟังก์ชันดึงค่า: old > data > ว่าง
function val($key, $old, $data) {
    return htmlspecialchars($old[$key] ?? $data[$key] ?? '');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $data ? 'แก้ไข' : 'เพิ่ม' ?>บริษัท</title>
    <style>
        .form-group { margin: 10px 0; }
        label { display: block; margin-bottom: 5px; }
        input { padding: 8px; width: 300px; }
        .error { color: red; font-size: 12px; }
        .btn { padding: 10px 20px; margin: 5px; cursor: pointer; }
        .save { background: #4CAF50; color: white; border: none; }
        .cancel { background: #9e9e9e; color: white; border: none; }
        .del { background: #f44336; color: white; text-decoration: none; }
    </style>
</head>
<body>
    <h1><?= $data ? 'แก้ไข' : 'เพิ่ม' ?>บริษัทขนส่ง</h1>
    
    <form method="POST" action="save.php">
        <!-- Hidden ID สำหรับ Update -->
        <input type="hidden" name="id" value="<?= val('ShippingCompanyID', $old, $data) ?>">
        
        <div class="form-group">
            <label>ชื่อบริษัท:</label>
            <input type="text" name="CompanyName" value="<?= val('CompanyName', $old, $data) ?>" required>
            <?php if (!empty($err['CompanyName'])): ?>
                <div class="error"><?= $err['CompanyName'] ?></div>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label>ที่อยู่:</label>
            <input type="text" name="Address" value="<?= val('Address', $old, $data) ?>" required>
            <?php if (!empty($err['Address'])): ?>
                <div class="error"><?= $err['Address'] ?></div>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label>เมือง:</label>
            <input type="text" name="City" value="<?= val('City', $old, $data) ?>">
            <?php if (!empty($err['City'])): ?>
                <div class="error"><?= $err['City'] ?></div>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label>ประเทศ:</label>
            <input type="text" name="Country" value="<?= val('Country', $old, $data) ?>" required>
            <?php if (!empty($err['Country'])): ?>
                <div class="error"><?= $err['Country'] ?></div>
            <?php endif; ?>
        </div>
        
        <div>
            <button type="submit" class="btn save">บันทึก</button>
            <a href="list.php" class="btn cancel">ยกเลิก</a>
            <?php if ($data): ?>
                <a href="delete.php?id=<?= $data['ShippingCompanyID'] ?>" class="btn del" onclick="return confirm('ลบข้อมูลนี้?')">ลบ</a>
            <?php endif; ?>
        </div>
    </form>
</body>
</html>
