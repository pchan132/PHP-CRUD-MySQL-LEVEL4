<?php 
session_start(); // เริ่ม session เพื่อรับค่า error messages

include 'db.php';

// เก็บ Error messages
$errors = $_SESSION['errors'] ?? [];
// เก็บค่าที่ผู้ใช้กรอกไว้ก่อนหน้า
$old = $_SESSION['old'] ?? [];
// ล้างค่า error messages และ old data หลังจากดึงมาใช้แล้ว
unset($_SESSION['errors'], $_SESSION['old']);

// ดึงข้อมูลลูกค้าจากฐานข้อมูลถ้ามีการส่ง id มาหา ผ่าน URL
$id = $_GET['id'] ?? '';
$customer = null; // ตัวแปรเก็บข้อมูลลูกค้า

// ตรวจสอบว่ามีการส่ง id มาหรือไม่
if ($id != ''){
    $sql = "SELECT * FROM customers WHERE CustomerID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // get_result คือ ฟังก์ชันที่ใช้ดึงผลลัพธ์จากการรันคำสั่งที่เตรียมไว้ ทำงานโดยการคืนค่าเป็นวัตถุผลลัพธ์ (result object)
    $result = $stmt->get_result();

    // fetch_assoc() คือ ฟังก์ชันที่ใช้ดึงแถวข้อมูลถัดไปจากชุดผลลัพธ์ที่ได้มา และคืนค่าเป็นอาร์เรย์แบบเชื่อมโยง (associative array)
    $customer = $result->fetch_assoc();
}

// แสดงข้อมูลลูกค้า (สำหรับตรวจสอบ) ตาม id ที่ส่งมา
// echo '<pre>';
// print_r($customer);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>customer</title>

    <link rel="stylesheet" href="style_form.css">
</head>
<body>
    <div class="form-container">
        <h1 class="form-title">
            <?php
            // เปลี่ยนหัวข้อฟอร์มตามสถานะการแก้ไขหรือสร้างใหม่
            // ถ้ามีข้อมูลลูกค้า (แก้ไข) ให้แสดง "Edit Customer" ถ้าไม่มี (สร้างใหม่) ให้แสดง "Create a new Customer"
                echo ($customer != null) ? "Edit Customer" : "Create a new Customer";
            ?>
        </h1>
    <form class="customer-form" method="POST" action="customer_save.php">
        <!-- input ของ CustomerID แต่ไม่แสดง -->
        <input type="hidden" name="CustomerID" value="<?php echo htmlspecialchars($customer['CustomerID'] ?? $old['CustomerID'] ?? ''); ?>">

        <div class="form-group">
        <!-- input ของ customer name -->
            <label for="CustomerName" class="form-label">Customer Name:</label>
            <input type="text" id="CustomerName" name="CustomerName" class="form-input"
                value="<?php 
                    echo htmlspecialchars(
                        $old['CustomerName'] ?? 
                        $customer['CustomerName'] ?? 
                        ''
                    ); 
                ?>">

                <!-- แสดงข้อความ error ถ้ามี -->
            <?php if (!empty($errors['CustomerName'])): ?>
                <div class="error-message"><?php echo htmlspecialchars($errors['CustomerName']); ?></div>
             <?php endif; ?>    
        </div>

        <div class="form-group">
            <!-- input ของ address line 1 -->
             <label for="AddressLine1">Address Line 1:</label>
             <input type="text" id="AddressLine1" name="AddressLine1" value="<?php echo htmlspecialchars($old['AddressLine1'] ?? $customer['AddressLine1'] ?? ''); ?>" class="form-input">
                
                <!-- แสดงข้อความ error ถ้ามี -->
                <?php if (!empty($errors['AddressLine1'])): ?>
                    <div class="error-message"><?php echo htmlspecialchars($errors['AddressLine1']); ?></div>
                 <?php endif; ?>
            </div>

            <div class="form-group">
                <!-- input ของ city -->
                <label for="City" class="form-label">City:</label>
                <input type="text" id="City" name="City" value="<?php echo htmlspecialchars($old['City'] ?? $customer['City'] ?? ''); ?>" class="form-input">

                <!-- แสดงข้อความ error ถ้ามี -->
                <?php if (!empty($errors['City'])): ?>
                    <div class="error-message"><?php echo htmlspecialchars($errors['City']); ?></div>
                 <?php endif; ?>
            </div>

            <div class="form-group">
                <!-- input ของ country -->
                <label for="Country" class="form-label">Country:</label>
                <input type="text" id="Country" name="Country" value="<?php echo htmlspecialchars($old['Country'] ?? $customer['Country'] ?? ''); ?>" class="form-input">
                
                <!-- แสดงข้อความ error ถ้ามี -->
                <?php if (!empty($errors['Country'])): ?>
                    <div class="error-message"><?php echo htmlspecialchars($errors['Country']); ?></div>
                 <?php endif; ?>
            </div>

            <div class="form-group">
                <!-- input ของ postal code -->
                <label for="PostalCode" class="form-label">Postal Code:</label>
                <input type="text" id="PostalCode" name="PostalCode" value="<?php echo htmlspecialchars($old['PostalCode'] ?? $customer['PostalCode'] ?? ''); ?>" class="form-input">
                
                <!-- แสดงข้อความ error ถ้ามี -->
                <?php if (!empty($errors['PostalCode'])): ?>
                    <div class="error-message"><?php echo htmlspecialchars($errors['PostalCode']); ?></div>
                 <?php endif; ?>
            </div>

            <div class="form-group">
                <!-- input ของ mobile phone -->
                <label for="MobilePhone" class="form-label">Mobile Phone:</label>
                <input type="text" id="MobilePhone" name="MobilePhone" value="<?php echo htmlspecialchars($old['MobilePhone'] ?? $customer['MobilePhone'] ?? ''); ?>" class="form-input">

                <!-- แสดงข้อความ error ถ้ามี -->
                <?php if (!empty($errors['MobilePhone'])): ?>
                    <div class="error-message"><?php echo htmlspecialchars($errors['MobilePhone']); ?></div>
                 <?php endif; ?>
            </div>

            <div class="form-actions">
                <!-- delete -->
                <?php if ($customer != null): ?>
                    <a href="customer_delete.php?id=<?php echo htmlspecialchars($customer['CustomerID']); ?>" class="formDelete" onclick="return confirm('Are you sure you want to delete this customer?');">Delete</a>
                <?php endif; ?>

                <!-- save -->
                <button type="submit" class="form-submit">Save</button>
                    <!-- cancel -->
                <a href="customer.php" class="form-cancel">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>