<?php 
include 'db.php';
$id = $_GET['id'];
$conn->query("DELETE FROM ShippingCompany WHERE ShippingCompanyID=$id");
header("location:shippingCompanies.php");
exit();
