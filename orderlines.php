<?php
include 'db.php';
$orderId = $_GET['order_id'] ?? 0;

// Get order info
$stmt = $conn->prepare("SELECT o.*, c.CustomerName, s.CompanyName 
    FROM Orders o 
    JOIN Customers c ON o.CustomerID = c.CustomerID 
    JOIN ShippingCompany s ON o.ShippingCompanyID = s.ShippingCompanyID 
    WHERE o.OrderID = ?");
$stmt->bind_param("i", $orderId);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

// Get order lines
$stmt = $conn->prepare("SELECT ol.*, p.ProductName, p.Price 
    FROM OrderLine ol 
    JOIN Products p ON ol.ProductID = p.ProductID 
    WHERE ol.OrderID = ?");
$stmt->bind_param("i", $orderId);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order Lines</title>
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
    <h1>Order Lines - Order #<?= $orderId ?></h1>
    
    <?php if ($order): ?>
    <div style="background:#f9f9f9; padding:15px; margin-bottom:15px; border-radius:5px;">
        <strong>Customer:</strong> <?= htmlspecialchars($order['CustomerName']) ?><br>
        <strong>Shipping Company:</strong> <?= htmlspecialchars($order['CompanyName']) ?><br>
        <strong>Order Date:</strong> <?= $order['OrderDateTime'] ?>
    </div>
    <?php endif; ?>
    
    <div class="search-box">
        <a href="orderline_form.php?order_id=<?= $orderId ?>" class="btn btn-add">New Order Line</a>
        <a href="orders.php" class="btn btn-cancel">Back to Orders</a>
    </div>
    
    <table>
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
            <th>Action</th>
        </tr>
        <?php $grandTotal = 0; while ($r = $result->fetch_assoc()): 
            $total = $r['Price'] * $r['Quantity'];
            $grandTotal += $total;
        ?>
        <tr>
            <td><?= htmlspecialchars($r['ProductName']) ?></td>
            <td><?= $r['Price'] ?></td>
            <td><?= $r['Quantity'] ?></td>
            <td><?= $total ?></td>
            <td>
                <a href="orderline_delete.php?order_id=<?= $orderId ?>&product_id=<?= $r['ProductID'] ?>" class="btn btn-delete" onclick="return confirm('Delete this item?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
        <tr style="background:#4CAF50; color:white; font-weight:bold;">
            <td colspan="3">Grand Total</td>
            <td><?= $grandTotal ?></td>
            <td></td>
        </tr>
    </table>
</div>
</body>
</html>
