<?php

session_start();

include('server/connection.php');

  //Ja lietotājs jau ir reģistrējies, nogādājiet lietotāju uz konta lapu
if (isset($_SESSION['logged_in'])){
    header('location: account.php');
    exit;
}

if(isset($_POST['register'])){

    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if($password !== $confirmPassword){
        header('location: register.php?error=Paroles nesakrīt');
    }

    else if(strlen($password) < 8){
        header('location: register.php?error=Parolei jābūt vismaz 8 rakstzīmēm');

    //ja nav kļūdu
    }else{

        //pārbaudiet, vai ir kāds lietotājs ar šo e-pastu
        $stmt1 = $conn->prepare("SELECT count(*) FROM users
                                    WHERE user_email = ?");

        $stmt1->bind_param('s', $email);
        $stmt1->execute();
        $stmt1->bind_result($num_rows);
        $stmt1->store_result();
        $stmt1->fetch();

        if($num_rows != 0){
            header('location: register.php?error=Lietotājs ar šo e-pasta adresi jau pastāv');

        //Ja neviens lietotājs iepriekš nav reģistrējies ar šo e-pastu
        }else {
            //izveidot jaunu lietotāju
            $stmt = $conn->prepare("INSERT INTO users (user_name, user_surname, user_email, user_password)
                                    VALUES (?,?,?,?)");

            $stmt->bind_param('ssss', $name, $surname, $email, md5($password));

            //Ja konts ir izveidots veiksmīgi
            if($stmt->execute()){
                $user_id = $stmt->$insert_id;
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_name'] = $name;
                $_SESSION['user_surname'] = $surname;
                $_SESSION['user_email'] = $email;
                $_SESSION['logged_in'] = true;

                header('location: account.php?register_success=Jūs veiksmīgi reģistrējāties!');

                //Ja kontu nevarēja izveidot
            }else {

                header('location: register.php?error=Pašlaik nevar izveidot kontu!');

            }
        }
    }
}

?>

<?php include('layouts/header.php'); ?>

    <!--Reģistrācija-->

    <section class="my-5 py-5">
        <div class="container text-center mt-3 pt-5">
            <h2 class="form-weight-bold">Reģistrēties</h2>
            <hr class="mx-auto">
        </div>

        <div class="mx-auto container">
            <form id="register-form" method="POST" action="register.php">
                <p style="color: red;" class="text-center"><?php if(isset($_GET['error'])){ echo $_GET['error']; }?></p>
                <p style="color: green;" class="text-center"><?php if(isset($_GET['message'])){ echo $_GET['message']; }?></p>
                <div class="form-group">
                    <label>Vārds</label>
                    <input type="text" class="form-control" id="register-name" name="name" placeholder="Vārds" required>
                </div>
                <div class="form-group">
                    <label>Uzvārds</label>
                    <input type="text" class="form-control" id="register-surname" name="surname" placeholder="Uzvārds" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control" id="register-email" name="email" placeholder="E-mail" required>
                </div>
                <div class="form-group">
                    <label>Parole</label>
                    <input type="password" class="form-control" id="register-password" name="password" placeholder="Parole" required>
                </div>
                <div class="form-group">
                    <label>Apstipriniet paroli</label>
                    <input type="password" class="form-control" id="register-confirm-password" name="confirmPassword" placeholder="Apstipriniet paroli" required>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn" id="register-btn" name="register" value="Reģistrēties">
                </div>
                <div class="form-group">
                    <a id="login-url" class="btn" href="login.php">Jums jau ir konts? Pieslēgties</a>
                </div>
                
            </form>
        </div>
    </section>

<?php include('layouts/footer.php'); ?>