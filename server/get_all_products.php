<?php

include('connection.php');

$stmt = $conn->prepare("SELECT * FROM products");

$stmt->execute();
$all_products = $stmt->get_result();

?>