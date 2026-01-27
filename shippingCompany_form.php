<?php 
    session_start();
    include 'db.php';

    // เก็บ Error messages
    $errors = $_SESSION['errors'] ?? [];
    // เก็บค่าที่ผู้ใช้กรอกไว้ก่อนหน้า    
    $old = $_SESSION['old'] ?? [];
    // ล้างค่า error messages และ old data หลังจากดึงมาใช้แล้ว
    unset($_SESSION['errors'], $_SESSION['old']);
    
    // ดึงข้อมูล shipping company จากฐานข้อมูลถ้ามีการส่ง id มาหา ผ่าน URL
    $id = $_GET['id'] ?? '';
    $shippingCompany = null; // ตัวแปรเก็บข้อมูล shipping company

    // ตรวจสอบว่ามีการส่ง id มาหรือไม่
    if ($id != ''){
        $sql = "SELECT * FROM ShippingCompany WHERE ShippingCompanyID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        // get_result คือ ฟังก์ชันที่ใช้ดึงผลลัพธ์จากการรันคำสั่งที่เตรียมไว้ ทำงานโดยการคืนค่าเป็นวัตถุผลลัพธ์ (result object)
        $result = $stmt->get_result();

        // fetch_assoc() คือ ฟังก์ชันที่ใช้ดึงแถวข้อมูลถัดไปจากชุดผลลัพธ์ที่ได้มา และคืนค่าเป็นอาร์เรย์แบบเชื่อมโยง (associative array)
        $shippingCompany = $result->fetch_assoc();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_form.css">
    <title>shipping Company Form</title>
</head>
<body class="form-body">
    <div class="form-container">
        <h1 class="form-title">
        <?php echo $shippingCompany ? "Edit Shipping Company" : "Add Shipping Company"; ?></h1>

        <form class="product-form" method="POST" action="shippingCompany_save.php">

        <!-- input ของชื่อบริษัท -->
            <div class="form-group">
                <label for="CompanyName">Company Name:</label>
                <input type="text" id="CompanyName" name="CompanyName" value="<?php echo htmlspecialchars($shippingCompany['CompanyName'] ?? $old['CompanyName'] ?? ''); ?>" class="form-input" required>

                <!-- แสดงข้อความ error ถ้ามี -->
                <?php if (isset($errors['CompanyName'])): ?>
                    <div class="error-message"><?php echo htmlspecialchars($errors['CompanyName']); ?></div>
                <?php endif; ?>
            </div>
            
            <!-- input ของที่อยู่ -->
            <div class="form-group">
                <label for="Address">Address:</label>
                <input type="text" id="Address" name="Address" value="<?php echo htmlspecialchars($shippingCompany['Address'] ?? $old['Address'] ?? ''); ?>" class="form-input" required>

                <!-- แสดงข้อความ error ถ้ามี -->
                <?php if (isset($errors['Address'])): ?>
                    <div class="error-message"><?php echo htmlspecialchars($errors['Address']); ?></div>
                <?php endif; ?>
            </div>

            <!-- input ของเบอร์โทรศัพท์ -->
            <div class="form-group">
                <label for="Phone">Phone:</label>
                <input type="text" id="Phone" name="Phone" value="<?php echo htmlspecialchars($shippingCompany['Phone'] ?? $old['Phone'] ?? ''); ?>" class="form-input" required>

                <!-- แสดงข้อความ error ถ้ามี -->
                <?php if (isset($errors['Phone'])): ?>
                    <div class="error-message"><?php echo htmlspecialchars($errors['Phone']); ?></div>
                <?php endif; ?>
            </div>
                    
        </form>
    </div>
</body>
</html>