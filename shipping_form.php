<?php
session_start();
include 'db.php';

$err = $_SESSION['err'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['err'], $_SESSION['old']);

$id = $_GET['id'] ?? '';
$data = null;

if ($id != '') {
    $stmt = $conn->prepare("SELECT * FROM ShippingCompany WHERE ShippingCompanyID = ?");
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
    <title><?= $data ? 'Edit' : 'New' ?> Shipping Company</title>
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
    <h1><?= $data ? 'Edit Shipping Company' : 'New Shipping Company' ?></h1>
    
    <form method="POST" action="shipping_save.php">
        <input type="hidden" name="ShippingCompanyID" value="<?= val('ShippingCompanyID', $old, $data) ?>">
        
        <div class="form-group">
            <label>Company Name: *</label>
            <input type="text" name="CompanyName" value="<?= val('CompanyName', $old, $data) ?>" maxlength="50">
            <?php if (!empty($err['CompanyName'])): ?><div class="error"><?= $err['CompanyName'] ?></div><?php endif; ?>
        </div>
        
        <div class="form-group">
            <label>Address: *</label>
            <input type="text" name="Address" value="<?= val('Address', $old, $data) ?>" maxlength="50">
            <?php if (!empty($err['Address'])): ?><div class="error"><?= $err['Address'] ?></div><?php endif; ?>
        </div>
        
        <div class="form-group">
            <label>City:</label>
            <input type="text" name="City" value="<?= val('City', $old, $data) ?>" maxlength="50">
        </div>
        
        <div class="form-group">
            <label>Country: *</label>
            <input type="text" name="Country" value="<?= val('Country', $old, $data) ?>" maxlength="50">
            <?php if (!empty($err['Country'])): ?><div class="error"><?= $err['Country'] ?></div><?php endif; ?>
        </div>
        
        <div>
            <button type="submit" class="btn btn-add">Save</button>
            <a href="shipping.php" class="btn btn-cancel">Cancel</a>
            <?php if ($data): ?>
                <a href="shipping_delete.php?id=<?= $data['ShippingCompanyID'] ?>" class="btn btn-delete" onclick="return confirm('Delete this company?')">Delete</a>
            <?php endif; ?>
        </div>
    </form>
</div>
</body>
</html>
