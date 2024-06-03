<?php

session_start();

include('connection.php');

//Ja lietotājs ir pieteicies
if(!isset($_SESSION['logged_in'])){
    header('location: ../checkout.php?message=Lūdzu, pieslēgties/reģistrējieties, lai veiktu pasūtījumu!');
    exit;
}

if(isset($_POST['place_order'])){

    //1. iegūstiet lietotāja informāciju un saglabājiet to datu bāzē
    $user_id = $_SESSION['user_id'];
    $phone = $_POST['phone'];
    $country = $_POST['country'];
    $address = $_POST['address'];
    $order_cost = $_SESSION['total'];
    $order_status = "Nav apmaksāts";
    $order_date = date('Y-m-d H:i:s');

    // Обновление данных о пользователе
    $stmt_update_user = $conn->prepare("UPDATE users SET user_phone = ?, user_country = ?, user_address = ? WHERE user_id = ?");
    $stmt_update_user->bind_param('sssi', $phone, $country, $address, $user_id);
    $stmt_update_user->execute();

    $stmt = $conn->prepare("INSERT INTO orders (order_cost, order_status, user_id, order_date)
                        VALUE (?,?,?,?); ");

    $stmt->bind_param('ssis', $order_cost, $order_status, $user_id, $order_date);

    $stmt_status = $stmt->execute();

    If(!$stmt_status){
        header('location: index.php');
        exit;
    }

    //2. izdot jaunu pasūtījumu un uzglabāt informāciju par pasūtījumu datu bāzē
    $order_id = $stmt->insert_id;


    //3. saņemt preces no groza
    foreach($_SESSION['cart'] as $key => $value){

        $product = $_SESSION['cart'] [$key];
        $product_id = $product['product_id'];
        $product_quantity = $product['product_quantity'];

        //4. saglabāt katru atsevišķo vienumu uz cart_items datu bāzē
        $stmt1 = $conn->prepare("INSERT INTO cart_items (order_id, product_id, quantity)
                                     VALUES (?, ?, ?)");
        $stmt1->bind_param('iii', $order_id, $product_id, $product_quantity);
        $stmt1->execute();
    }

    //5. izņemt visu no groza -> kavēties līdz maksājuma veikšanai
    $_SESSION['order_id'] = $order_id;
    $_SESSION['cart'] = [];
    $_SESSION['quantity'] = 0;

    //6. informē lietotāju, vai viss ir kārtībā vai ir problēma
    header('location: ../payment.php?order_status=Pasūtījums veiksmīgi veikts');
    exit;

}
?>