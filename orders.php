<?php
include 'db.php';
$kw = $_GET['kw'] ?? '';

if ($kw != '') {
    $stmt = $conn->prepare("SELECT o.*, c.CustomerName, s.CompanyName 
        FROM Orders o 
        JOIN Customers c ON o.CustomerID = c.CustomerID 
        JOIN ShippingCompany s ON o.ShippingCompanyID = s.ShippingCompanyID 
        WHERE s.CompanyName LIKE ?");
    $search = "%$kw%";
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT o.*, c.CustomerName, s.CompanyName 
        FROM Orders o 
        JOIN Customers c ON o.CustomerID = c.CustomerID 
        JOIN ShippingCompany s ON o.ShippingCompanyID = s.ShippingCompanyID");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Orders</title>
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
    <h1>Orders</h1>
    
    <div class="search-box">
        <form method="GET">
            <input type="text" name="kw" value="<?= htmlspecialchars($kw) ?>" placeholder="Search by Company Name...">
            <button type="submit">Search</button>
            <a href="orders.php" class="btn btn-reset">Reset</a>
            <a href="order_form.php" class="btn btn-add">Create a new Order</a>
        </form>
    </div>
    
    <table>
        <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Shipping Company</th>
            <th>Order DateTime</th>
            <th>Action</th>
        </tr>
        <?php while ($r = $result->fetch_assoc()): ?>
        <tr>
            <td><a href="order_form.php?id=<?= $r['OrderID'] ?>"><?= $r['OrderID'] ?></a></td>
            <td><?= htmlspecialchars($r['CustomerName']) ?></td>
            <td><?= htmlspecialchars($r['CompanyName']) ?></td>
            <td><?= $r['OrderDateTime'] ?></td>
            <td>
                <a href="orderlines.php?order_id=<?= $r['OrderID'] ?>" class="btn btn-edit">Order Lines</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
