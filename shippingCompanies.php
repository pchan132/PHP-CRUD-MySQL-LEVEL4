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
    // echo '<pre>';
    // print_r($result->fetch_all(MYSQLI_ASSOC));

    // ShippingCompanyID
    // CompanyName
    // Address
    // City
    // Country
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping Companies</title>

    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container grey-bg rounded">
        <div>
            <div>
                <h1>Shipping Companies</h1>
            </div>
            <div>
                <a href="customer.php">Customer</a>
            </div>
            <div>
                <a href="index.php">Products</a>
            </div>
        </div>

        <!-- ช่องค้นหาสินค้า -->
        <div class="flex margin-top-20">
            <form method="GET" action="shippingCompanies.php">
                <input type="text" name="keyword" value="<?php echo htmlspecialchars($keyword); ?>" placeholder="Search shipping Companies..." class="input-field">

                <!-- ปุ่มค้นหา -->
                 <button type="submit" class="button-primary">
                    Search
                 </button>
                 <!-- ปุ่มรีเซ็ตเพื่อเคลียร์คำค้นหา -->
                 <button type="button"  class="button-reset" onclick="window.location.href='shippingCompanies.php'">Reset</button>
            </form>

            <div class="margin-left-10">
                 <a href="shippingCompany_form.php">Create a new Shipping Company</a>
            </div>
        </div>  
            <!-- แสดงข้อมูล บริษัท -->
            <table border="1" class="table white-bg rounded margin-top-20">
                <th>Company Id</th>
                <th>Company Name</th>
                <th>Address</th>
                <th>City</th>
                <th>Country</th>

                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <!-- แสดงอันดับ -->
                         <td><?= htmlspecialchars($index++) ?></td> 

                         <!-- แสดงชื่อ Company -->
                         <td>
                            <a href="shippingCompany_form.php?id=<?= htmlspecialchars($row['ShippingCompanyID']) ?>"><?= htmlspecialchars($row['CompanyName']) ?></a>
                         </td>

                         <!-- แสดงที่อยู่ -->
                         <td><?= htmlspecialchars($row['Address']) ?></td>

                         <!-- แสดงเมือง -->
                         <td><?= htmlspecialchars($row['City']) ?></td>

                         <!-- แสดงประเทศ -->
                         <td><?= htmlspecialchars($row['Country']) ?></td>
                    </tr>
                <?php } ?>
            </table>   
    </div>
</body>
</html>



