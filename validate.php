<?php 
    // $v (value) : ค่าที่ต้องการตรวจสอบ
    // len (length) : ความยาวสูงสุดที่อนุญาต

    // ตรวจสอบค่าว่าง
    function req($v){
        return isset($v) && trim($v) !== '';
    }

    // ตรวจสอบตัวเลขและความยาวสูงสุด
    // is_numeric(): ฟังก์ชันที่ใช้ตรวจสอบว่าค่าที่ส่งเข้ามาเป็นตัวเลขหรือไม่
    // strlen(): ฟังก์ชันที่ใช้ในการนับจำนวนตัวอักษรในสตริง
    function num($v, $len) {
        return is_numeric($v) && strlen((string)$v) <= $len;
    }

    // ตรวจสอบความยาวสูงสุดของสตริง
    function str_len($v, $len) {
        return strlen(trim($v)) <= $len;
    }

    // ตรวจสอบรูปแบบ URL และความยาวสูงสุด
    function url($v) {
        return filter_var($v, FILTER_VALIDATE_URL);
    }
?>