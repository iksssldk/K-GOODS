<?php 
include('../server/connection.php');

if(!isset($_SESSION['logged_in'])){
    header('location: ../login.php');
    exit;
}

if(isset($_POST['create_product'])){
    $product_name = $_POST['name'];
    $product_description = $_POST['description'];
    $product_price = $_POST['price'];
    $product_color = $_POST['color'];
    $product_category = $_POST['category'];

    $image = $_FILES['image']['tmp_name'];
    $image_name = $product_name.".jpeg";

    //augšupielādēt attēlu
    move_uploaded_file($image,"../assets/imgs/".$image_name);

    //pievienot jaunu preci
    $stmt = $conn->prepare("INSERT INTO products (product_name, product_description, product_price, product_color, product_category, product_image)
                            VALUES (?,?,?,?,?,?) ");
    $stmt->bind_param('ssssss', $product_name, $product_description, $product_price, $product_color, $product_category, $image_name);
    
    if($stmt->execute()){
        header('location: products.php?product_created=Prece ir veiksmīgi pievinots!!');

    }else {
        header('location: products.php?product_failed=Radās kļūda, mēģiniet vēlreiz!');
    }
}

?>