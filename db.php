<?php
    $conn = new mysqli("localhost","root","","shop");

    // เมื่อการเชื่อมต่อล้มเหลว
    if ($conn->connect_error) die("DB Error") ;
    // else die("Connected successfully");
?>