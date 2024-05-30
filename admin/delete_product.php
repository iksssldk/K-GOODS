<?php

session_start();

include('../server/connection.php');

if(!isset($_SESSION['admin_logged_in'])){
    header('location: login.php');
    exit;
}

if(isset($_GET['product_id'])){
    $product_id = $_GET['product_id'];

    $stmt = $conn->prepare("UPDATE products SET availability = FALSE 
                            WHERE product_id = ? ");
    $stmt->bind_param('i', $product_id);
    
    if($stmt->execute()){
        header('location: products.php?deleted_successfully=Prece ir veiksmīgi izdzēsts');

    } else {
        header('location: products.php?deleted_failure=Nevarēja izdzēst preci');
    }
}

?>