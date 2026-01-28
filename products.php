<?php
include 'db.php';
$kw = $_GET['kw'] ?? '';

if ($kw != '') {
    $stmt = $conn->prepare("SELECT * FROM Products WHERE ProductName LIKE ? OR Category LIKE ? OR ProductDescription LIKE ?");
    $search = "%$kw%";
    $stmt->bind_param("sss", $search, $search, $search);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM Products");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
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
    <h1>Products</h1>
    
    <div class="search-box">
        <form method="GET">
            <input type="text" name="kw" value="<?= htmlspecialchars($kw) ?>" placeholder="Search by Name, Category, Description...">
            <button type="submit">Search</button>
            <a href="products.php" class="btn btn-reset">Reset</a>
            <a href="product_form.php" class="btn btn-add">Create a new Product</a>
        </form>
    </div>
    
    <table>
        <tr>
            <th>ID</th>
            <th>Picture</th>
            <th>Product Name</th>
            <th>Category</th>
            <th>Description</th>
            <th>Price</th>
            <th>Stock</th>
        </tr>
        <?php while ($r = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $r['ProductID'] ?></td>
            <td><img src="<?= htmlspecialchars($r['Picture']) ?>" class="img-thumb" width="100" height="100 "></td>
            <td><a href="product_form.php?id=<?= $r['ProductID'] ?>"><?= htmlspecialchars($r['ProductName']) ?></a></td>
            <td><?= htmlspecialchars($r['Category']) ?></td>
            <td><?= htmlspecialchars($r['ProductDescription']) ?></td>
            <td><?= $r['Price'] ?></td>
            <td><?= $r['QuantityStock'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
