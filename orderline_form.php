<?php
session_start();
include 'db.php';

$orderId = $_GET['order_id'] ?? 0;

$err = $_SESSION['err'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['err'], $_SESSION['old']);

// Get products for dropdown
$products = $conn->query("SELECT * FROM Products WHERE QuantityStock > 0");
?>
<!DOCTYPE html>
<html>
<head>
    <title>New Order Line</title>
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
    <h1>New Order Line - Order #<?= $orderId ?></h1>
    
    <form method="POST" action="orderline_save.php">
        <input type="hidden" name="OrderID" value="<?= $orderId ?>">
        
        <div class="form-group">
            <label>Product: *</label>
            <select name="ProductID">
                <option value="">-- Select Product --</option>
                <?php while ($p = $products->fetch_assoc()): ?>
                    <option value="<?= $p['ProductID'] ?>" <?= ($old['ProductID'] ?? '') == $p['ProductID'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($p['ProductName']) ?> (Stock: <?= $p['QuantityStock'] ?>, Price: <?= $p['Price'] ?>)
                    </option>
                <?php endwhile; ?>
            </select>
            <?php if (!empty($err['ProductID'])): ?><div class="error"><?= $err['ProductID'] ?></div><?php endif; ?>
        </div>
        
        <div class="form-group">
            <label>Quantity: * (max 999)</label>
            <input type="number" name="Quantity" value="<?= $old['Quantity'] ?? 1 ?>" min="1" max="999">
            <?php if (!empty($err['Quantity'])): ?><div class="error"><?= $err['Quantity'] ?></div><?php endif; ?>
        </div>
        
        <div>
            <button type="submit" class="btn btn-add">Save</button>
            <a href="orderlines.php?order_id=<?= $orderId ?>" class="btn btn-cancel">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>
