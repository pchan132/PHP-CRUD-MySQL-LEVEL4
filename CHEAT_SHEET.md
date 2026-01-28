# 📚 สรุปสูตร PHP CRUD สำหรับสอบ (ไม่ใช้เน็ต)

## 📁 โครงสร้างไฟล์ต่อ 1 ตาราง
```
[table].php       - แสดงรายการ + ค้นหา (READ)
[table]_form.php  - ฟอร์ม เพิ่ม/แก้ไข
[table]_save.php  - บันทึก (CREATE/UPDATE)
[table]_delete.php - ลบ (DELETE)
```

---

## 1️⃣ db.php - เชื่อมต่อฐานข้อมูล
```php
<?php
$conn = new mysqli("localhost", "root", "", "shop");
if ($conn->connect_error) die("DB Error");
$conn->set_charset("utf8");
?>
```

---

## 2️⃣ list.php - แสดงรายการ + ค้นหา
```php
<?php
include 'db.php';
$kw = $_GET['kw'] ?? '';

if ($kw != '') {
    $stmt = $conn->prepare("SELECT * FROM Products WHERE ProductName LIKE ?");
    $search = "%$kw%";
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM Products");
}
?>
<!-- HTML -->
<form method="GET">
    <input name="kw" value="<?= htmlspecialchars($kw) ?>">
    <button>Search</button>
</form>

<table>
    <?php while ($r = $result->fetch_assoc()): ?>
    <tr>
        <td><a href="form.php?id=<?= $r['ProductID'] ?>"><?= htmlspecialchars($r['ProductName']) ?></a></td>
    </tr>
    <?php endwhile; ?>
</table>
```

### ค้นหาหลายฟิลด์
```php
$stmt = $conn->prepare("SELECT * FROM Products WHERE ProductName LIKE ? OR Category LIKE ?");
$search = "%$kw%";
$stmt->bind_param("ss", $search, $search);
```

---

## 3️⃣ form.php - ฟอร์มเพิ่ม/แก้ไข
```php
<?php
session_start();
include 'db.php';

// ดึง error และ old data
$err = $_SESSION['err'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['err'], $_SESSION['old']);

// ถ้ามี id = แก้ไข
$id = $_GET['id'] ?? '';
$data = null;
if ($id != '') {
    $stmt = $conn->prepare("SELECT * FROM Products WHERE ProductID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();
}

// ฟังก์ชันดึงค่า (old > data > ว่าง)
function val($key, $old, $data) {
    return htmlspecialchars($old[$key] ?? $data[$key] ?? '');
}
?>

<form method="POST" action="save.php">
    <input type="hidden" name="id" value="<?= val('ProductID', $old, $data) ?>">
    
    <input name="ProductName" value="<?= val('ProductName', $old, $data) ?>">
    <?php if (!empty($err['ProductName'])): ?>
        <div class="error"><?= $err['ProductName'] ?></div>
    <?php endif; ?>
    
    <button>Save</button>
</form>
```

### Dropdown (ComboBox) จากฐานข้อมูล
```php
<?php $customers = $conn->query("SELECT * FROM Customers"); ?>
<select name="CustomerID">
    <option value="">-- เลือก --</option>
    <?php while ($c = $customers->fetch_assoc()): ?>
        <option value="<?= $c['CustomerID'] ?>" <?= val('CustomerID', $old, $data) == $c['CustomerID'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($c['CustomerName']) ?>
        </option>
    <?php endwhile; ?>
</select>
```

---

## 4️⃣ save.php - บันทึก (Validate + INSERT/UPDATE)
```php
<?php
session_start();
include 'db.php';

$id = $_POST['id'] ?? '';
$name = trim($_POST['ProductName'] ?? '');
$price = $_POST['Price'] ?? '';

// ===== VALIDATE =====
$err = [];

// Required + Max Length
if ($name == '') $err['ProductName'] = "กรุณากรอกชื่อ";
elseif (strlen($name) > 50) $err['ProductName'] = "ไม่เกิน 50 ตัว";

// Number + Range
if ($price === '' || !is_numeric($price)) $err['Price'] = "กรุณากรอกราคา";
elseif ($price < 0 || $price > 9999) $err['Price'] = "ราคา 0-9999";

// Phone Pattern: 0XX-XXX-XXXX
if (!preg_match('/^0[0-9]{2}-[0-9]{3}-[0-9]{4}$/', $phone)) 
    $err['Phone'] = "รูปแบบ: 081-234-5678";

// ===== ERROR -> กลับฟอร์ม =====
if (!empty($err)) {
    $_SESSION['err'] = $err;
    $_SESSION['old'] = $_POST;
    header("Location: form.php" . ($id ? "?id=$id" : ""));
    exit;
}

// ===== SAVE =====
if ($id != '') {
    // UPDATE
    $stmt = $conn->prepare("UPDATE Products SET ProductName=?, Price=? WHERE ProductID=?");
    $stmt->bind_param("sii", $name, $price, $id);
} else {
    // INSERT
    $stmt = $conn->prepare("INSERT INTO Products (ProductName, Price) VALUES (?, ?)");
    $stmt->bind_param("si", $name, $price);
}
$stmt->execute();

header("Location: list.php");
?>
```

---

## 5️⃣ delete.php - ลบ
```php
<?php
include 'db.php';
$id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("DELETE FROM Products WHERE ProductID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: list.php");
?>
```

### ลบแบบเช็ค FK ก่อน
```php
<?php
include 'db.php';
$id = $_GET['id'] ?? 0;

// เช็คว่ามีข้อมูลลูกหรือไม่
$stmt = $conn->prepare("SELECT COUNT(*) as cnt FROM OrderLine WHERE OrderID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if ($result['cnt'] > 0) {
    echo "<script>alert('ไม่สามารถลบได้'); window.location='list.php';</script>";
    exit;
}

$stmt = $conn->prepare("DELETE FROM Orders WHERE OrderID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
header("Location: list.php");
?>
```

---

## 🔑 สูตรจำสำคัญ

### Prepared Statement
```php
$stmt = $conn->prepare("SQL ?");  // เตรียม
$stmt->bind_param("s", $var);     // ผูก
$stmt->execute();                  // รัน
$result = $stmt->get_result();    // ผลลัพธ์
$data = $result->fetch_assoc();   // แถวเดียว
```

### bind_param Types
| Type | ความหมาย | ตัวอย่าง |
|------|----------|---------|
| `s`  | string   | ชื่อ, ที่อยู่ |
| `i`  | integer  | ID, จำนวน |
| `d`  | double   | ราคาทศนิยม |

### JOIN ตาราง
```php
$sql = "SELECT o.*, c.CustomerName, s.CompanyName 
    FROM Orders o 
    JOIN Customers c ON o.CustomerID = c.CustomerID 
    JOIN ShippingCompany s ON o.ShippingCompanyID = s.ShippingCompanyID";
```

### Session
```php
session_start();                  // เรียกก่อนใช้
$_SESSION['err'] = $err;          // เก็บ
$err = $_SESSION['err'] ?? [];    // ดึง (ถ้าไม่มีให้ = [])
unset($_SESSION['err']);          // ลบ
```

### Validation Patterns
```php
// Required
if (trim($value) == '') $err['field'] = "Required";

// Max Length
if (strlen($value) > 50) $err['field'] = "Max 50";

// Number
if (!is_numeric($value)) $err['field'] = "Must be number";

// Number Range
if ($value < 0 || $value > 9999) $err['field'] = "0-9999";

// Phone: 0XX-XXX-XXXX
if (!preg_match('/^0[0-9]{2}-[0-9]{3}-[0-9]{4}$/', $value))
```

### Redirect
```php
header("Location: page.php");
exit;
```

### XSS Protection
```php
<?= htmlspecialchars($value) ?>
```

### Null Coalescing
```php
$kw = $_GET['kw'] ?? '';          // ถ้าไม่มีค่า = ''
$data = $old[$key] ?? $data[$key] ?? '';  // ลำดับความสำคัญ
```

---

## 🔄 Flow Chart

```
┌─────────────┐     ┌───────────────┐     ┌───────────┐
│  list.php   │────>│   form.php    │────>│  save.php │
│ (แสดง+ค้นหา) │     │ (เพิ่ม/แก้ไข)   │     │ (บันทึก)   │
└─────────────┘     └───────────────┘     └───────────┘
      │                    ↑                    │
      │                    │ error              │ success
      │                    └────────────────────┤
      │                                         │
      │              ┌─────────────┐            │
      └─────────────>│ delete.php  │<───────────┘
                     │   (ลบ)       │
                     └─────────────┘
```

---

## 📋 Data Dictionary -> Code

| Data Type | MySQL | PHP Validate |
|-----------|-------|--------------|
| Text(50) | VARCHAR(50) | `strlen() > 50` |
| Number(4) | INT | `!is_numeric() \|\| > 9999` |
| Auto Number PK | INT AUTO_INCREMENT PRIMARY KEY | ไม่ต้อง validate |
| Not Null | NOT NULL | `trim() == ''` |
| Pattern เบอร์โทร | VARCHAR(12) | `preg_match('/^0[0-9]{2}-[0-9]{3}-[0-9]{4}$/')` |

---

## 📂 โครงสร้างระบบสอบ

```
exam_full/
├── db.php              # เชื่อมต่อ DB
├── style.css           # CSS
├── shop.sql            # SQL สร้างฐานข้อมูล
│
├── products.php        # รายการสินค้า
├── product_form.php    # ฟอร์มสินค้า
├── product_save.php    # บันทึกสินค้า
├── product_delete.php  # ลบสินค้า
│
├── customers.php       # รายการลูกค้า
├── customer_form.php   # ฟอร์มลูกค้า
├── customer_save.php   # บันทึกลูกค้า
├── customer_delete.php # ลบลูกค้า
│
├── shipping.php        # รายการบริษัทขนส่ง
├── shipping_form.php   # ฟอร์มบริษัทขนส่ง
├── shipping_save.php   # บันทึกบริษัทขนส่ง
├── shipping_delete.php # ลบบริษัทขนส่ง
│
├── orders.php          # รายการ Order
├── order_form.php      # ฟอร์ม Order (มี Dropdown)
├── order_save.php      # บันทึก Order
├── order_delete.php    # ลบ Order (เช็ค FK)
│
├── orderlines.php      # รายการ Order Line
├── orderline_form.php  # ฟอร์ม Order Line
├── orderline_save.php  # บันทึก Order Line
└── orderline_delete.php # ลบ Order Line
```
