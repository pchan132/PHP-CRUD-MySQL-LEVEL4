<?php
    include 'db.php';

    $index = 1;

    $keyword = $_GET['keyword'] ?? '';

    $sql = "SELECT * FROM customers";

    if ($keyword != ''){
        $sql .= " WHERE CustomerName LIKE ?";

        $stmt = $conn->prepare($sql);
        $searchTerm = "%$keyword%";
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $result = $conn->query($sql);
    }

    // แสดงข้อมูลทั้งหมด
    // echo '<pre>';
    // print_r($result->fetch_all(MYSQLI_ASSOC));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container grey-bg rounded">
        <div>
            <div>
                <h1>Customer</h1>
            </div>
            <div>
                <a href="index.php">Product</a>
            </div>
        </div>

        <!-- ช่องค้นหาชื่อ customer -->
        <div class="flex margin-top-20">
            <form action="customer.php" method="GET">
                <input type="text" name="keyword" value="<?php echo htmlspecialchars($keyword); ?>"
                    placeholder="Search customers..." class="input-field">
                    <button type="submit" class="button-primary">Search</button>
                <!-- ปุ่มรีเซ็ตเพื่อเคลียร์คำค้นหา -->
                <button type="button"  class="button-reset" onclick="window.location.href='customer.php'">Reset</button>
            </form>
        </div>
    </div>

</body>
</html>