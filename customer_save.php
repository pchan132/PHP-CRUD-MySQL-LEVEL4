<?php 
    session_start(); // เริ่ม session เพื่อเก็บ error messages
    include 'db.php';
    include 'validate.php';
    
    $CustomerID = $_POST['CustomerID'] ?? '';
    $CustomerName = $_POST['CustomerName'] ?? '';
    $AddressLine1 = $_POST['AddressLine1'] ?? '';
    $City = $_POST['City'] ?? '';
    $Country = $_POST['Country'] ?? '';
    $PostalCode = $_POST['PostalCode'] ?? '';
    $MobilePhone = $_POST['MobilePhone'] ?? '';
    
    // เก็บ Error messages
    $CustomerNameErr=$AddressLine1Err=$CityErr=$CountryErr=$PostalCodeErr=$MobilePhoneErr="";

    // ตรวจสอบข้อมูลที่ส่งมา
    // ตรวจสอบชื่อ customer
    if (!req($CustomerName)) {
        $CustomerNameErr = "ต้องระบุชื่อ customer";
    } elseif (!str_len($CustomerName, 50)) {
        $CustomerNameErr = "ชื่อต้องไม่เกิน 50 ตัวอักษร";
    }
    // ตรวจสอบที่อยู่
    if (!req($AddressLine1)) {
        $AddressLine1Err = "ต้องระบุที่อยู่";
    } elseif (!str_len($AddressLine1, 50)) {
        $AddressLine1Err = "ที่อยู่ต้องไม่เกิน 50 ตัวอักษร";
    }
    // ตรวจสอบเมือง
    if (!str_len($City, 50)) {
        $CityErr = "เมืองต้องไม่เกิน 50 ตัวอักษร";
    }

    // ตรวจสอบประเทศ
    if (!req($Country)) {
        $CountryErr = "ต้องระบุประเทศ";
    } elseif (!str_len($Country, 50)) {
        $CountryErr = "ประเทศต้องไม่เกิน 50 ตัวอักษร";
    }
    // ตรวจสอบรหัสไปรษณีย์
    if (!req($PostalCode)) {
        $PostalCodeErr = "ต้องระบุรหัสไปรษณีย์";
    } elseif (!str_len($PostalCode, 5)) {
        $PostalCodeErr = "รหัสไปรษณีย์ต้องไม่เกิน 5 ตัวอักษร";
    }
    // ตรวจสอบเบอร์โทรศัพท์มือถือ
    if (!req($MobilePhone)) {
        $MobilePhoneErr = "ต้องระบุเบอร์โทรศัพท์มือถือ";
    } elseif (!str_len($MobilePhone, 12)) {
        $MobilePhoneErr = "เบอร์โทรศัพท์มือถือไม่เกิน 12 ตัวอักษร";
    }

    // ถ้ามีข้อผิดพลาดใด ๆ ให้แสดงผลลัพธ์และหยุดการทำงาน
    if ($CustomerNameErr || $AddressLine1Err || $CityErr || $CountryErr || $PostalCodeErr || $MobilePhoneErr) {
        // เก็บข้อผิดพลาดใน session
        $_SESSION['errors'] = [
            'CustomerName' => $CustomerNameErr,
            'AddressLine1' => $AddressLine1Err,
            'City' => $CityErr,
            'Country' => $CountryErr,
            'PostalCode' => $PostalCodeErr,
            'MobilePhone' => $MobilePhoneErr
        ];
        // เก็บข้อมูลเก่าใน session
        $_SESSION['old'] = $_POST;
        // ส่งกลับไปยังฟอร์ม
        header("Location: customer_form.php" . ($CustomerID ? "?id=$CustomerID" : ""));
        exit();
    }

    // ถ้าไม่มีข้อผิดพลาด ให้บันทึกข้อมูลลงฐานข้อมูล
    if ($CustomerID != '') {
        // อัปเดตข้อมูล customer
        $stmt = $conn->prepare("UPDATE customers SET CustomerName=?, AddressLine1=?, City=?, Country=?, PostalCode=?, MobilePhone=? WHERE CustomerID=?");
        $stmt->bind_param("ssssssi", $CustomerName, $AddressLine1, $City, $Country, $PostalCode, $MobilePhone, $CustomerID);
    } else {
        // เพิ่ม customer ใหม่
        $stmt = $conn->prepare("INSERT INTO customers (CustomerName, AddressLine1, City, Country, PostalCode, MobilePhone) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $CustomerName, $AddressLine1, $City, $Country, $PostalCode, $MobilePhone);
    }
    $stmt->execute(); // รันคำสั่ง SQL
    $stmt->close(); // ปิดคำสั่งที่เตรียมไว้
    $conn->close(); // ปิดการเชื่อมต่อฐานข้อมูล

    // เปลี่ยนเส้นทางกลับไปยังหน้ารายการลูกค้า
    header("Location: customer.php");
?>