<?php
// เชื่อมต่อฐานข้อมูล
$conn = new mysqli("localhost", "root", "", "shop");
if ($conn->connect_error) die("DB Error");
?>
