<?php

session_start();
include('server/connection.php');

if (isset($_SESSION['logged_in'])){
  header('location: account.php');
  exit;
}

if(isset($_POST['login_btn'])){

  $email = $_POST['email'];
  $password = md5($_POST['password']);

  $stmt = $conn->prepare("SELECT user_id, user_name, user_surname, user_email, user_password, user_status FROM users 
                          WHERE user_email = ? AND user_password = ?
                          LIMIT 1");

  $stmt->bind_param('ss', $email, $password);

  if($stmt->execute()){
    $stmt->bind_result($user_id, $user_name, $user_surname, $user_email, $user_password, $user_status);
    $stmt->store_result();

    if($stmt->num_rows() == 1){
      $stmt->fetch();

      $_SESSION['user_id'] = $user_id;
      $_SESSION['user_name'] = $user_name;
      $_SESSION['user_surname'] = $user_surname;
      $_SESSION['user_email'] = $user_email;
      $_SESSION['user_status'] = $user_status;
      $_SESSION['logged_in'] = true;

      if ($user_status === 'admin') {
          header('location: admin/index.php');
      } else {
          header('location: account.php?login_success=Veiksmīgi pieteikties!');
      }
      exit;

    }else {

      header('location: login.php?error=Nevarēja verificēt jūsu kontu!');

    }
  }else {

    header('location: login.php?error=Kaut kas noiet greizi!');

  }
}

?>

<?php include('layouts/header.php'); ?>

      <!--Login-->

      <section>
        <div class="container text-center mt-3 pt-5">
            <h2 class="form-weight-bold">Pieslēgties</h2>
            <hr class="mx-auto">
        </div>

        <div class="mx-auto container">
            <form id="login-form" method="POST" action="login.php">
              <p style="color: red;" class="text-center"><?php if(isset($_GET['error'])){ echo $_GET['error']; }?></p>
              <p style="color: green;" class="text-center"><?php if(isset($_GET['message'])){ echo $_GET['message']; }?></p>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control" id="login-email" name="email" placeholder="E-mail" required>
                </div>
                <div class="form-group">
                    <label>Parole</label>
                    <input type="password" class="form-control" id="login-password" name="password" placeholder="Parole" required>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn" id="login-btn" name="login_btn" value="Pieslēgties">
                </div>
                <div class="form-group">
                    <a id="register-url" class="btn" href="register.php">Vai jums nav konta? Reģistrēties</a>
                </div>
                
            </form>
        </div>
      </section>

<?php include('layouts/footer.php'); ?>