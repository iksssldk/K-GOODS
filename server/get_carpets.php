<?php

include('connection.php');

// 1. Noteikt lapas numuru
if(isset($_GET['page_no']) && $_GET['page_no'] != "") {
    $page_no = $_GET['page_no'];
} else {
    $page_no = 1;
}

// 2. Preces atgriešanas numurs
$stmt = $conn->prepare("SELECT COUNT(*) AS total_records FROM products WHERE product_category = 'Paklāji'");
$stmt->execute();
$stmt->bind_result($total_records);
$stmt->store_result();
$stmt->fetch();

// 3. Preces vienā lapā
$total_records_per_page = 4;

$offset = ($page_no - 1) * $total_records_per_page;

$previous_page = $page_no - 1;
$next_page = $page_no + 1;

$adjacents = "2";

$total_no_of_pages = ceil($total_records / $total_records_per_page);

// 4. Saņemt visas preces ar limitu un офсетом
$stmt1 = $conn->prepare("SELECT * FROM products WHERE product_category = 'Paklāji' LIMIT ?, ?");
$stmt1->bind_param("ii", $offset, $total_records_per_page);
$stmt1->execute();
$carpets_products = $stmt1->get_result();

?>