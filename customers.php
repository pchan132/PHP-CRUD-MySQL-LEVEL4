<?php
include 'db.php';
$kw = $_GET['kw'] ?? '';

if ($kw != '') {
    $stmt = $conn->prepare("SELECT * FROM Customers WHERE CustomerName LIKE ?");
    $search = "%$kw%";
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM Customers");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Customers</title>
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
    <h1>Customers</h1>
    
    <div class="search-box">
        <form method="GET">
            <input type="text" name="kw" value="<?= htmlspecialchars($kw) ?>" placeholder="Search by Customer Name...">
            <button type="submit">Search</button>
            <a href="customers.php" class="btn btn-reset">Reset</a>
            <a href="customer_form.php" class="btn btn-add">Create a new Customer</a>
        </form>
    </div>
    
    <table>
        <tr>
            <th>ID</th>
            <th>Customer Name</th>
            <th>Address</th>
            <th>City</th>
            <th>Country</th>
            <th>Postal Code</th>
            <th>Mobile Phone</th>
        </tr>
        <?php while ($r = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $r['CustomerID'] ?></td>
            <td><a href="customer_form.php?id=<?= $r['CustomerID'] ?>"><?= htmlspecialchars($r['CustomerName']) ?></a></td>
            <td><?= htmlspecialchars($r['AddressLine1']) ?></td>
            <td><?= htmlspecialchars($r['City']) ?></td>
            <td><?= htmlspecialchars($r['Country']) ?></td>
            <td><?= htmlspecialchars($r['PostalCode']) ?></td>
            <td><?= htmlspecialchars($r['MobilePhone']) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
