<?php 
    include 'db.php';

    $index = 1;

    $keyword = $_GET['keyword'] ?? '';

    $sql = "SELECT * FROM shippingcompany";

    if($keyword != ''){
        $sql .= "WHERE CompanyName LIKE ?";

        $stmt = $conn->prepare($sql);
        $searchTerm = "%$keyword%";
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $result = $conn->query($sql);
    }

    // แสดงข้อมูลทั้ง
    echo '<pre>';
    print_r($result->fetch_all(MYSQLI_ASSOC));

    // ShippingCompanyID
    // CompanyName
    // Address
    // City
    // Country
?>



