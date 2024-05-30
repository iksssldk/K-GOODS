<?php

session_start();

include('connection.php');

if (isset($_GET['transaction_id']) && isset($_GET['order_id'])){
    
    $order_id = $_GET['order_id'];
    $order_status = "Apmaksāts";
    $transaction_id = $_GET['transaction_id'];
    $user_id = $_SESSION['user_id'];
    $payment_date = date('Y-m-d H:i:s');

    //Mainiet order_status uz apmaksātu
    $stmt = $conn->prepare("UPDATE orders SET order_status = ?
                                WHERE order_id = ?");

    $stmt->bind_param('si', $order_status, $order_id);
    $stmt->execute();

    //Saglabāt maksājumu informācija
    $stmt1 = $conn->prepare("INSERT INTO payments (order_id, user_id, transaction_id, payment_date)
                        VALUE (?,?,?,?); ");

    $stmt1->bind_param('iiss', $order_id, $user_id, $transaction_id, $payment_date);

    $stmt1->execute();

    //Dodieties uz lietotāja profilu
    header('location: ../account.php?payment_success=Apmaksāts veiksmīgi, paldies, ka iepirkāties pie mums!');

}else {
    header('location: index.php');
    exit;
}

?>