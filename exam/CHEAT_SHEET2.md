# 📚 PHP CRUD สรุปสั้นสำหรับสอบ

## 📁 โครงสร้างไฟล์ (5 ไฟล์)
```
exam/
├── db.php      # เชื่อมต่อ DB
├── list.php    # แสดงรายการ + ค้นหา (READ)
├── form.php    # ฟอร์ม เพิ่ม/แก้ไข
├── save.php    # บันทึก (CREATE/UPDATE)
└── delete.php  # ลบ (DELETE)
```

---

## 1️⃣ db.php - เชื่อมต่อฐานข้อมูล
```php
<?php
$conn = new mysqli("localhost", "root", "", "shop");
if ($conn->connect_error) die("DB Error");
?>
```

---

## 2️⃣ list.php - แสดงรายการ + ค้นหา (READ + SEARCH)
```php
<?php
include 'db.php';
$kw = $_GET['kw'] ?? '';

// ค้นหา หรือ ดึงทั้งหมด
if ($kw != '') {
    $stmt = $conn->prepare("SELECT * FROM table WHERE name LIKE ?");
    $search = "%$kw%";
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM table");
}
?>

<!-- ฟอร์มค้นหา -->
<form method="GET">
    <input name="kw" value="<?= htmlspecialchars($kw) ?>">
    <button>ค้นหา</button>
</form>

<!-- วนลูปแสดงข้อมูล -->
<?php while ($r = $result->fetch_assoc()): ?>
    <?= htmlspecialchars($r['name']) ?>
    <a href="form.php?id=<?= $r['id'] ?>">แก้ไข</a>
    <a href="delete.php?id=<?= $r['id'] ?>">ลบ</a>
<?php endwhile; ?>
```

---

## 3️⃣ form.php - ฟอร์มเพิ่ม/แก้ไข
```php
<?php
session_start();
include 'db.php';

$err = $_SESSION['err'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['err'], $_SESSION['old']);

$id = $_GET['id'] ?? '';
$data = null;

// ถ้ามี id = โหมดแก้ไข
if ($id != '') {
    $stmt = $conn->prepare("SELECT * FROM table WHERE id = ?");
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
    <input type="hidden" name="id" value="<?= val('id', $old, $data) ?>">
    
    <input name="name" value="<?= val('name', $old, $data) ?>">
    <?php if (!empty($err['name'])): ?>
        <div class="error"><?= $err['name'] ?></div>
    <?php endif; ?>
    
    <button>บันทึก</button>
</form>
```

---

## 4️⃣ save.php - บันทึก (CREATE/UPDATE)
```php
<?php
session_start();
include 'db.php';

$id = $_POST['id'] ?? '';
$name = $_POST['name'] ?? '';

// Validate
$err = [];
if (trim($name) == '') $err['name'] = "กรุณากรอก";

// ถ้ามี error -> กลับฟอร์ม
if (!empty($err)) {
    $_SESSION['err'] = $err;
    $_SESSION['old'] = $_POST;
    header("Location: form.php" . ($id ? "?id=$id" : ""));
    exit;
}

// บันทึก
if ($id != '') {
    // UPDATE
    $stmt = $conn->prepare("UPDATE table SET name=? WHERE id=?");
    $stmt->bind_param("si", $name, $id);
} else {
    // INSERT
    $stmt = $conn->prepare("INSERT INTO table (name) VALUES (?)");
    $stmt->bind_param("s", $name);
}
$stmt->execute();

header("Location: list.php");
?>
```

---

## 5️⃣ delete.php - ลบ (DELETE)
```php
<?php
include 'db.php';
$id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("DELETE FROM table WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: list.php");
?>
```

---

## 🔑 สูตรจำง่าย

### Prepared Statement
```php
$stmt = $conn->prepare("SQL ?");  // เตรียม
$stmt->bind_param("s", $var);     // ผูก (s=string, i=int)
$stmt->execute();                  // รัน
$result = $stmt->get_result();    // ผลลัพธ์ (SELECT)
```

### bind_param Types
| Type | ความหมาย |
|------|----------|
| `s`  | string   |
| `i`  | integer  |
| `d`  | double   |

### Session สำหรับ Error
```php
session_start();
$_SESSION['err'] = $err;     // เก็บ
$err = $_SESSION['err'];     // ดึง
unset($_SESSION['err']);     // ลบ
```

### Redirect
```php
header("Location: page.php");
exit;
```

### Null Coalescing (??)
```php
$kw = $_GET['kw'] ?? '';     // ถ้าไม่มีค่า = ''
```

### XSS Protection
```php
<?= htmlspecialchars($value) ?>
```

---

## 🔄 Flow สรุป

```
list.php ──[เพิ่ม]──> form.php ──[บันทึก]──> save.php ──> list.php
    │                    ↑                       │
    │                    └───[error]─────────────┘
    │
    ├──[แก้ไข]──> form.php?id=X ──> save.php ──> list.php
    │
    └──[ลบ]────> delete.php?id=X ──> list.php
```
