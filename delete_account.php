<?php

session_start();
include('server/connection.php');

if(isset($_GET['user_id'])){
    $user_id = $_GET['user_id'];

    $stmt = $conn->prepare("DELETE FROM users
                            WHERE user_id = ? ");
    $stmt->bind_param('i', $user_id);
    
    if($stmt->execute()){
        session_unset();
        session_destroy();
        header('location: login.php');

    } else {
        header('location: account.php?deleted_failure=Nevarēja izdzēst profilu');
    }
} else {
    header('location: login.php');
    exit;
}

?>