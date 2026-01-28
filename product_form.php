<?php
session_start();
include 'db.php';

$err = $_SESSION['err'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['err'], $_SESSION['old']);

$id = $_GET['id'] ?? '';
$data = null;

if ($id != '') {
    $stmt = $conn->prepare("SELECT * FROM Products WHERE ProductID = ?");
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
    <title><?= $data ? 'Edit' : 'New' ?> Product</title>
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
    <h1><?= $data ? 'Edit Product' : 'New Product' ?></h1>
    
    <form method="POST" action="product_save.php">
        <input type="hidden" name="ProductID" value="<?= val('ProductID', $old, $data) ?>">
        
        <div class="form-group">
            <label>Product Name: *</label>
            <input type="text" name="ProductName" value="<?= val('ProductName', $old, $data) ?>">
            <?php if (!empty($err['ProductName'])): ?><div class="error"><?= $err['ProductName'] ?></div><?php endif; ?>
        </div>
        
        <div class="form-group">
            <label>Picture URL: *</label>
            <input type="text" name="Picture" value="<?= val('Picture', $old, $data) ?>">
            <?php if (!empty($err['Picture'])): ?><div class="error"><?= $err['Picture'] ?></div><?php endif; ?>
        </div>
        
        <div class="form-group">
            <label>Category: *</label>
            <input type="text" name="Category" value="<?= val('Category', $old, $data) ?>">
            <?php if (!empty($err['Category'])): ?><div class="error"><?= $err['Category'] ?></div><?php endif; ?>
        </div>
        
        <div class="form-group">
            <label>Product Description:</label>
            <textarea name="ProductDescription"><?= val('ProductDescription', $old, $data) ?></textarea>
            <?php if (!empty($err['ProductDescription'])): ?><div class="error"><?= $err['ProductDescription'] ?></div><?php endif; ?>
        </div>
        
        <div class="form-group">
            <label>Price: * (max 4 digits)</label>
            <input type="number" name="Price" value="<?= val('Price', $old, $data) ?>" min="0" max="9999">
            <?php if (!empty($err['Price'])): ?><div class="error"><?= $err['Price'] ?></div><?php endif; ?>
        </div>
        
        <div class="form-group">
            <label>Quantity Stock: * (max 3 digits)</label>
            <input type="number" name="QuantityStock" value="<?= val('QuantityStock', $old, $data) ?>" min="0" max="999">
            <?php if (!empty($err['QuantityStock'])): ?><div class="error"><?= $err['QuantityStock'] ?></div><?php endif; ?>
        </div>
        
        <div>
            <button type="submit" class="btn btn-add">Save</button>
            <a href="products.php" class="btn btn-cancel">Cancel</a>
            <?php if ($data): ?>
                <a href="product_delete.php?id=<?= $data['ProductID'] ?>" class="btn btn-delete" onclick="return confirm('Delete this product?')">Delete</a>
            <?php endif; ?>
        </div>
    </form>
</div>
</body>
</html>
