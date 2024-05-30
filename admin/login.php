<?php include('header.php'); ?>

<?php

include('../server/connection.php');

if (isset($_SESSION['admin_logged_in'])){
  header('location: index.php');
  exit;
}

if(isset($_POST['login_btn'])){

  $email = $_POST['email'];
  $password = md5($_POST['password']);

  $stmt = $conn->prepare("SELECT admin_id, admin_name, admin_email, admin_password FROM admins 
                          WHERE admin_email = ? AND admin_password = ?
                          LIMIT 1");

  $stmt->bind_param('ss', $email, $password);

  if($stmt->execute()){
    $stmt->bind_result($admin_id, $admin_name, $admin_email, $admin_password);
    $stmt->store_result();

    if($stmt->num_rows() == 1){
      $stmt->fetch();

      $_SESSION['admin_id'] = $admin_id;
      $_SESSION['admin_name'] = $admin_name;
      $_SESSION['admin_email'] = $admin_email;
      $_SESSION['admin_logged_in'] = true;

      header('location: index.php?login_success=Veiksmīgi pieteikties!');

    }else {

      header('location: login.php?error=Nevarēja verificēt jūsu kontu!');

    }
  }else {

    header('location: login.php?error=Kaut kas noiet greizi!');

  }
}

?>
      <!--Login admin-->

      <section class="my-5 py-5">
        <div class="container text-center mt-3 pt-5">
            <h2 class="form-weight-bold">Pieslēgties</h2>
            <hr class="mx-auto">
        </div>

        <div class="mx-auto container">
            <form id="login-form" enctype="multipart/form-data" method="POST" action="login.php">
              <p style="color: red;" class="text-center"><?php if(isset($_GET['error'])){ echo $_GET['error']; }?></p>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control" id="login-email" name="email" placeholder="E-mail" required>
                </div>
                <div class="form-group mt-2">
                    <label>Parole</label>
                    <input type="password" class="form-control" id="login-password" name="password" placeholder="Parole" required>
                </div>
                <div class="form-group mt-3">
                    <input type="submit" class="btn btn-primary" id="login-btn" name="login_btn" value="Pieslēgties">
                </div>
                
            </form>
        </div>
      </section>