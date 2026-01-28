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
        $sql = "SELECT * FROM shippingcompany WHERE ShippingCompanyID = ?";
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
    <title>Shipping Company Form</title>
</head>
<body class="form-body">
    <div class="form-container">
        <h1 class="form-title">

        <?php echo $shippingCompany ? "Edit Shipping Company" : "Add Shipping Company"; ?></h1>
        
        <form class="product-form" method="POST" action="shippingCompany_save.php">

        <!-- input ของ id -->
            <input type="hidden" name="ShippingCompanyID" value="<?php echo htmlspecialchars($old['ShippingCompanyID'] ?? $shippingCompany['ShippingCompanyID'] ?? ''); ?>">

        <!-- input ของชื่อบริษัท -->
            <div class="form-group">
                <label for="CompanyName">Company Name:</label>
                <input type="text" id="CompanyName" name="CompanyName" value="<?php echo htmlspecialchars($old['CompanyName'] ?? $shippingCompany['CompanyName'] ?? ''); ?>" class="form-input" required>

                <!-- แสดงข้อความ error ถ้ามี -->
                <?php if (!empty($errors['CompanyNameErr'])): ?>
                    <div class="error-message"><?php echo htmlspecialchars($errors['CompanyNameErr']); ?></div>
                <?php endif; ?>
            </div>
            
            <!-- input ของที่อยู่ -->
            <div class="form-group">
                <label for="Address">Address:</label>
                <input type="text" id="Address" name="Address" value="<?php echo htmlspecialchars($old['Address'] ?? $shippingCompany['Address'] ?? ''); ?>" class="form-input" required>

                <!-- แสดงข้อความ error ถ้ามี -->
                <?php if (!empty($errors['AddressErr'])): ?>
                    <div class="error-message"><?php echo htmlspecialchars($errors['AddressErr']); ?></div>
                <?php endif; ?>
            </div>

            <!-- input ของเมือง -->
            <div class="form-group">
                <label for="City">City:</label>
                <input type="text" id="City" name="City" value="<?php echo htmlspecialchars($old['City'] ?? $shippingCompany['City'] ?? ''); ?>" class="form-input" required>

                <!-- แสดงข้อความ error ถ้ามี -->
                <?php if (!empty($errors['CityErr'])): 
                    ?>
                    <div class="error-message"><?php echo htmlspecialchars($errors['CityErr']); ?></div>
                <?php endif; ?>
            </div>

            <!-- input ของประเทศ -->
            <div class="form-group">
                <label for="Country">Country:</label>
                <input type="text" id="Country" name="Country" value="<?php echo htmlspecialchars($old['Country'] ?? $shippingCompany['Country'] ?? ''); ?>" class="form-input" required>

                <!-- แสดงข้อความ error ถ้ามี -->
                <?php if (!empty($errors['CountryErr'])): ?>
                    <div class="error-message"><?php echo htmlspecialchars($errors['CountryErr']); ?></div>
                <?php endif; ?>
            </div>
            
            <!-- save button -->
            <div class="form-actions">
                <!-- delete -->
                <?php
                    if ($shippingCompany != null) {
                        echo '<a href="shippingCompany_delete.php?id=' . htmlspecialchars($shippingCompany['ShippingCompanyID']) . '" class="formDelete" onclick="return confirm(\'Are you sure you want to delete this shipping company?\')">Delete</a>';
                    }
                ?>
                <button type="submit" class="form-submit">
                    Save
                </button>

                <button type="button" class="form-cancel" onclick="window.location.href='shippingCompanies.php'">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</body>
</html>