<?php 
    session_start();    
    include 'db.php';
    include 'validate.php';

    // ShippingCompanyID
    // CompanyName
    // Address
    // City
    // Country
    $ShippingCompanyID = $_POST['ShippingCompanyID'] ?? '';
    $CompanyName = $_POST['CompanyName'] ?? '';
    $Address = $_POST['Address'] ?? '';
    $City = $_POST['City'] ?? '';
    $Country = $_POST['Country'] ?? '';

    // เก็บ Error messages
    $CompanyNameErr = $AddressErr = $CityErr = $CountryErr = "";

    // ตรวจสอบข้อมูลที่ส่งมา
    // ตรวจสอบชื่อบริษัท
    if (!req($CompanyName)) {
        $CompanyNameErr = "ต้องระบุชื่อบริษัท";
    } elseif (!str_len($CompanyName, 50)) {
        $CompanyNameErr = "ชื่อบริษัทต้องไม่เกิน 50 ตัวอักษร";
    }

    // ตรวจสอบที่อยู่
    if (!req($Address)) {
        $AddressErr = "ต้องระบุที่อยู่";
    } elseif (!str_len($Address, 50)) {
        $AddressErr = "ที่อยู่ต้องไม่เกิน 50 ตัวอักษร";
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

    // ถ้ามีข้อผิดพลาดใด ๆ ให้แสดงผลลัพธ์และหยุดการทำงาน
    if ($CompanyNameErr || $AddressErr || $CityErr || $CountryErr) {
        // เก็บข้อผิดพลาดลงใน session
        $_SESSION['errors'] = [
            'CompanyNameErr' => $CompanyNameErr,
            'AddressErr' => $AddressErr,
            'CityErr' => $CityErr,
            'CountryErr' => $CountryErr
        ];

        // เก็บค่าที่ผู้ใช้กรอกไว้ก่อนหน้า
        $_SESSION['old'] = [
            'ShippingCompanyID' => $ShippingCompanyID,
            'CompanyName' => $CompanyName,
            'Address' => $Address,
            'City' => $City,
            'Country' => $Country
        ];

        // ย้อนกลับไปยังฟอร์ม
        header("Location: shippingCompany_form.php" . ($ShippingCompanyID ? "?id=$ShippingCompanyID" : ""));
        exit();
    }

    // ถ้าไม่มีข้อผิดพลาด ให้บันทึกข้อมูลลงฐานข้อมูล
    if ($ShippingCompanyID != '') {
        // อัปเดตข้อมูลบริษัท
        $stmt = $conn->prepare("UPDATE ShippingCompanies SET CompanyName=?, Address=?, City=?, Country=? WHERE ShippingCompanyID=?");
        $stmt->bind_param("ssssi", $CompanyName, $Address, $City, $Country, $ShippingCompanyID);
    } else {
        // เพิ่มบริษัทใหม่
        $stmt = $conn->prepare("INSERT INTO ShippingCompanies (CompanyName, Address, City, Country) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $CompanyName, $Address, $City, $Country);
    }
    $stmt->execute();
    $stmt->close();
    $conn->close();

    // เปลี่ยนเส้นทางกลับไปยังหน้ารายการบริษัทขนส่ง
    header("Location: shippingCompanies.php");
?>