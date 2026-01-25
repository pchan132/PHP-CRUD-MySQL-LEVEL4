<?php
    include 'db.php';

    $id = $_GET['id'] ?? '';
    $product = null; // ตัวแปรเก็บข้อมูลสินค้า

    // ตรวจสอบว่ามีการส่ง id มาหรือไม่
    if ($id != ''){
        $sql = "SELECT * FROM products WHERE ProductID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        // get_result คือ ฟังก์ชันที่ใช้ดึงผลลัพธ์จากการรันคำสั่งที่เตรียมไว้ ทำงานโดยการคืนค่าเป็นวัตถุผลลัพธ์ (result object)
        $result = $stmt->get_result();

        // fetch_assoc() คือ ฟังก์ชันที่ใช้ดึงแถวข้อมูลถัดไปจากชุดผลลัพธ์ที่ได้มา และคืนค่าเป็นอาร์เรย์แบบเชื่อมโยง (associative array)
        $product = $result->fetch_assoc();
    }

    // แสดงข้อมูลสินค้า (สำหรับตรวจสอบ) ตาม id ที่ส่งมา
    // echo '<pre>';
    // print_r($product);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_form.css">
    <title>
        <?php
            echo ($product != null) ? "Edit Product" : "Create a new Product";
        ?>
    </title>
</head>
<body class="form-body">
    <div class="form-container">
        <h1 class="form-title">
            <?php
            // เปลี่ยนหัวข้อฟอร์มตามสถานะการแก้ไขหรือสร้างใหม่
            // ถ้ามีข้อมูลสินค้า (แก้ไข) ให้แสดง "Edit Product" ถ้าไม่มี (สร้างใหม่) ให้แสดง "Create a new Product"
                echo ($product != null) ? "Edit Product" : "Create a new Product";
            ?>
        </h1>

        <form class="product-form" method="POST" action="product_save.php">
            <input type="hidden" name="ProductID" value="<?php echo htmlspecialchars($product['ProductID'] ?? ''); ?>">

            <div class="form-group">
                <label for="ProductName" class="form-label">Product Name:</label>
                <input type="text" id="ProductName" name="ProductName" class="form-input" value="<?php echo htmlspecialchars($product['ProductName'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="Picture" class="form-label">Picture URL:</label>
                <input type="text" id="Picture" name="Picture" class="form-input" value="<?php echo htmlspecialchars($product['Picture'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="Category" class="form-label">Category:</label>
                <input type="text" id="Category" name="Category" class="form-input" value="<?php echo htmlspecialchars($product['Category'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="ProductDescription" class="form-label">Product Description:</label>
                <textarea id="ProductDescription" name="ProductDescription" class="form-textarea"><?php echo htmlspecialchars($product['ProductDescription'] ?? ''); ?></textarea>
            </div>

            <div class="form-group">
                <label for="Price" class="form-label">Price:</label>
                <input type="number" id="Price" step="0.01" name="Price" class="form-input" value="<?php echo htmlspecialchars($product['Price'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="QuantityStock" class="form-label">Quantity:</label>
                <input type="number" id="QuantityStock" step="1" name="QuantityStock" class="form-input" value="<?php echo htmlspecialchars($product['QuantityStock'] ?? ''); ?>" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="form-submit">Save</button>
                <a href="index.php" class="form-cancel">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>