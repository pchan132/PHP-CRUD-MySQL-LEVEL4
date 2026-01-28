<?php
include 'db.php';
$kw = $_GET['kw'] ?? '';

if ($kw != '') {
    $stmt = $conn->prepare("SELECT * FROM ShippingCompany WHERE CompanyName LIKE ?");
    $search = "%$kw%";
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM ShippingCompany");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Shipping Companies</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <nav>
        <a href="products.php">Products</a>
        <a href="customers.php">Customers</a>
        <a href="shipping.php">Shipping Companies</a>
        <a href="orders.php">Orders</a>
    </nav>
    <h1>Shipping Companies</h1>
    
    <div class="search-box">
        <form method="GET">
            <input type="text" name="kw" value="<?= htmlspecialchars($kw) ?>" placeholder="Search by Company Name...">
            <button type="submit">Search</button>
            <a href="shipping.php" class="btn btn-reset">Reset</a>
            <a href="shipping_form.php" class="btn btn-add">Create a new Shipping Company</a>
        </form>
    </div>
    
    <table>
        <tr>
            <th>ID</th>
            <th>Company Name</th>
            <th>Address</th>
            <th>City</th>
            <th>Country</th>
        </tr>
        <?php while ($r = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $r['ShippingCompanyID'] ?></td>
            <td><a href="shipping_form.php?id=<?= $r['ShippingCompanyID'] ?>"><?= htmlspecialchars($r['CompanyName']) ?></a></td>
            <td><?= htmlspecialchars($r['Address']) ?></td>
            <td><?= htmlspecialchars($r['City']) ?></td>
            <td><?= htmlspecialchars($r['Country']) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
