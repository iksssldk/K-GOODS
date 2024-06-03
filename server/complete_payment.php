<?php

session_start();

include('connection.php');

if (isset($_GET['transaction_id']) && isset($_GET['order_id'])){
    
    $order_id = $_GET['order_id'];
    $order_status = "Apmaksāts";
    $transaction_id = $_GET['transaction_id'];
    $user_id = $_SESSION['user_id'];
    $payment_date = date('Y-m-d H:i:s');
    $transaction_amount = $_GET['transaction_amount']; // Предполагаем, что это значение передается в запросе
    $transaction_date = $_GET['transaction_date']; 

    // Проверка наличия transaction_id в таблице transactions и получение дополнительных данных
    $stmt = $conn->prepare("SELECT transaction_id FROM transactions WHERE transaction_id = ?");
    $stmt->bind_param('s', $transaction_id);
    $stmt->execute();
    $stmt->store_result();

    // Проверяем, существует ли уже запись с таким transaction_id в таблице transactions
    if ($stmt->num_rows == 0) {
        // Если нет, то добавляем новую запись в таблицу transactions
        $stmt1 = $conn->prepare("INSERT INTO transactions (transaction_id, transaction_amount, transaction_date)
                                                VALUES (?, ?, ?)");
        $stmt1->bind_param('sss', $transaction_id, $transaction_amount, $transaction_date);
        $stmt1->execute();
        $stmt1->close();
    }

        //Mainiet order_status uz apmaksātu
        $stmt2 = $conn->prepare("UPDATE orders SET order_status = ? 
                                WHERE order_id = ?");
        $stmt2->bind_param('si', $order_status, $order_id);
        $stmt2->execute();

        //Saglabāt maksājumu informācija
        $stmt3 = $conn->prepare("INSERT INTO payments (order_id, user_id, transaction_id, payment_date) 
                VALUES (?, ?, ?, ?)");
        $stmt3->bind_param('iiss', $order_id, $user_id, $transaction_id, $payment_date);
        $stmt3->execute();
        $stmt3->close();

        //Dodieties uz lietotāja profilu
        header('location: ../account.php?payment_success=Apmaksāts veiksmīgi, paldies, ka iepirkāties pie mums!');

}else {
    header('location: index.php');
    exit;
}

?>