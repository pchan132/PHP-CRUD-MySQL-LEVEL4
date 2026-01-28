<?php
session_start();
include 'db.php';

$err = $_SESSION['err'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['err'], $_SESSION['old']);

$id = $_GET['id'] ?? '';
$data = null;

if ($id != '') {
    $stmt = $conn->prepare("SELECT * FROM Customers WHERE CustomerID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();
}

function val($key, $old, $data) {
    return htmlspecialchars($old[$key] ?? $data[$key] ?? '');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $data ? 'Edit' : 'New' ?> Customer</title>
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
    <h1><?= $data ? 'Edit Customer' : 'New Customer' ?></h1>
    
    <form method="POST" action="customer_save.php">
        <input type="hidden" name="CustomerID" value="<?= val('CustomerID', $old, $data) ?>">
        
        <div class="form-group">
            <label>Customer Name: *</label>
            <input type="text" name="CustomerName" value="<?= val('CustomerName', $old, $data) ?>">
            <?php if (!empty($err['CustomerName'])): ?><div class="error"><?= $err['CustomerName'] ?></div><?php endif; ?>
        </div>
        
        <div class="form-group">
            <label>Address: *</label>
            <input type="text" name="AddressLine1" value="<?= val('AddressLine1', $old, $data) ?>">
            <?php if (!empty($err['AddressLine1'])): ?><div class="error"><?= $err['AddressLine1'] ?></div><?php endif; ?>
        </div>
        
        <div class="form-group">
            <label>City:</label>
            <input type="text" name="City" value="<?= val('City', $old, $data) ?>">
            <?php if (!empty($err['City'])): ?><div class="error"><?= $err['City'] ?></div><?php endif; ?>
        </div>
        
        <div class="form-group">
            <label>Country: *</label>
            <input type="text" name="Country" value="<?= val('Country', $old, $data) ?>">
            <?php if (!empty($err['Country'])): ?><div class="error"><?= $err['Country'] ?></div><?php endif; ?>
        </div>
        
        <div class="form-group">
            <label>Postal Code: * (5 digits)</label>
            <input type="text" name="PostalCode" value="<?= val('PostalCode', $old, $data) ?>">
            <?php if (!empty($err['PostalCode'])): ?><div class="error"><?= $err['PostalCode'] ?></div><?php endif; ?>
        </div>
        
        <div class="form-group">
            <label>Mobile Phone: * (format: 0XX-XXX-XXXX)</label>
            <input type="text" name="MobilePhone" value="<?= val('MobilePhone', $old, $data) ?>" placeholder="081-234-5678">
            <?php if (!empty($err['MobilePhone'])): ?><div class="error"><?= $err['MobilePhone'] ?></div><?php endif; ?>
        </div>
        
        <div>
            <button type="submit" class="btn btn-add">Save</button>
            <a href="customers.php" class="btn btn-cancel">Cancel</a>
            <?php if ($data): ?>
                <a href="customer_delete.php?id=<?= $data['CustomerID'] ?>" class="btn btn-delete" onclick="return confirm('Delete this customer?')">Delete</a>
            <?php endif; ?>
        </div>
    </form>
</div>
</body>
</html>
