<?php
    session_start(); // เริ่ม session เพื่อเก็บ error messages
    include 'db.php';
    include 'validate.php';

    $ProductID = $_POST['ProductID'] ?? '';
    $ProductName = $_POST['ProductName'] ?? '';
    $Picture = $_POST['Picture'] ?? '';
    $Category = $_POST['Category'] ?? '';
    $ProductDescription = $_POST['ProductDescription'] ?? '';
    $Price = $_POST['Price'] ?? 0;
    $QuantityStock = $_POST['QuantityStock'] ?? 0;

    // เก็บ Error messages
    $ProductNameErr=$productNameErr=$PictureErr=$CategoryErr=$ProductDescriptionErr=$PriceErr=$QuantityStockErr="";

    // ตรวจสอบข้อมูลที่ส่งมา
    // ตรวจสอบชื่อสินค้า
    if (!req($ProductName)) {
        $ProductNameErr = "ต้องระบุชื่อสินค้า";
    } elseif (!str_len($ProductName, 50)) {
        $ProductNameErr = "ชื่อต้องไม่เกิน 50 ตัวอักษร";
    }

    // ตรวจสอบ URL ของรูปภาพ
    if (!req($Picture)) {
        $PictureErr = "ต้องระบุ URL ของรูปภาพ";
    } elseif (!url($Picture) || !str_len($Picture, 100)) {
        $PictureErr = "รูปแบบ URL ของรูปภาพไม่ถูกต้อง หรือยาวเกิน 100 ตัวอักษร";
    }

    // ตรวจสอบหมวดหมู่
    if (!req($Category)) {
        $CategoryErr = "ต้องระบุหมวดหมู่";
    } elseif (!str_len($Category, 50)) {
        $CategoryErr = "หมวดหมู่ต้องไม่เกิน 50 ตัวอักษร";
    }

    // ตรวจสอบคำอธิบายสินค้า
    if (!str_len($ProductDescription, 250)) {
        $ProductDescriptionErr = "คำอธิบายสินค้าต้องไม่เกิน 250 ตัวอักษร";
    }

    // ตรวจสอบราคา
    if (!req($Price)) {
        $PriceErr = "ต้องระบุราคา";
    } elseif (!num($Price, 4)) {
        $PriceErr = "ราคาต้องเป็นตัวเลขและไม่เกิน 4 หลัก";
    }

    // ตรวจสอบจำนวนในสต็อก
    if (!req($QuantityStock)) {
        $QuantityStockErr = "ต้องระบุจำนวนในสต็อก";
    } elseif (!num($QuantityStock, 3)) {
        $QuantityStockErr = "จำนวนในสต็อกต้องเป็นตัวเลขและไม่เกิน 3 หลัก";
    }

    // ถ้ามีข้อผิดพลาดใด ๆ ให้แสดงผลลัพธ์และหยุดการทำงาน
    if ($ProductNameErr || $PictureErr || $CategoryErr || $ProductDescriptionErr || $PriceErr || $QuantityStockErr) {
        
        // เก็บข้อผิดพลาดไว้ใน session
        $_SESSION['errors'] = [
            'ProductName' => $ProductNameErr,
            'Picture' => $PictureErr,
            'Category' => $CategoryErr,
            'ProductDescription' => $ProductDescriptionErr,
            'Price' => $PriceErr,
            'QuantityStock' => $QuantityStockErr
        ];

        // เก็บข้อมูลเดิมไว้ใน session
        $_SESSION['old'] = [
            'ProductID' => $ProductID,
            'ProductName' => $ProductName,
            'Picture' => $Picture,
            'Category' => $Category,
            'ProductDescription' => $ProductDescription,
            'Price' => $Price,
            'QuantityStock' => $QuantityStock
        ];

        header("Location: product_form.php");

        exit();
    }

    if ($ProductID != '') {
        // อัปเดตข้อมูลสินค้า
        $stmt = $conn->prepare("UPDATE products SET ProductName=?, Picture=?, Category=?, ProductDescription=?, Price=?, QuantityStock=? WHERE ProductID=?");
        $stmt->bind_param("ssssdii", $ProductName, $Picture, $Category, $ProductDescription, $Price, $QuantityStock, $ProductID);
    } else {
        // เพิ่มสินค้ารายการใหม่
        $stmt = $conn->prepare("INSERT INTO products (ProductName, Picture, Category, ProductDescription, Price, QuantityStock) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssdi", $ProductName, $Picture, $Category, $ProductDescription, $Price, $QuantityStock);
    }
    $stmt->execute();
    $stmt->close();
    $conn->close();

    // เปลี่ยนเส้นทางกลับไปยังหน้ารายการสินค้า
    header("Location: index.php");
?>