<?php
session_start();
include 'db.php';

$err = $_SESSION['err'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['err'], $_SESSION['old']);

$id = $_GET['id'] ?? '';
$data = null;

if ($id != '') {
    $stmt = $conn->prepare("SELECT * FROM Orders WHERE OrderID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();
}

// Get customers & shipping companies for dropdown
$customers = $conn->query("SELECT * FROM Customers");
$shippings = $conn->query("SELECT * FROM ShippingCompany");

function val($key, $old, $data) {
    return $old[$key] ?? $data[$key] ?? '';
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $data ? 'Edit' : 'New' ?> Order</title>
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
    <h1><?= $data ? 'Edit Order' : 'New Order' ?></h1>
    
    <form method="POST" action="order_save.php">
        <input type="hidden" name="OrderID" value="<?= val('OrderID', $old, $data) ?>">
        
        <div class="form-group">
            <label>Customer: *</label>
            <select name="CustomerID">
                <option value="">-- Select Customer --</option>
                <?php while ($c = $customers->fetch_assoc()): ?>
                    <option value="<?= $c['CustomerID'] ?>" <?= val('CustomerID', $old, $data) == $c['CustomerID'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['CustomerName']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <?php if (!empty($err['CustomerID'])): ?><div class="error"><?= $err['CustomerID'] ?></div><?php endif; ?>
        </div>
        
        <div class="form-group">
            <label>Shipping Company: *</label>
            <select name="ShippingCompanyID">
                <option value="">-- Select Shipping Company --</option>
                <?php while ($s = $shippings->fetch_assoc()): ?>
                    <option value="<?= $s['ShippingCompanyID'] ?>" <?= val('ShippingCompanyID', $old, $data) == $s['ShippingCompanyID'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($s['CompanyName']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <?php if (!empty($err['ShippingCompanyID'])): ?><div class="error"><?= $err['ShippingCompanyID'] ?></div><?php endif; ?>
        </div>
        
        <div class="form-group">
            <label>Order DateTime: *</label>
            <input type="datetime-local" name="OrderDateTime" value="<?= date('Y-m-d\TH:i', strtotime(val('OrderDateTime', $old, $data) ?: 'now')) ?>">
            <?php if (!empty($err['OrderDateTime'])): ?><div class="error"><?= $err['OrderDateTime'] ?></div><?php endif; ?>
        </div>
        
        <div>
            <button type="submit" class="btn btn-add">Save</button>
            <a href="orders.php" class="btn btn-cancel">Cancel</a>
            <?php if ($data): ?>
                <a href="order_delete.php?id=<?= $data['OrderID'] ?>" class="btn btn-delete" onclick="return confirm('Delete this order?')">Delete</a>
            <?php endif; ?>
        </div>
    </form>
</div>
</body>
</html>
