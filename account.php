<?php

session_start();

include('server/connection.php');

if (!isset($_SESSION['logged_in'])){
  header('location: login.php');
  exit;
}

if (isset($_GET['logout'])){
    if(isset($_SESSION['logged_in'])){
        unset($_SESSION['logged_in']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_surname']);
        header('location: login.php');
        exit;
    }
}

if(isset($_POST['change_password'])){

    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $user_email = $_SESSION['user_email'];

    if($password !== $confirmPassword){
        header('location: account.php?error=Paroles nesakrīt!');

    }else if(strlen($password) < 8){
        header('location: account.php?error=Parolei jābūt vismaz 8 rakstzīmēm!');

    //ja nav kļūdu
    }else{
        $stmt = $conn->prepare("UPDATE users SET user_password = ?
                                WHERE user_email = ?");

        $stmt->bind_param('ss', md5($password), $user_email);

        if($stmt->execute()){
            header('location: account.php?message=Parole ir veiksmīgi atjaunināta!');

        }else {
            header('location: account.php?error=Nevarēja atjaunināt paroli!');
        }
    }
}

//saņemt pasūtījumus
if (isset($_SESSION['logged_in'])){

    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT * FROM orders
                            WHERE user_id = ? ");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $orders = $stmt->get_result();

}

?>

<?php include('layouts/header.php'); ?>

    <main>
          <!--Account-->
        <p style="color: green;" class="text-center"><?php if(isset($_GET['register_success'])){ echo $_GET['register_success']; }?></p>
        <p style="color: green;" class="text-center"><?php if(isset($_GET['login_success'])){ echo $_GET['login_success']; }?></p>
        <p style="color: green;" class="text-center"><?php if(isset($_GET['payment_success'])){ echo $_GET['payment_success']; }?></p>
        <p style="color: red;" class="text-center"><?php if(isset($_GET['deleted_failure'])){ echo $_GET['deleted_failure']; }?></p>
        <p style="color: red;" class="text-center"><?php if(isset($_GET['transaction_failure'])){ echo $_GET['transaction_failure']; }?></p>
        <section class="my-5 py-5">
            <div class="row container mx-auto">
                <div class="text-center col-lg-6 col-md-12 col-sm-12">
                    <h2 class="font-weight-bold">Konta informācija</h2>
                    <hr class="mx-auto">
                    <div class="account-info">
                        <p>Vārds: <span><?php if(isset($_SESSION['user_name'])){ echo $_SESSION['user_name']; }?></span></p>
                        <p>Uzvārds: <span><?php if(isset($_SESSION['user_surname'])){ echo $_SESSION['user_surname']; }?></span></p>
                        <p>Email: <span><?php if(isset($_SESSION['user_email'])){ echo $_SESSION['user_email']; }?></span></p>
                        <p><a href="#orders" id="orders-btn">Jūsu pasūtījumi</a></p>
                        <p><a href="account.php?logout=1" id="logout-btn">Iziet</a></p>
                        <p style="color: blue;"><a href="delete_account.php?user_id=<?php echo $_SESSION['user_id']; ?>" id="delete-btn">Dzēst profilu</a></p>
                    </div>
                </div>

                <div class="col-lg-6 col-md-12 col-sm-12">
                    <form id="account-form" method="POST" action="account.php">
                        <h3>Mainiet paroli</h3>
                        <hr class="mx-auto">
                        <p style="color: red;" class="text-center"><?php if(isset($_GET['error'])){ echo $_GET['error']; }?></p>
                        <p style="color: green;" class="text-center"><?php if(isset($_GET['message'])){ echo $_GET['message']; }?></p>
                        <div class="form-group">
                            <label>Parole</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Parole" required>
                        </div>
                        <div class="form-group">
                            <label>Apstipriniet paroli</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirmPassword" placeholder="Apstipriniet paroli" required>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="change-btn" id="change_password" name="change_password" value="Mainiet paroli">
                        </div>
                    </form>
                </div>
            </div>
        </section>

                <!--Orders-->
        <section id="orders" class="orders container my-5 py-3">
            <div class="container mt-2">
                <h2 class="font-weight-bold text-center">Jūsu pasūtījumi</h2>
                <hr class="mx-auto">
            </div>

            <table class="mt-5 pt-5 mx-auto">
                <tr>
                    <th>Pasūtījuma ID</th>
                    <th>Pasūtījuma izmaksas</th>
                    <th>Status</th>
                    <th>Datums</th>
                    <th>Pasūtījuma detaļas</th>
                </tr>

                <?php while($row = $orders->fetch_assoc()){?>

                    <tr>
                        <td>
                            <span><?php echo isset($row['order_id']) ? $row['order_id'] : ''; ?></span>
                        </td>

                        <td>
                            <span><?php echo isset($row['order_cost']) ? $row['order_cost'] : '';  ?>€</span>
                        </td>

                        <td>
                            <span><?php echo isset($row['order_status']) ? $row['order_status'] : '';  ?></span>
                        </td>

                        <td>
                            <span><?php echo isset($row['order_date']) ? $row['order_date'] : '';  ?></span>
                        </td>

                        <td>
                            <form method="POST" action="order_details.php">
                                <input type="hidden" value="<?php echo isset($row['order_id']) ? $row['order_id'] : ''; ?>" name="order_id">
                                <input type="hidden" value="<?php echo isset($row['order_status']) ? $row['order_status'] : ''; ?>" name="order_status">
                                <input class="btn order-details-btn" type="submit" name="order_details_btn" value="Detaļas">
                            </form>
                        </td>
                    </tr>

                <?php } ?>

            </table>
        </section>
    </main>

<?php include('layouts/footer.php'); ?>