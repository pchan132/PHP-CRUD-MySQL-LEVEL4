<?php
    include 'db.php';

    $index = 1; // ตัวแปรนับลำดับ

    $keyword = $_GET['keyword'] ?? '';

    $sql = "SELECT * FROM products";

    if ($keyword != ''){
        $sql .= " WHERE ProductName LIKE ? or Category LIKE ?";

        $stmt = $conn->prepare($sql);
        $searchTerm = "%$keyword%";
        $stmt->bind_param("ss", $searchTerm, $searchTerm);
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
    <title>Products</title>

    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container grey-bg rounded">
        <div>
            <div>
                <h1>Product</h1>
            </div>
            <div>
                <a href="customer.php">Customer</a>
            </div>
        </div>
        
        <!-- ค้นหาสินค้า -->
        <div class="flex margin-top-20">
            <form method="GET" action="index.php">
                <input type="text" name="keyword" value="<?php echo htmlspecialchars($keyword); ?>" placeholder="Search products..." class="input-field">
                <button type="submit" class="button-primary">Search</button>
                <!-- ปุ่มรีเซ็ตเพื่อเคลียร์คำค้นหา -->
                <button type="button"  class="button-reset" onclick="window.location.href='index.php'">Reset</button>
            </form>

            <div class="margin-left-10">
                <a href="product_form.php">Create a new Product</a>
            </div>
        </div>

        <!-- แสดงข้อมูลสินค้า -->
        <table border="1" class="table white-bg rounded margin-top-20">
            <th>Id</th>
            <th>Product Name</th>
            <th>Picture</th>
            <th>Category</th>
            <th>Product Description</th>
            <th>Price</th>
            <th>Quantity Stock</th>

            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <!-- แสดงอันดับ -->
                    <td><?= htmlspecialchars($index) ?></td>
                    <td>
                        <a href="product_form.php?id=<?= htmlspecialchars($row['ProductID']) ?>">
                            <?= htmlspecialchars($row['ProductName']) ?>
                        </a>
                    </td>

                    <!-- แสดงรูปภาพ -->
                    <td>
                        <img src="<?= htmlspecialchars($row['Picture']) ?>" alt="<?= htmlspecialchars($row['ProductName']) ?>" width="100">
                    </td>

                    <!--  -->
                    <td><?= htmlspecialchars($row['Category']) ?></td>
                    <td><?= htmlspecialchars($row['ProductDescription']) ?></td>
                    <td><?= htmlspecialchars($row['Price']) ?> Baht</td>
                    <td><?= htmlspecialchars($row['QuantityStock']) ?></td>
                </tr>

                <!-- เพิ่มลำดับ -->
                <?php $index++; ?>
            <?php } ?>
        </table>
    </div>
</body>
</html>